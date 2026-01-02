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
        $stocks = Stock::where('pharmacy_id', $pharmacyId)
            ->whereColumn('remain_Quantity', '<', 'low_stock_percentage')
            ->with(['item'])
            ->get();

        return $stocks->map(function ($stock) {
            // Logic for Suggested Quantity:
            // User requested: "suggested quantity 300".
            // Assumption: Since we don't have a "Max Stock" field, we'll suggest restocking up to 
            // a reasonable amount. A common heuristic is (LowStock * 3) or restoring to initial 'quantity'.
            // Let's use: Suggested = (quantity - remain_Quantity).
            // If the user meant a specific fixed number, we'd need a new DB column.
            // For now, let's assume they want to replenish the batch to its original level.
            // OR if 'quantity' represents the batch size, maybe they want to buy a new batch.
            // Let's go with: Suggested = quantity (buy a full new batch) or (quantity - remain).
            // Let's use (quantity - remain_Quantity) as 'Top Up', but typically for re-ordering you order packs.
            // Let's use the 'quantity' (original batch size) as a default suggested re-order amount?
            // User said: "Medicine name paracetamol, suggested quantity 300"
            // Let's use: Suggested = quantity (assuming 'quantity' is the standard pack size/restock level).
            
            // However, to be safe and logical:
            // If I have 5 remaining, and low stock is 10.
            // If I bought 100 initially.
            // Suggested = 100 - 5 = 95? Or just buy another 100?
            // "Restocking summary" implies what to buy.
            // I will compute 'suggested_quantity' as equal to the original 'quantity' of the batch (assuming re-ordering the same amount).
            
            $suggestedQty = $stock->quantity; 
            
            $stock->suggested_quantity = $suggestedQty;
            $stock->unit_buying_price = $stock->buying_price; 
            $stock->total_buying_price = $suggestedQty * $stock->buying_price;
            
            return $stock;
        });
    }
}
