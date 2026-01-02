<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class ProfitSettingSeeder extends Seeder
{
    public function run()
    {
        SystemSetting::updateOrCreate(
            ['key' => 'profit_share_percentage'],
            [
                'value' => '25',
                'description' => 'Percentage of item profit to charge (Profit Based Pricing)',
            ]
        );
    }
}
