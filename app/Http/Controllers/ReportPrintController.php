<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Mail\DailyPharmacyReport;
use App\Models\Items;
use App\Models\Pharmacy;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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
        $pharmacyId = session('current_pharmacy_id');
        $pharmacy = Pharmacy::find($pharmacyId);

        return view('reports.reports', compact('medicines', 'pharmacy'));
    }

    public function filterReports(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
            'category' => 'required|in:sales,stocks,profits,expired',
            'medicine' => 'required|integer',
        ]);

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
        $rows = 0;


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

            //create a variable to count number of rows in the profitsRows and in the stocksRows
            if ($category == 'sales') {
                $rows = $sales->count();
            } else if ($category == 'stocks') {
                $rows = $stocks->count();
            }


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
            // $totalSales = $sales->sum('total_price');
            // sum of(quantity*sellingprice) of all sales total
            $totalSales = $sales->sum(DB::raw('quantity * total_price'));
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
                'profits' => $profitsRows,
                'rows' => $rows
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
                'profits' => $profitsRows,
                'rows' => $rows
            ]);
        }
    }

    public function sendReport(Request $request)
    {
        try {
            $pharmacy_id = session('current_pharmacy_id');
            $pharmacy = Pharmacy::find($pharmacy_id);
            // validate start and end date
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            if ($request->start_date == $request->end_date) {
                $reportDate = Carbon::parse($request->start_date)->format('F j, Y');
                $message = 'daily';
            } else {
                $reportDate = Carbon::parse($request->start_date)->format('F j, Y') . ' to ' . Carbon::parse($request->end_date)->format('F j, Y');
                $message = 'custom';
            }

            // dd($request->all());    

            // $start_date = Carbon::parse($request->start_date);
            // $end_date = Carbon::parse($request->end_date);

            // $today = Carbon::today();

            // ----------------------
            // Sales summary (aggregated in SQL)
            // ----------------------
            $salesData = Sales::where('pharmacy_id', $pharmacy_id)
                ->whereDate('date', '>=', $request->start_date)
                ->whereDate('date', '<=', $request->end_date)
                ->selectRaw('
                                        COALESCE(SUM(total_price * quantity), 0) as total_revenue,
                                        COALESCE(SUM(quantity), 0) as total_quantity,
                                        COUNT(*) as total_transactions
                                    ')->first();

            // Compute total cost directly with a join (avoid per-row loops)
            $totalCost = Sales::where('sales.pharmacy_id', $pharmacy_id)
                ->whereDate('sales.date', '>=', $request->start_date)
                ->whereDate('sales.date', '<=', $request->end_date)
                ->join('stocks', 'sales.stock_id', '=', 'stocks.id')
                ->selectRaw('COALESCE(SUM(stocks.buying_price * sales.quantity), 0) as total_cost')
                ->value('total_cost');

            /** 
             * @var array<string, int|float> $salesSummary 
             */
            $salesSummary = [
                'total_revenue'      => (float) ($salesData->total_revenue ?? 0),
                'total_cost'         => (float) ($totalCost ?? 0),
                'total_transactions' => (int)   ($salesData->total_transactions ?? 0),
                'profit_loss'        => (float) (($salesData->total_revenue ?? 0) - ($totalCost ?? 0)),
            ];

            // dd($salesSummary, $salesData, $totalCost);


            // ----------------------
            // Stock status (categorized in PHP but fetched once)
            // ----------------------
            $stocks = Stock::where('pharmacy_id', $pharmacy_id)
                ->select(['id', 'item_id', 'quantity', 'remain_Quantity', 'low_stock_percentage', 'expire_date', 'batch_number', 'supplier'])
                ->with('item:id,name') // load only what’s needed
                ->get();

            // dd($stocks);

            /** 
             * @var array<string, \Illuminate\Support\Collection<int, \App\Models\Stock>> $stockStatus 
             */
            $stockStatus = [
                'out_of_stock' => $stocks->where('remain_quantity', 0)->values(),
                'low_stock' => $stocks->filter(function ($stock) {
                    if ($stock->remain_quantity <= 0) return false;
                    $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
                    return $stock->remain_quantity <= $threshold;
                })->values(),
                'expired' => $stocks->filter(fn($stock) => Carbon::parse($stock->expire_date)->isPast())->values(),
                'good_stock' => $stocks->filter(function ($stock) {
                    if ($stock->remain_quantity <= 0) return false;
                    if (Carbon::parse($stock->expire_date)->isPast()) return false;
                    $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
                    return $stock->remain_quantity > $threshold;
                })->values(),
            ];

            // ----------------------
            // Queue email (non-blocking)
            // ----------------------
            if ($pharmacy->owner && $pharmacy->owner->email) {
                Mail::to($pharmacy->owner->email)
                    ->send(new DailyPharmacyReport($pharmacy, $salesSummary, $stockStatus, $reportDate, $message));
                return redirect()->back()->with('success', 'Report sent successfully');
            } else {
                return redirect()->back()->with('error', 'No email found for pharmacy');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error sending report: ' . $e->getMessage());
        }
    }
}
