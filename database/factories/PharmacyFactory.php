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
            'name' => 'Default Pharmacy',
            'location' => 'Morogoro',
            'status' => 'Active',
            'package_id' => 1, // Default package
            'owner_id' => 2, // Default user
        ];
    }
}
