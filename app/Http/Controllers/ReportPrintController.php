<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Models\Items;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;

// use PDF;
// use Barryvdh\DomPDF\PDF;

class ReportPrintController extends Controller
{
    // View for selecting the report type
    public function index()
    {
        return view('reports.index');
    }

    // Generate the report
    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:day,week,month,year',
            'value' => 'required|date',
            'format' => 'required|in:pdf,excel',
        ]);

        $type = $request->type;
        $value = $request->value;
        $format = $request->format;

        // Filter sales based on the selected type
        // $sales = Sales::join('stocks', 'sales.stock_id', '=', 'stocks.id')
        //      ->join('items', 'sales.item_id', '=', 'items.id')
        //      ->select('sales.*', 'stocks.*', 'items.*')
        //      ->get();

        $sales = match ($type) {
            'day' => Sales::with('item')->whereDate('sales.date', $value)->get(),
            'week' => Sales::with('item')->whereBetween('sales.date', [
                date('Y-m-d', strtotime('monday this week', strtotime($value))),
                date('Y-m-d', strtotime('sunday this week', strtotime($value)))
            ])->get(),
            'month' => Sales::with('item')->whereMonth('sales.date', date('m', strtotime($value)))
                ->whereYear('date', date('Y', strtotime($value)))
                ->get(),
            'year' => Sales::with('item')->whereYear('sales.date', date('Y', strtotime($value)))->get(),
            default => collect(),
        };

        if ($format === 'pdf') {
            // Generate PDF
            $pdf = Pdf::loadView('reports.sales', ['sales' => $sales, 'type' => $type, 'value' => $value]);
            return $pdf->download("sales_report_{$type}_{$value}.pdf");
        }

        if ($format === 'excel') {
            // Generate Excel
            return Excel::download(new SalesReportExport($sales), "sales_report_{$type}_{$value}.xlsx");
        }
    }

    public function all()
    {
        $medicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->get();
        return view('reports.reports', compact('medicines'));
    }

    public function filterReports(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
            'category' => 'required|in:sales,stocks,profits,expired',
            'medicine' => 'required|integer',
        ]);

        //Initialize below response variables to 0 to avoid errors
        // return response()->json([
        //     'totalSales' => $totalSales ?? 0,
        //     'totalStocks' => $totalStocks ?? 0,
        //     'totalReturns' => $totalReturns ?? 0,
        //     'totalProfit' => $totalProfit ?? 0,
        //     'totalExpired' => $totalExpired ?? 0,
        //     'labels' => $labels,
        //     'data' => $data,
        //     'sales' => $salesRows,
        //     'stocks' => $stocksRows,
        //     'expired' => $expiredRows,
        //     'profits' => $profitsRows
        // ]);

        $totalSales = 0;
        $totalStocks = 0;
        $totalReturns = 0;
        $totalProfit = 0;
        $totalExpired = 0;
        $labels = [];
        $data = [];
        $salesRows = [];
        $stocksRows = [];
        $expiredRows = [];
        $profitsRows = [];


        // Validate and parse dates
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end)->endOfDay();
        $category = $request->category;
        $medicine = $request->medicine;
        $pharmacyId = session('current_pharmacy_id');

        try {
            // Fetch all sales and calculate profits
            $sales = Sales::with('stock', 'item')
                ->where('pharmacy_id', $pharmacyId)
                ->whereBetween('created_at', [$startDate, $endDate]);

            // Fetch all expired stocks and calculate losses
            $stocks = Stock::with('item')
                ->where('pharmacy_id', $pharmacyId)
                ->whereBetween('created_at', [$startDate, $endDate]);


            if ($medicine != 0) {
                $sales->where('item_id', $medicine);
                $stocks->where('item_id', $medicine);
            }

            
            //total costs encurred to buy all stocks as the sum of the product of buying price and quantity
            $totalStocks = $stocks->sum(DB::raw('buying_price * quantity'));


            // Fetch all sales and stocks        
            $salesRows = $sales->get();
            $stocksRows = $stocks->get();

            // Calculate expired stocks
            $expiredRows = $stocks->where('expire_date', '<', now())->get()->map(function ($stock) {
                $loss = $stock->buying_price * $stock->remain_Quantity;
                return [
                    'item_name' => $stock->item->name,
                    'batch_number' => $stock->batch_number,
                    'amount' => $stock->remain_Quantity,
                    'loss_incurred' => $loss
                ];
            });

            // Calculate profits
            $profitsRows = $sales->get()->map(function ($sale) {
                $profit = ($sale->stock->selling_price - $sale->stock->buying_price) * $sale->quantity;
                return [
                    'item_name' => $sale->item->name,
                    'batch_number' => $sale->stock->batch_number,
                    'amount' => $profit
                ];
            });

            // Calculate totals
            $totalSales = $sales->sum('total_price');
            $totalReturns = 0;
            $totalProfit = $profitsRows->sum('amount');
            $totalExpired = $expiredRows->count();

            // Generate labels and data for the chart, where the graph should not change, rather let it always draw medicines names  (as labels) Vs percentage profit the medicine contributes to the total profit (as data).
            $labels = $profitsRows->map(function ($profit) {
                return $profit['item_name'];
            });

            $data = $profitsRows->map(function ($profit) use ($totalProfit) {
                return ($profit['amount'] / $totalProfit) * 100;
            });

            return response()->json([
                'success' => true,
                'totalSales' => $totalSales ?? 0,
                'totalStocks' => $totalStocks ?? 0,
                'totalReturns' => $totalReturns ?? 0,
                'totalProfit' => $totalProfit ?? 0,
                'totalExpired' => $totalExpired ?? 0,
                'labels' => $labels,
                'data' => $data,
                'sales' => $salesRows,
                'stocks' => $stocksRows,
                'expired' => $expiredRows,
                'profits' => $profitsRows
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'totalSales' => $totalSales ?? 0,
                'totalStocks' => $totalStocks ?? 0,
                'totalReturns' => $totalReturns ?? 0,
                'totalProfit' => $totalProfit ?? 0,
                'totalExpired' => $totalExpired ?? 0,
                'labels' => $labels,
                'data' => $data,
                'sales' => $salesRows,
                'stocks' => $stocksRows,
                'expired' => $expiredRows,
                'profits' => $profitsRows
            ]);
        }
    }
}
