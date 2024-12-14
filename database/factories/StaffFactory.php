<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role'=>'staff']),
            'pharmacy_id' => Pharmacy::factory(),
        ];
    }
}
