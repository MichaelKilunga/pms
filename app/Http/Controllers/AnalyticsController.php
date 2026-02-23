<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Installment;
use App\Models\Staff;
use App\Models\Items;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard
     */
    public function index()
    {
        $pharmacyId = session('current_pharmacy_id');
        
        // Get all medicines for filtering
        $medicines = Items::where('pharmacy_id', $pharmacyId)->get();
        
        return view('reports.index', compact('medicines'));
    }

    /**
     * Get comprehensive analytics data
     */
    public function getAnalytics(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $pharmacyId = session('current_pharmacy_id');

        // Validate minimum 1 month range
        $daysDiff = $startDate->diffInDays($endDate);
        if ($daysDiff < 30) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a date range of at least 1 month (30 days) for accurate analytics.'
            ], 400);
        }

        try {
            $analytics = [
                'success' => true,
                'period' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'days' => $daysDiff,
                ],
                'overview' => $this->getOverview($startDate, $endDate, $pharmacyId),
                'stock_predictions' => $this->getStockPredictions($startDate, $endDate, $pharmacyId),
                'profit_predictions' => $this->getProfitPredictions($startDate, $endDate, $pharmacyId),
                'improvement_suggestions' => $this->getImprovementSuggestions($startDate, $endDate, $pharmacyId),
                'sales_optimization' => $this->getSalesOptimization($startDate, $endDate, $pharmacyId),
                'expense_analysis' => $this->getExpenseAnalysis($startDate, $endDate, $pharmacyId),
                'debt_insights' => $this->getDebtInsights($startDate, $endDate, $pharmacyId),
                'staff_performance' => $this->getStaffPerformance($startDate, $endDate, $pharmacyId),
            ];

            return response()->json($analytics);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get overview metrics
     */
    private function getOverview($startDate, $endDate, $pharmacyId)
    {
        // Total sales revenue
        $totalSales = Sales::where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum(DB::raw('quantity * total_price'));

        // Total stock value
        $totalStockValue = Stock::where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('buying_price * quantity'));

        // Gross profit
        $salesData = Sales::with('stock')
            ->where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        
        $grossProfit = $salesData->sum(function ($sale) {
            if (!$sale->stock) return 0;
            return ($sale->stock->selling_price - $sale->stock->buying_price) * $sale->quantity;
        });

        // Total expenses
        $totalExpenses = Expense::where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        // Total installments
        $totalInstallments = Installment::where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Net profit
        $netProfit = $grossProfit - $totalExpenses - $totalInstallments;

        // Total debts
        $totalDebts = Debt::where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('debtAmount');

        $totalDebtsPaid = Debt::where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->sum(function ($debt) {
                return $debt->totalPaid();
            });

        return [
            'total_sales' => $totalSales,
            'total_stock_value' => $totalStockValue,
            'gross_profit' => $grossProfit,
            'total_expenses' => $totalExpenses,
            'total_installments' => $totalInstallments,
            'net_profit' => $netProfit,
            'total_debts' => $totalDebts,
            'total_debts_paid' => $totalDebtsPaid,
            'profit_margin' => $totalSales > 0 ? ($grossProfit / $totalSales) * 100 : 0,
        ];
    }

    /**
     * Get stock predictions
     */
    private function getStockPredictions($startDate, $endDate, $pharmacyId)
    {
        $daysDiff = $startDate->diffInDays($endDate);

        // Get all sales grouped by item
        $salesByItem = Sales::where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('item_id')
            ->get();

        // Calculate average daily sales for each item
        $itemAnalytics = $salesByItem->map(function ($sale) use ($daysDiff, $pharmacyId) {
            $item = Items::find($sale->item_id);
            if (!$item) return null;
            $avgDailySales = $sale->total_quantity / $daysDiff;
            
            // Get current stock
            $currentStock = Stock::where('pharmacy_id', $pharmacyId)
                ->where('item_id', $sale->item_id)
                ->where('remain_Quantity', '>', 0)
                ->sum('remain_Quantity');

            // Calculate days until depletion
            $daysUntilDepletion = $avgDailySales > 0 ? $currentStock / $avgDailySales : 999;

            // Suggested reorder quantity (30 days supply + 20% buffer)
            $suggestedReorder = ceil($avgDailySales * 30 * 1.2);

            return [
                'item_id' => $sale->item_id,
                'item_name' => $item->name ?? 'Unknown',
                'total_sold' => $sale->total_quantity,
                'avg_daily_sales' => round($avgDailySales, 2),
                'current_stock' => $currentStock,
                'days_until_depletion' => round($daysUntilDepletion, 1),
                'suggested_reorder_qty' => $suggestedReorder,
                'velocity' => $avgDailySales, // For sorting
            ];
        })->filter(); // Remove null entries

        // Calculate percentiles for categorization
        $velocities = $itemAnalytics->pluck('velocity')->sort()->values();
        $count = $velocities->count();
        $percentile80 = $count > 0 ? $velocities[floor($count * 0.8)] : 0;
        $percentile20 = $count > 0 ? $velocities[floor($count * 0.2)] : 0;

        // Categorize items
        $fastMoving = $itemAnalytics->filter(function ($item) use ($percentile80) {
            return $item['velocity'] >= $percentile80;
        })->sortByDesc('velocity')->values()->take(20);

        $slowMoving = $itemAnalytics->filter(function ($item) use ($percentile20) {
            return $item['velocity'] <= $percentile20 && $item['velocity'] > 0;
        })->sortBy('velocity')->values()->take(20);

        // Items needing urgent restock (< 7 days until depletion)
        $urgentRestock = $itemAnalytics->filter(function ($item) {
            return $item['days_until_depletion'] < 7 && $item['days_until_depletion'] > 0;
        })->sortBy('days_until_depletion')->values();

        // Overstocked items (> 90 days supply)
        $overstocked = $itemAnalytics->filter(function ($item) {
            return $item['days_until_depletion'] > 90;
        })->sortByDesc('days_until_depletion')->values();

        return [
            'fast_moving' => $fastMoving,
            'slow_moving' => $slowMoving,
            'urgent_restock' => $urgentRestock,
            'overstocked' => $overstocked,
        ];
    }

    /**
     * Get profit predictions
     */
    private function getProfitPredictions($startDate, $endDate, $pharmacyId)
    {
        $daysDiff = $startDate->diffInDays($endDate);

        // Daily profit trend
        $dailyProfits = Sales::with('stock')
            ->where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('DATE(date) as sale_date')
            ->get()
            ->groupBy('sale_date')
            ->map(function ($sales, $date) {
                $profit = $sales->sum(function ($sale) {
                    if (!$sale->stock) return 0;
                    return ($sale->stock->selling_price - $sale->stock->buying_price) * $sale->quantity;
                });
                return [
                    'date' => $date,
                    'profit' => $profit
                ];
            })->values();

        // Calculate trend (simple linear regression)
        $avgProfit = $dailyProfits->avg('profit');
        $forecast30Days = $avgProfit * 30; // Simple forecast

        // High margin products
        $salesByItem = Sales::with('stock', 'item')
            ->where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy('item_id')
            ->map(function ($sales, $itemId) {
                $firstSale = $sales->first();
                if (!$firstSale || !$firstSale->stock) return null;
                
                $totalProfit = $sales->sum(function ($sale) {
                    if (!$sale->stock) return 0;
                    return ($sale->stock->selling_price - $sale->stock->buying_price) * $sale->quantity;
                });
                $totalRevenue = $sales->sum(function ($sale) {
                    if (!$sale->stock) return 0;
                    return $sale->stock->selling_price * $sale->quantity;
                });
                $margin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

                return [
                    'item_id' => $itemId,
                    'item_name' => $firstSale->item->name ?? 'Unknown',
                    'total_profit' => $totalProfit,
                    'total_revenue' => $totalRevenue,
                    'profit_margin' => round($margin, 2),
                ];
            })
            ->filter()
            ->sortByDesc('profit_margin')
            ->values()
            ->take(20);

        // ROI by medicine
        $roiByMedicine = Stock::with('item')
            ->where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($stock) use ($startDate, $endDate, $pharmacyId) {
                if (!$stock->item) return null;
                
                $investment = $stock->buying_price * $stock->quantity;
                
                $profit = Sales::where('pharmacy_id', $pharmacyId)
                    ->where('item_id', $stock->item_id)
                    ->where('stock_id', $stock->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get()
                    ->sum(function ($sale) use ($stock) {
                        return ($stock->selling_price - $stock->buying_price) * $sale->quantity;
                    });

                $roi = $investment > 0 ? ($profit / $investment) * 100 : 0;

                return [
                    'item_name' => $stock->item->name ?? 'Unknown',
                    'investment' => $investment,
                    'profit' => $profit,
                    'roi' => round($roi, 2),
                ];
            })
            ->filter()
            ->sortByDesc('roi')
            ->values()
            ->take(20);

        return [
            'daily_profits' => $dailyProfits,
            'avg_daily_profit' => round($avgProfit, 2),
            'forecast_30_days' => round($forecast30Days, 2),
            'high_margin_products' => $salesByItem,
            'roi_by_medicine' => $roiByMedicine,
        ];
    }

    /**
     * Get improvement suggestions
     */
    private function getImprovementSuggestions($startDate, $endDate, $pharmacyId)
    {
        $daysDiff = $startDate->diffInDays($endDate);

        // Get all items with their performance metrics
        $itemPerformance = Items::where('pharmacy_id', $pharmacyId)
            ->get()
            ->map(function ($item) use ($startDate, $endDate, $pharmacyId, $daysDiff) {
                // Sales data
                $sales = Sales::with('stock')
                    ->where('pharmacy_id', $pharmacyId)
                    ->where('item_id', $item->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                $totalSold = $sales->sum('quantity');
                $avgDailySales = $daysDiff > 0 ? $totalSold / $daysDiff : 0;
                
                $totalRevenue = $sales->sum(function ($sale) {
                    if (!$sale->stock) return 0;
                    return $sale->stock->selling_price * $sale->quantity;
                });
                
                $totalProfit = $sales->sum(function ($sale) {
                    if (!$sale->stock) return 0;
                    return ($sale->stock->selling_price - $sale->stock->buying_price) * $sale->quantity;
                });
                
                $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

                // Stock data
                $currentStock = Stock::where('pharmacy_id', $pharmacyId)
                    ->where('item_id', $item->id)
                    ->sum('remain_Quantity');

                // Expired stock
                $expiredStock = Stock::where('pharmacy_id', $pharmacyId)
                    ->where('item_id', $item->id)
                    ->where('expire_date', '<', now())
                    ->sum('remain_Quantity');

                $totalStocked = Stock::where('pharmacy_id', $pharmacyId)
                    ->where('item_id', $item->id)
                    ->sum('quantity');

                $expiryRate = $totalStocked > 0 ? ($expiredStock / $totalStocked) * 100 : 0;

                return [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'total_sold' => $totalSold,
                    'avg_daily_sales' => $avgDailySales,
                    'total_profit' => $totalProfit,
                    'profit_margin' => $profitMargin,
                    'current_stock' => $currentStock,
                    'expiry_rate' => $expiryRate,
                ];
            });

        // Calculate percentiles
        $velocities = $itemPerformance->pluck('avg_daily_sales')->filter()->sort()->values();
        $margins = $itemPerformance->pluck('profit_margin')->filter()->sort()->values();
        
        $count = $velocities->count();
        $velocityHigh = $count > 0 ? $velocities[floor($count * 0.8)] : 0;
        $velocityLow = $count > 0 ? $velocities[floor($count * 0.2)] : 0;

        // Items to MAXIMIZE (high sales, high margin, low expiry)
        $maximize = $itemPerformance->filter(function ($item) use ($velocityHigh) {
            return $item['avg_daily_sales'] >= $velocityHigh 
                && $item['profit_margin'] > 30 
                && $item['expiry_rate'] < 10;
        })->sortByDesc('total_profit')->values()->take(15);

        // Items to MINIMIZE (low sales, low margin)
        $minimize = $itemPerformance->filter(function ($item) use ($velocityLow) {
            return $item['avg_daily_sales'] <= $velocityLow 
                && $item['avg_daily_sales'] > 0
                && $item['profit_margin'] < 15;
        })->sortBy('total_profit')->values()->take(15);

        // Items to DROP (no sales or high expiry rate)
        $drop = $itemPerformance->filter(function ($item) {
            return $item['total_sold'] == 0 || $item['expiry_rate'] > 30;
        })->sortByDesc('expiry_rate')->values()->take(15);

        // Pricing optimization suggestions
        $pricingOptimization = $itemPerformance->filter(function ($item) use ($velocityLow) {
            return $item['avg_daily_sales'] <= $velocityLow 
                && $item['avg_daily_sales'] > 0
                && $item['current_stock'] > 0;
        })->map(function ($item) {
            $suggestedDiscount = $item['profit_margin'] > 20 ? 10 : 5;
            return [
                'item_name' => $item['item_name'],
                'current_margin' => round($item['profit_margin'], 2),
                'suggested_discount' => $suggestedDiscount,
                'reason' => 'Slow-moving item with available stock',
            ];
        })->values()->take(10);

        return [
            'maximize' => $maximize,
            'minimize' => $minimize,
            'drop' => $drop,
            'pricing_optimization' => $pricingOptimization,
        ];
    }

    /**
     * Get sales optimization insights
     */
    private function getSalesOptimization($startDate, $endDate, $pharmacyId)
    {
        // Sales by hour of day
        $salesByHour = Sales::where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('HOUR(date) as hour, COUNT(*) as transaction_count, SUM(quantity * total_price) as revenue')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Sales by day of week
        $salesByDayOfWeek = Sales::where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('DAYOFWEEK(date) as day_of_week, COUNT(*) as transaction_count, SUM(quantity * total_price) as revenue')
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get()
            ->map(function ($item) {
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                return [
                    'day' => $days[$item->day_of_week - 1] ?? 'Unknown',
                    'transaction_count' => $item->transaction_count,
                    'revenue' => $item->revenue,
                ];
            });

        // Peak hours
        $peakHours = $salesByHour->sortByDesc('revenue')->take(3)->pluck('hour');
        $offPeakHours = $salesByHour->sortBy('revenue')->take(3)->pluck('hour');

        return [
            'sales_by_hour' => $salesByHour,
            'sales_by_day_of_week' => $salesByDayOfWeek,
            'peak_hours' => $peakHours,
            'off_peak_hours' => $offPeakHours,
        ];
    }

    /**
     * Get expense analysis
     */
    private function getExpenseAnalysis($startDate, $endDate, $pharmacyId)
    {
        // Total expenses by category
        $expensesByCategory = Expense::with('category')
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get()
            ->groupBy('category_id')
            ->map(function ($expenses, $categoryId) {
                $firstExpense = $expenses->first();
                return [
                    'category_name' => $firstExpense->category->name ?? 'Uncategorized',
                    'total_amount' => $expenses->sum('amount'),
                    'transaction_count' => $expenses->count(),
                    'avg_amount' => round($expenses->avg('amount'), 2),
                ];
            })
            ->sortByDesc('total_amount')
            ->values();

        // Monthly expense trend
        $monthlyExpenses = Expense::where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Expense to revenue ratio
        $totalExpenses = Expense::where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $totalRevenue = Sales::where('pharmacy_id', $pharmacyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum(DB::raw('quantity * total_price'));

        $expenseRatio = $totalRevenue > 0 ? ($totalExpenses / $totalRevenue) * 100 : 0;

        // Unusual expenses (> 2 standard deviations from mean)
        $avgExpense = Expense::where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->avg('amount');

        $stdDev = DB::table('expenses')
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('STDDEV(amount) as std_dev')
            ->first()->std_dev ?? 0;

        $unusualExpenses = Expense::with('category', 'vendor')
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', 'approved')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->where('amount', '>', $avgExpense + (2 * $stdDev))
            ->get()
            ->map(function ($expense) {
                return [
                    'date' => $expense->expense_date->format('Y-m-d'),
                    'category' => $expense->category->name ?? 'N/A',
                    'vendor' => $expense->vendor->name ?? 'N/A',
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                ];
            });

        return [
            'by_category' => $expensesByCategory,
            'monthly_trend' => $monthlyExpenses,
            'expense_to_revenue_ratio' => round($expenseRatio, 2),
            'unusual_expenses' => $unusualExpenses,
            'optimization_tips' => $this->getExpenseOptimizationTips($expensesByCategory, $expenseRatio),
        ];
    }

    /**
     * Get expense optimization tips
     */
    private function getExpenseOptimizationTips($expensesByCategory, $expenseRatio)
    {
        $tips = [];

        if ($expenseRatio > 40) {
            $tips[] = [
                'type' => 'warning',
                'message' => 'Expenses are consuming ' . round($expenseRatio, 1) . '% of revenue. Consider cost reduction strategies.',
            ];
        }

        // Find highest expense category
        if ($expensesByCategory->count() > 0) {
            $highest = $expensesByCategory->first();
            $tips[] = [
                'type' => 'info',
                'message' => 'Highest expense category is "' . $highest['category_name'] . '" at TZS ' . number_format($highest['total_amount'], 0) . '. Review for optimization opportunities.',
            ];
        }

        return $tips;
    }

    /**
     * Get debt insights
     */
    private function getDebtInsights($startDate, $endDate, $pharmacyId)
    {
        $debts = Debt::with('stock.item', 'installments')
            ->where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalDebts = $debts->sum('debtAmount');
        $totalPaid = $debts->sum(function ($debt) {
            return $debt->totalPaid();
        });
        $totalRemaining = $totalDebts - $totalPaid;

        // Debt by status
        $debtsByStatus = $debts->groupBy('status')
            ->map(function ($items, $status) {
                return [
                    'status' => $status,
                    'count' => $items->count(),
                    'total_amount' => $items->sum('debtAmount'),
                ];
            })->values();

        // Payment velocity (average days to pay off debt)
        $paidDebts = $debts->filter(function ($debt) {
            return $debt->status === 'paid';
        });

        $avgPaymentDays = $paidDebts->count() > 0 
            ? $paidDebts->avg(function ($debt) {
                return $debt->created_at->diffInDays($debt->updated_at);
            })
            : 0;

        // Overdue debts (created > 30 days ago and not paid)
        $overdueDebts = $debts->filter(function ($debt) {
            return $debt->status !== 'paid' && $debt->created_at->diffInDays(now()) > 30;
        })->map(function ($debt) {
            return [
                'item_name' => $debt->stock->item->name ?? 'Unknown',
                'batch_number' => $debt->stock->batch_number ?? 'N/A',
                'debt_amount' => $debt->debtAmount,
                'paid_amount' => $debt->totalPaid(),
                'remaining' => $debt->debtAmount - $debt->totalPaid(),
                'days_overdue' => $debt->created_at->diffInDays(now()),
            ];
        })->values();

        return [
            'total_debts' => $totalDebts,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'payment_rate' => $totalDebts > 0 ? ($totalPaid / $totalDebts) * 100 : 0,
            'by_status' => $debtsByStatus,
            'avg_payment_days' => round($avgPaymentDays, 1),
            'overdue_debts' => $overdueDebts,
        ];
    }

    /**
     * Get staff performance metrics
     */
    private function getStaffPerformance($startDate, $endDate, $pharmacyId)
    {
        $staffMembers = Staff::with('user')
            ->where('pharmacy_id', $pharmacyId)
            ->get();

        $staffPerformance = $staffMembers->map(function ($staff) use ($startDate, $endDate, $pharmacyId) {
            // Sales by this staff member
            $sales = Sales::with('stock')
                ->where('pharmacy_id', $pharmacyId)
                ->where('staff_id', $staff->user_id) // Match with user_id as recorded in Sales table
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalTransactions = $sales->count();
            $totalRevenue = $sales->sum(function ($sale) {
                // Use recorded total_price (unit price) from sale record
                return ($sale->total_price ?? 0) * $sale->quantity;
            });
            $totalProfit = $sales->sum(function ($sale) {
                if (!$sale->stock) return 0;
                // Use recorded price minus buying price from stock
                return (($sale->total_price ?? $sale->stock->selling_price) - $sale->stock->buying_price) * $sale->quantity;
            });

            $avgTransactionValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

            // Stock added by this staff member
            $stocksAdded = Stock::where('pharmacy_id', $pharmacyId)
                ->where('staff_id', $staff->user_id) // Match with user_id as recorded in Stock table
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            return [
                'staff_id' => $staff->id,
                'staff_name' => $staff->user->name ?? 'Unknown',
                'total_transactions' => $totalTransactions,
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'avg_transaction_value' => round($avgTransactionValue, 2),
                'stocks_added' => $stocksAdded,
            ];
        })->sortByDesc('total_revenue')->values();

        // Top performers
        $topPerformers = $staffPerformance->take(5);

        return [
            'all_staff' => $staffPerformance,
            'top_performers' => $topPerformers,
        ];
    }
}
