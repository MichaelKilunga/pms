<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\Sales;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class InventoryService
{
    /**
     * Get list of items that need restocking.
     * Logic: remain_Quantity < low_stock_percentage
     *
     * @param int $pharmacyId
     * @param int $calculationDays
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSuggestedStock(int $pharmacyId, int $calculationDays = 30)
    {
        // Get all stocks for the pharmacy to group them by item name
        $stocks = Stock::where('pharmacy_id', $pharmacyId)
            ->with(['item'])
            ->get();

        // Get sales volume per item for the calculation period (e.g. last 30 days)
        $recentSales = Sales::where('pharmacy_id', $pharmacyId)
            ->where('date', '>=', Carbon::now()->subDays($calculationDays))
            ->selectRaw('item_id, SUM(quantity) as total_sold')
            ->groupBy('item_id')
            ->pluck('total_sold', 'item_id');

        // Group by medicine name to avoid duplicates if same medicine exists as different items
        $groupedStocks = $stocks->groupBy(function ($stock) {
            return trim(strtolower($stock->item->name ?? 'Unknown Item'));
        });

        $suggested = collect();

        foreach ($groupedStocks as $nameKey => $batches) {
            // Aggregate remaining quantity across all batches of the same item name
            $totalRemain = $batches->sum('remain_Quantity');

            // Find the latest batch to use its threshold and pricing info
            $latestBatch = $batches->sortByDesc('created_at')->first();

            if (!$latestBatch) continue;
            
            // Check if there are any sales for this item in the period
            $totalSalesForName = 0;
            foreach ($batches as $batch) {
                if (isset($recentSales[$batch->item_id])) {
                    $totalSalesForName += $recentSales[$batch->item_id];
                }
            }

            // Requirement: Do not display any medicine that has had 0 sales during the calculation period
            if ($totalSalesForName <= 0) {
                continue;
            }

            // 1. Calculate Average Daily Sales (Sales Velocity V)
            $velocity = $totalSalesForName / $calculationDays;

            // 2. Dynamic Reorder Point (7-Day Supply)
            $reorderPoint = $velocity * 7;

            // 3. Manual Threshold Value
            $manualThreshold = ($latestBatch->quantity * $latestBatch->low_stock_percentage) / 100;

            // 4. Trigger Point: Use the higher of the two
            $triggerPoint = max($manualThreshold, $reorderPoint);

            // 5. Check if we need to restock
            if ($totalRemain < $triggerPoint) {
                // Determine suggested quantity: to reach a 21-day stock buffer
                $suggestedQty = ceil(($velocity * 21) - $totalRemain);

                // Round up and ensure it's at least 1 if we're below trigger
                if ($suggestedQty <= 0) {
                    $suggestedQty = 0; // Should ideally be positive if totalRemain < triggerPoint and velocity > 0
                }

                // Days of stock left (Runway)
                $daysLeft = $velocity > 0 ? ($totalRemain / $velocity) : 0;

                // Urgency Sorting helper
                $latestBatch->avg_daily_sales = $velocity;
                $latestBatch->days_left = $daysLeft;
                $latestBatch->suggested_quantity = $suggestedQty;
                $latestBatch->unit_buying_price = $latestBatch->buying_price;
                $latestBatch->total_buying_price = $suggestedQty * $latestBatch->buying_price;
                $latestBatch->aggregated_remain = $totalRemain;
                $latestBatch->reorder_point = $reorderPoint;

                $suggested->push($latestBatch);
            }
        }

        // Sort by Days of Stock Left (ascending) - most urgent first
        return $suggested->sortBy('days_left')->values();
    }
}
