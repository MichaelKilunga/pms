<?php

namespace Database\Factories;

use App\Models\Sales;
use App\Models\Items;
use App\Models\Pharmacy;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesFactory extends Factory
{
    protected $model = Sales::class;

    public function definition()
    {
        return [
            'staff_id' => 1,
            'pharmacy_id' => 1,
            'item_id' => 1,
            'stock_id' => 1,
            'quantity' => 400,
            'total_price' => 2000000,
            'date' => now(),
        ];
    }
}
