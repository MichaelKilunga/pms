<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InventoryService;
use App\Models\Sales;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Installment;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
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

use function Laravel\Prompts\alert;

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
        // $expenses = Expense::where('pharmacy_id', $pharmacyId)->get();
        $expenses = Expense::where('pharmacy_id', session('current_pharmacy_id'))
            ->with(['category', 'vendor', 'creator'])->get();
        // $debt = Debt::where('pharmacy_id', $pharmacyId)->get();
        //return stock with medicine that has not listed on stock debts
        $stocks = Stock::with('item')
            ->where('pharmacy_id', session('current_pharmacy_id'))
            ->whereDoesntHave('debts')
            ->get();

        $debts = Debt::with('stock', 'installments')
            ->where('pharmacy_id', session('current_pharmacy_id'))
            ->get();

             //get all installments with debt and stock
        $installments = Installment::with('debt.stock')
         ->where('pharmacy_id', session('current_pharmacy_id'))
        ->get();


        return view('reports.reports', compact('medicines', 'pharmacy', 'expenses', 'debts', 'stocks', 'installments'));
    }

    public function filterReports(Request $request)
    {

        //if request category is stock or category
        // if ($request->category == 'stocks' || $request->category == 'sales') {
        if (in_array($request->category, ['stocks', 'sales', 'profits', 'expired'])) {

            //validate inputs for stock and sales
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
                    ->whereBetween('date', [$startDate, $endDate]);

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
                $grossProfit = $profitsRows->sum('amount');

                // NEW: Calculate approved expenses and installments for Net Profit
                $totalExpenses = Expense::where('pharmacy_id', $pharmacyId)
                    ->where('status', 'approved')
                    ->whereBetween('expense_date', [$startDate, $endDate])
                    ->sum('amount');

                $totalInstallments = Installment::where('pharmacy_id', $pharmacyId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('amount');

                $totalProfit = $grossProfit - $totalExpenses - $totalInstallments;
                $totalExpired = $expiredRows->count();

                // Calculate Top 10 and Bottom 10 Most Sold Medicines by Quantity
                $allSalesByMedicine = Sales::with('item')
                    ->where('pharmacy_id', $pharmacyId)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->select('item_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->groupBy('item_id')
                    ->get();

                $top10 = $allSalesByMedicine->sortByDesc('total_quantity')->take(10);
                $bottom10 = $allSalesByMedicine->sortBy('total_quantity')->take(10);

                $topLabels = $top10->map(fn($s) => $s->item->name ?? 'Unknown')->values();
                $topData = $top10->pluck('total_quantity')->values();

                $bottomLabels = $bottom10->map(fn($s) => $s->item->name ?? 'Unknown')->values();
                $bottomData = $bottom10->pluck('total_quantity')->values();

                return response()->json([
                    'success' => true,
                    'totalSales' => $totalSales ?? 0,
                    'totalStocks' => $totalStocks ?? 0,
                    'totalReturns' => $totalReturns ?? 0,
                    'totalProfit' => $totalProfit ?? 0,
                    'grossProfit' => $grossProfit ?? 0,
                    'totalExpenses' => $totalExpenses ?? 0,
                    'totalInstallments' => $totalInstallments ?? 0,
                    'totalExpired' => $totalExpired ?? 0,
                    'topLabels' => $topLabels,
                    'topData' => $topData,
                    'bottomLabels' => $bottomLabels,
                    'bottomData' => $bottomData,
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
        } else {
            // else if ($request->category == 'expenses' || $request->category == 'debts') {
            //validates inputs for expenses and debt
            $request->validate([
                'start' => 'required|date',
                'end' => 'required|date',
                'category' => 'required|in:expenses,debts,installments',
            ]);

            // Validate and parse dates
            $startDate = Carbon::parse($request->start);
            $endDate = Carbon::parse($request->end)->endOfDay();
            $category = $request->category;
            $pharmacyId = session('current_pharmacy_id');

            if ($category == 'expenses') {
                $expenses = Expense::with(['category', 'vendor', 'creator'])
                    ->where('pharmacy_id', $pharmacyId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                $totalExpenses = $expenses->sum('amount');

                return response()->json([
                    'success' => true,
                    'totalExpenses' => $totalExpenses ?? 0,
                    'expenses' => $expenses,
                    'rows' => $expenses->count()
                ]);
            } else if ($category == 'debts') {

                $debts = Debt::with(['stock.item', 'installments'])
                    ->where('pharmacy_id', $pharmacyId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                // Map debts to include totalPaid
                $debts = $debts->map(function ($debt) {
                    $debt->totalPaid = $debt->installments->sum('amount'); // same as Blade
                    $debt->remaining = $debt->debtAmount - $debt->totalPaid; // remaining debt
                    return $debt;
                });

                // Calculate totals
                $totalDebts = $debts->sum('debtAmount');
                $totalPaid = $debts->sum('totalPaid');
                $totalRemaining = $debts->sum('remaining');

                return response()->json([
                    'success' => true,
                    'totalDebts' => $totalDebts ?? 0,
                    'totalDeptsPaid' => $totalPaid ?? 0,
                    'totalDeptsRemaining' => $totalRemaining ?? 0,
                    'debts' => $debts,
                    'rows' => $debts->count()
                ]);
            }else if($category == 'installments'){
                $installments = Installment::with(['debt.stock.item'])
                    ->where('pharmacy_id', $pharmacyId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                    // dd($installments);

                $totalInstallments = $installments->sum('amount');
                return response()->json([
                    'success' => true,
                    'totalInstallments' => $totalInstallments,
                    'installments' => $installments,
                    'rows' => $installments->count()
                ]);
            }
        }
    }

    public function sendReport(Request $request, \App\Services\SmsService $smsService, \App\Services\MetaWhatsAppService $whatsAppService)
    {
        try {
            $pharmacyId = session('current_pharmacy_id');
            $pharmacy = Pharmacy::find($pharmacyId);
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

            $pharmacy_id = session('current_pharmacy_id'); //NIMEONGEZA HIKI KWA MUDA PLEASE
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

            // NEW: Fetch approved expenses and installments for the period
            $totalExpenses = Expense::where('pharmacy_id', $pharmacy_id)
                ->where('status', 'approved')
                ->whereDate('expense_date', '>=', $request->start_date)
                ->whereDate('expense_date', '<=', $request->end_date)
                ->sum('amount');

            $totalInstallments = Installment::where('pharmacy_id', $pharmacy_id)
                ->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ])
                ->sum('amount');

            $grossProfit = ($salesData->total_revenue ?? 0) - ($totalCost ?? 0);

            /** 
             * @var array<string, int|float> $salesSummary 
             */
            $salesSummary = [
                'total_revenue'      => (float) ($salesData->total_revenue ?? 0),
                'gross_profit'       => (float) $grossProfit,
                'total_expenses'     => (float) ($totalExpenses ?? 0),
                'total_installments' => (float) ($totalInstallments ?? 0),
                'total_transactions' => (int)   ($salesData->total_transactions ?? 0),
                'profit_loss'        => (float) ($grossProfit - $totalExpenses - $totalInstallments), // Net Profit
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
            // Send Notifications
            // ----------------------
            $owner = $pharmacy->owner;
            if(!$owner) {
                return redirect()->back()->with('error', 'No owner found for pharmacy');
            }

            $channels = $request->input('channels', ['email', 'sms', 'whatsapp', 'in_app']);
            if ($request->input('channel') === 'whatsapp') {
                $channels = ['whatsapp'];
            }

            $successMessages = [];

            // 1. Email
            if (in_array('email', $channels) && $owner->email && $owner->wantsNotificationChannel('email')) {
                Mail::to($owner->email)
                    ->send(new DailyPharmacyReport($pharmacy, $salesSummary, $stockStatus, $reportDate, $message));
                $successMessages[] = 'Email';
            }

            // 2. Push SMS
            if (in_array('sms', $channels) && $owner->phone && $owner->wantsNotificationChannel('sms')) {
                $smsMsg = "Daily Report: {$pharmacy->name}\nDate: {$reportDate}\nSales: " . number_format($salesSummary['total_revenue']) . " TZS\nGross Profit: " . number_format($salesSummary['gross_profit']) . " TZS\nExpenses: " . number_format($salesSummary['total_expenses']) . " TZS\nInstallments: " . number_format($salesSummary['total_installments']) . " TZS\nNet Profit: " . number_format($salesSummary['profit_loss']) . " TZS";
                $smsService->send($owner->phone, $smsMsg);
                $successMessages[] = 'SMS';
            }

            // 3. WhatsApp
            if (in_array('whatsapp', $channels) && $owner->phone && $owner->wantsNotificationChannel('whatsapp')) {
                
                // Construct WhatsApp Message
                $waMsg = "*Daily Report*\n";
                $waMsg .= "Pharmacy: {$pharmacy->name}\n";
                $waMsg .= "Date: {$reportDate}\n\n";
                $waMsg .= "*Summary:*\n";
                $waMsg .= "Total Sales: " . number_format($salesSummary['total_revenue']) . " TZS\n";
                $waMsg .= "Gross Profit: " . number_format($salesSummary['gross_profit']) . " TZS\n";
                $waMsg .= "Expenses: " . number_format($salesSummary['total_expenses']) . " TZS\n";
                $waMsg .= "Net Profit: " . number_format($salesSummary['profit_loss']) . " TZS\n";
                $waMsg .= "Installments: " . number_format($salesSummary['total_installments']) . " TZS\n";
                $waMsg .= "Transactions: " . $salesSummary['total_transactions'] . "\n\n";
                $waMsg .= "*Stock Alerts:*\n";
                $waMsg .= "Out of Stock: " . $stockStatus['out_of_stock']->count() . "\n";
                $waMsg .= "Low Stock: " . $stockStatus['low_stock']->count() . "\n";
                $waMsg .= "Expired: " . $stockStatus['expired']->count() . "\n";
                
                $whatsAppService->sendMessage($owner->phone, $waMsg);
                $successMessages[] = 'WhatsApp';
            }

            // 4. In-App
            if (in_array('in_app', $channels) && $owner->wantsNotificationChannel('database')) {
                \Illuminate\Support\Facades\Notification::send($owner, new \App\Notifications\InAppNotification([
                    'message' => "Daily Report Sent for {$reportDate}. Net Profit: " . number_format($salesSummary['profit_loss']) . " TZS (Expenses: " . number_format($salesSummary['total_expenses']) . ", Installments: " . number_format($salesSummary['total_installments']) . ")", 
                    'type' => 'info'
                ]));
                $successMessages[] = 'In-App';
            }

            if (count($successMessages) > 0) {
                return redirect()->back()->with('success', 'Report sent successfully via: ' . implode(', ', $successMessages));
            } else {
                 return redirect()->back()->with('warning', 'Report generated but no notifications were sent (check user preferences or contact details).');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error sending report: ' . $e->getMessage());
        }
    }

    public function getSuggestedStockJson(Request $request, InventoryService $inventoryService)
    {
        $pharmacyId = session('current_pharmacy_id');
        $stocks = $inventoryService->getSuggestedStock($pharmacyId);
        
        return response()->json([
            'success' => true,
            'stocks' => $stocks
        ]);
    }

    public function downloadSuggestedStock(Request $request, InventoryService $inventoryService)
    {
        $pharmacyId = session('current_pharmacy_id');
        
        $stocks = $inventoryService->getSuggestedStock($pharmacyId);
        $date = now()->format('Y-m-d H:i');

        $pdf = Pdf::loadView('reports.suggested_stock_pdf', compact('stocks', 'date'));

        return $pdf->download("suggested_stock_{$date}.pdf");
    }
}


