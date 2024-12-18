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
            'staff_id' => Staff::factory(),
            'pharmacy_id' => Pharmacy::factory(),
            'item_id' => Items::factory(),
            'quantity' => $this->faker->numberBetween(1000, 5000),
            'buying_price' => $this->faker->randomFloat(0, 5000, 100000),
            'selling_price' => $this->faker->randomFloat(0, 10000, 200000),
            'in_date' => $this->faker->date(),
            'expire_date' => $this->faker->dateTimeBetween('now', '+2 years'),
        ];
    }
}
