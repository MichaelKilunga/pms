<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
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
            'type' => 'required|in:day,month,year',
            'value' => 'required|date',
            'format' => 'required|in:pdf,excel',
        ]);

        $type = $request->type;
        $value = $request->value;
        $format = $request->format;

        // Filter sales based on the selected type
        $sales = match ($type) {
            'day' => Sale::whereDate('date', $value)->get(),
            'month' => Sale::whereMonth('date', date('m', strtotime($value)))
                           ->whereYear('date', date('Y', strtotime($value)))
                           ->get(),
            'year' => Sale::whereYear('date', date('Y', strtotime($value)))->get(),
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
