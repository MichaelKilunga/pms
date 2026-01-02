<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'pricing_mode',
                'value' => 'dynamic',
                'description' => 'Current pricing mode: "dynamic" (item-based) or "standard" (package-based)',
            ],
            [
                'key' => 'system_use_rate',
                'value' => '100',
                'description' => 'Rate per item for system use calculation (Dynamic Pricing)',
            ],
            [
                'key' => 'item_tier_divisor',
                'value' => '500',
                'description' => 'Divisor for calculating the multiplier tier (Dynamic Pricing)',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
