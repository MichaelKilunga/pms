<?php

namespace Database\Factories;

use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PharmacyFactory extends Factory
{
    protected $model = Pharmacy::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'location' => $this->faker->address,
            'owner_id' => User::factory(), // Creating an owner user automatically
        ];
    }
}
