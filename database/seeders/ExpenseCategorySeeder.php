<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Pharmacy Supplies', 'description' => 'Expenses for medical and pharmacy supplies', 'is_active' => true],
            ['name' => 'Salaries & Wages', 'description' => 'Staff salaries and wages', 'is_active' => true],
            ['name' => 'Utilities', 'description' => 'Electricity, water, internet, and other utilities', 'is_active' => true],
            ['name' => 'Rent', 'description' => 'Branch or office rent', 'is_active' => true],
            ['name' => 'Maintenance & Repairs', 'description' => 'Maintenance of equipment or premises', 'is_active' => true],
            ['name' => 'Marketing & Advertising', 'description' => 'Promotions, advertisements, and campaigns', 'is_active' => true],
            ['name' => 'Transportation', 'description' => 'Vehicle or delivery expenses', 'is_active' => true],
            ['name' => 'Miscellaneous', 'description' => 'Other general expenses', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::create($cat);
        }
    }
}
