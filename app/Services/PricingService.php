<?php

namespace App\Services;

use App\Models\User;
use App\Models\Package;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class PricingService
{
    /**
     * Calculate the price for a subscription.
     *
     * @param User $user
     * @param int $months
     * @param int|null $packageId
     * @return array
     */
    public function calculatePrice(User $user, int $months, ?int $packageId = null): array
    {
        // 1. Determine Strategy
        // If packageId is provided, we might be in Standard mode (unless overridden).
        // For now, check if the system or user has a specific preferred strategy or if package implies one.
        // Assuming the caller specifies intent via package selection or defaults.
        
        // However, the prompt says "Global Configuration (packages/index.blade.php) ... Select the active Pricing Mode".
        // So we should look up the global active pricing mode from SystemSettings.
        
        $pricingModeSetting = SystemSetting::where('key', 'pricing_mode')->first();
        $pricingMode = $pricingModeSetting ? $pricingModeSetting->value : 'standard';

        // Override if package is provided and we want to force standard for specific flow?
        // But prompt says "Select the active Pricing Mode" globally. Let's respect global setting unless context suggests otherwise.
        
        if ($pricingMode === 'standard') {
            return $this->calculateStandardPrice($months, $packageId);
        } elseif ($pricingMode === 'dynamic') {
            return $this->calculateDynamicPrice($user, $months);
        } elseif ($pricingMode === 'profit_share') {
            return $this->calculateProfitSharePrice($user, $months);
        }

        // Fallback
        return $this->calculateStandardPrice($months, $packageId);
    }

    protected function calculateStandardPrice(int $months, ?int $packageId): array
    {
        $package = Package::find($packageId);
        
        if (!$package) {
            // Handle case where package is required but not found
             return [
                'amount' => 0,
                'strategy' => 'standard',
                'details' => ['error' => 'Package not found'],
                'agent_markup' => 0
            ];
        }

        $basePrice = $package->price * $months;

        return [
            'amount' => $basePrice,
            'strategy' => 'standard',
            'details' => [
                'package_price' => $package->price,
                'months' => $months,
                'formula' => "{$package->price} * {$months}"
            ],
            'agent_markup' => 0 // Add logic for agent markup if needed for standard
        ];
    }

    protected function calculateDynamicPrice(User $user, int $months): array
    {
        // Inputs: Number of Branches, Staff, Items, Services, Modules.
        // Configuration: SystemSetting
        
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        $ratePerBranch = $settings['dynamic_rate_per_branch'] ?? 20000;
        $ratePerStaff = $settings['dynamic_rate_per_staff'] ?? 5000;
        $ratePerItem = $settings['dynamic_rate_per_item'] ?? 100;
        
        // Fetch User counts
        // Assuming User model has relationships to count these.
        // NOTE: User might be owner, so we look at their owned entities.
        
        $branchesCount = $user->pharmacies()->count(); 
        // Logic for staff: User -> Pharmacy -> Staff? or User -> Staff (if owner)?
        // Assuming owner has many pharmacies and pharmacies have many staff.
        // Also adding +1 for the owner themselves as they are a staff member of the business.
        $staffCount = 1; // Start with owner
        foreach($user->pharmacies as $pharmacy){
            $staffCount += $pharmacy->staff()->count();
        }
        
        // Items count (medicines/stocks)
        $itemsCount = 0;
         foreach($user->pharmacies as $pharmacy){
             // Count items in each pharmacy
             // Verified: Pharmacy model has 'item()' relationship.
             $itemsCount += $pharmacy->item()->count(); 
        }

        $baseAmount = ($branchesCount * $ratePerBranch) + 
                      ($staffCount * $ratePerStaff) + 
                      ($itemsCount * $ratePerItem);
        
        $totalAmount = $baseAmount * $months;

        return [
            'amount' => $totalAmount,
            'strategy' => 'dynamic',
            'details' => [
                'branches_count' => $branchesCount,
                'branches_rate' => $ratePerBranch,
                'staff_count' => $staffCount,
                'staff_rate' => $ratePerStaff,
                'items_count' => $itemsCount,
                'items_rate' => $ratePerItem,
                'months' => $months
            ],
            'agent_markup' => 0
        ];
    }

    protected function calculateProfitSharePrice(User $user, int $months): array
    {
         $settings = SystemSetting::pluck('value', 'key')->toArray();
         $percentage = $settings['profit_share_percentage'] ?? 0; // e.g. 25 for 25%

         // Get Last 30 Days Profit
         // This requires complex logic depending on how profit is stored/calculated.
         // For now, I'll assume there's a way to get this or placeholder it.
         $last30DaysProfit = $this->getLast30DaysProfit($user);

         $monthlyFee = $last30DaysProfit * ($percentage / 100);
         $totalAmount = $monthlyFee * $months;

         return [
             'amount' => $totalAmount,
             'strategy' => 'profit_share',
             'details' => [
                 'last_30_days_profit' => $last30DaysProfit,
                 'percentage' => $percentage,
                 'months' => $months
             ],
             'agent_markup' => 0
         ];
    }

    protected function getLast30DaysProfit(User $user): float
    {
        // Placeholder for profit calculation logic
        // This likely involves querying Sales - Expenses for the user's businesses
        return 0.0;
    }
}
