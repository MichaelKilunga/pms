<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'pharmacy_id' => Pharmacy::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
