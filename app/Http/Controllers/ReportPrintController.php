<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function all(){
        return view('reports.reports');
    }
}
