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
        $rates = $this->getUpgradeRates();
        $res = $rates['resources'];
        
        $branchesCount = $user->pharmacies()->count(); 
        
        $staffCount = 1; // Start with owner
        foreach($user->pharmacies as $pharmacy){
            $staffCount += $pharmacy->staff()->count();
        }
        
        $itemsCount = 0;
        foreach($user->pharmacies as $pharmacy){
             $itemsCount += $pharmacy->item()->count(); 
        }

        $baseAmount = ($branchesCount * $res['pharmacy']) + 
                      ($staffCount * $res['staff']) + 
                      ($itemsCount * $res['item']);
        
        $totalAmount = $baseAmount * $months;

        return [
            'amount' => $totalAmount,
            'strategy' => 'dynamic',
            'details' => [
                'branches_count' => $branchesCount,
                'branches_rate' => $res['pharmacy'],
                'staff_count' => $staffCount,
                'staff_rate' => $res['staff'],
                'items_count' => $itemsCount,
                'items_rate' => $res['item'],
                'months' => $months
            ],
            'agent_markup' => 0
        ];
    }

    protected function calculateProfitSharePrice(User $user, int $months): array
    {
         $settings = SystemSetting::whereNull('pharmacy_id')->pluck('value', 'key')->toArray();
         $percentage = (float)($settings['profit_share_percentage'] ?? 0); 

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

    public function getUpgradeRates(): array
    {
        $settings = SystemSetting::whereNull('pharmacy_id')->pluck('value', 'key')->toArray();

        // 1. Resource Rates (Dynamic)
        $resourceRates = [
            'pharmacy' => (float)($settings['dynamic_rate_per_branch'] ?? 20000),
            'staff' => (float)($settings['dynamic_rate_per_staff'] ?? 5000),
            'item' => (float)($settings['dynamic_rate_per_item'] ?? 100),
        ];

        // 2. Feature Rates (Add-ons)
        $featureRates = [
            'has_whatsapp' => (float)($settings['upgrade_rate_whatsapp'] ?? 5000),
            'has_sms' => (float)($settings['upgrade_rate_sms'] ?? 10000),
            'has_reports' => (float)($settings['upgrade_rate_reports'] ?? 15000),
            'stock_management' => (float)($settings['upgrade_rate_stock_management'] ?? 10000),
            'stock_transfer' => (float)($settings['upgrade_rate_stock_transfer'] ?? 10000),
            'staff_management' => (float)($settings['upgrade_rate_staff_management'] ?? 5000),
            'receipts' => (float)($settings['upgrade_rate_receipts'] ?? 5000),
            'analytics' => (float)($settings['upgrade_rate_analytics'] ?? 15000),
        ];

        return [
            'resources' => $resourceRates,
            'features' => $featureRates
        ];
    }

    public function calculateUpgradePrice(array $requestedUpgrades, int $months): array
    {
        $rates = $this->getUpgradeRates();
        $res = $rates['resources'];
        $feat = $rates['features'];
        
        $amount = 0;
        $details = [];

        // Incremental Resources
        if (($requestedUpgrades['extra_pharmacies'] ?? 0) > 0) {
            $count = $requestedUpgrades['extra_pharmacies'];
            $amount += $count * $res['pharmacy'] * $months;
            $details['extra_pharmacies'] = $count;
        }
        
        if (($requestedUpgrades['extra_pharmacists'] ?? 0) > 0) {
            $count = $requestedUpgrades['extra_pharmacists'];
            $amount += $count * $res['staff'] * $months;
            $details['extra_pharmacists'] = $count;
        }

        if (($requestedUpgrades['extra_medicines'] ?? 0) > 0) {
            $count = $requestedUpgrades['extra_medicines'];
            $amount += $count * $res['item'] * $months;
            $details['extra_medicines'] = $count;
        }

        // Fixed Price Add-ons (Monthly rates)
        foreach ($feat as $key => $rate) {
            if ($requestedUpgrades[$key] ?? false) {
                $amount += $rate * $months;
                $details[$key] = true;
            }
        }

        return [
            'amount' => $amount,
            'details' => $details
        ];
    }

    protected function getLast30DaysProfit(User $user): float
    {
        // Placeholder for profit calculation logic
        // This likely involves querying Sales - Expenses for the user's businesses
        return 0.0;
    }
}
