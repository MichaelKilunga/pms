<?php

namespace Database\Factories;

use App\Models\ContractPayment;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractPayment>
 */
class ContractPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 50, 500);
        return [
            'contract_id' => Contract::factory(),
            'amount_to_pay' => $amount,
            'discount' => 0,
            'discount_percentage' => 0,
            'paid_amount' => $amount, // Fully paid by default for simplicity
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
