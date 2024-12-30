<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use App\Models\Package;
use App\Models\Category;
use App\Models\Items;
use App\Models\Stock;
use App\Models\Staff;
use App\Models\Sales;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Pest\Laravel\get;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Package::factory()->create();

        // // Create some users
        // User::factory()->count(5)->create(['role' => 'owner']);

        // $owners = User::where('role', 'owner')->get();
        // foreach ($owners as $owner) {
        //     // Create some pharmacies and related data
        //     Pharmacy::factory()->count(3)->create(['owner_id' => $owner->id]);

        //     $pharmacies = Pharmacy::where('owner_id', $owner->id)->get();
        //     foreach ($pharmacies as $pharmacy) {
        //         // For each pharmacy, create categories, items, staff, and stock
        //         $categories = Category::factory()->count(3)->create(['pharmacy_id' => $pharmacy->id]);
        //         foreach ($categories as $category) {
        //             Items::factory()->count(5)->create([
        //                 'pharmacy_id' => $pharmacy->id,
        //                 'category_id' => $category->id,
        //             ]);
        //         }


        //         // Create stock for each item in the pharmacy
        //         $items = Items::where('pharmacy_id', $pharmacy->id)->get();
        //         foreach ($items as $item) {
        //             Stock::factory()->count(1)->create([
        //                 'pharmacy_id' => $pharmacy->id,
        //                 'item_id' => $item->id,
        //                 'staff_id' => $owner,
        //             ]);

        //             $staff = Staff::factory()->count(1)->create(['pharmacy_id' => $pharmacy->id]);
        //             // Create some sales    data for each pharmacy
        //             foreach ($staff as $staff) {
        //                 Sales::factory()->count(10)->create([
        //                     'pharmacy_id' => $pharmacy->id,
        //                     'item_id' => $item->id,
        //                     'staff_id' => $staff->id,
        //                 ]);
        //             }
        //         }
        //     }
        // }
    }
}
