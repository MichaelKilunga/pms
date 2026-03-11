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

            // Use the low stock threshold from the latest batch
            $threshold = $latestBatch->low_stock_percentage;

            if ($totalRemain < $threshold) {
                // Determine suggested quantity: Use the latest batch's original quantity as a replenishment target
                $suggestedQty = $latestBatch->quantity;

                // Requirement: Only display medicines where the 'Suggested Qty' is greater than 0
                if ($suggestedQty <= 0) {
                    continue;
                }

                $latestBatch->suggested_quantity = $suggestedQty;
                $latestBatch->unit_buying_price = $latestBatch->buying_price;
                $latestBatch->total_buying_price = $suggestedQty * $latestBatch->buying_price;
                $latestBatch->aggregated_remain = $totalRemain;

                $suggested->push($latestBatch);
            }
        }

        return $suggested;
    }
}
