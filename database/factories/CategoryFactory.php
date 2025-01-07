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
            'pharmacy_id' => 1,
            'name' => 'Default',
            'description' => 'This is the default category',
            'pharmacy_id' => 1,
        ];
    }
}
