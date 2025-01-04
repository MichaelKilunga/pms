<?php

namespace Database\Factories;

use App\Models\Items;
use App\Models\Category;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemsFactory extends Factory
{
    protected $model = Items::class;

    public function definition()
    {
        return [
            'name' => 'Default Medicine',
            'category_id' => 1,
            'pharmacy_id' => 1,
        ];
    }
}
