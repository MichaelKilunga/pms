<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\Items;
use App\Models\Pharmacy;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition()
    {
        return [
            'staff_id' => 1,
            'pharmacy_id' => 1,
            'item_id' => 1,
            'quantity' =>  5000,
            'batch_number' =>  123456789,
            'supplier' =>  'Default Supplier',
            'remain_Quantity' =>  5000,
            'low_stock_percentage' => 100,
            'buying_price' => 500,
            'selling_price' => 5000,
            'in_date' => now(),
            'expire_date' => $this->faker->dateTimeBetween('now', '+2 years'),
        ];
    }
}
