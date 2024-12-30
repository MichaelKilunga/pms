<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesReportExport implements FromView
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function view(): View
    {
        return view('reports.sales', [
            'sales' => $this->sales,
        ]);
    }
}
