<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Items;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\Sales;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create all seeders here in order of dependency

        // 1. Permissions and Roles (Foundational)
        $this->call(RolesAndPermissionsSeeder::class);

        // Seed specific users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'info@skylinksolutions.co.tz',
                'email_verified_at' => now(),
                'role' => 'super',
                'phone' => '0742177328',
                'password' => Hash::make('password'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'remember_token' => Str::random(10),
                'profile_photo_path' => null,
                'current_team_id' => null,
            ],
            [
                'name' => 'Default Owner',
                'email' => 'owner@mail.com',
                'email_verified_at' => now(),
                'role' => 'owner',
                'phone' => '0742177328',
                'password' => Hash::make('password'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'remember_token' => Str::random(10),
                'profile_photo_path' => null,
                'current_team_id' => null,
            ],

        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        // Create package
        $package = Package::factory()->create();

        // Create a pharmacy
        $pharmacy = Pharmacy::factory()->create();

        // Create a category
        Category::factory()->create();

        // Create an item
        Items::factory()->create();

        // Create a stock
        Stock::factory()->create();

        // Create a staff
        Staff::factory()->create();

        // Create a sale
        Sales::factory()->create();

        // Create Contracts
        // Assign a contract to the Default Owner if possible, else create new ones
        $owner = User::where('email', 'owner@mail.com')->first();
        if ($owner) {
            \App\Models\Contract::factory()
                ->has(\App\Models\ContractPayment::factory()->count(1), 'payment')
                ->create([
                    'owner_id' => $owner->id,
                    'package_id' => $package->id,
                ]);
        }
    }
}
