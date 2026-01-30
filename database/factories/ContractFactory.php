<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\User;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(), // Or a random existing user if seeded
            'package_id' => Package::factory(),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['active', 'inactive', 'graced']),
            'grace_end_date' => $this->faker->dateTimeBetween('+1 year', '+1 year 1 week'),
            'payment_status' => $this->faker->randomElement(['payed', 'unpayed', 'pending']),
            'is_current_contract' => true,
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'agent_markup' => $this->faker->randomFloat(2, 0, 50),
            'pricing_strategy' => 'standard',
            'details' => ['notes' => $this->faker->sentence],
        ];
    }
}
