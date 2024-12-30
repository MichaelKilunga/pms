<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use PDF;

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
            'day' => Sales::whereDate('date', $value)->get(),
            'week' => Sales::whereBetween('date', [
                date('Y-m-d', strtotime('monday this week', strtotime($value))),
                date('Y-m-d', strtotime('sunday this week', strtotime($value)))
            ])->get(),
            'month' => Sales::whereMonth('date', date('m', strtotime($value)))
                            ->whereYear('date', date('Y', strtotime($value)))
                            ->get(),
            'year' => Sales::whereYear('date', date('Y', strtotime($value)))->get(),
            default => collect(),
        };

        if ($format === 'pdf') {
            // Generate PDF
            $pdf = PDF::loadView('reports.sales', ['sales' => $sales, 'type' => $type, 'value' => $value]);
            return $pdf->download("sales_report_{$type}_{$value}.pdf");
        }

        if ($format === 'excel') {
            // Generate Excel
            return Excel::download(new SalesReportExport($sales), "sales_report_{$type}_{$value}.xlsx");
        }
        
    }
}
