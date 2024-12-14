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
            'staff_id' => Staff::factory(),
            'pharmacy_id' => Pharmacy::factory(),
            'item_id' => Items::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'total_price' => $this->faker->randomFloat(2, 5, 100),
            'date' => $this->faker->dateTimeThisYear(),
        ];
    }
}
