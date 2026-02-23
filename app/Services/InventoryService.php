<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Collection;

class InventoryService
{
    /**
     * Get list of items that need restocking.
     * Logic: remain_Quantity < low_stock_percentage
     *
     * @param int $pharmacyId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSuggestedStock(int $pharmacyId)
    {
        // Get all stocks for the pharmacy to group them by item name
        $stocks = Stock::where('pharmacy_id', $pharmacyId)
            ->with(['item'])
            ->get();

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

            // Ensure we use the proper name (not the lowercase key)
            $itemName = $latestBatch->item->name ?? 'Unknown Item';

            // Use the low stock threshold from the latest batch
            $threshold = $latestBatch->low_stock_percentage;

            if ($totalRemain < $threshold) {
                // Determine suggested quantity: Use the latest batch's original quantity as a replenishment target
                $suggestedQty = $latestBatch->quantity;

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
