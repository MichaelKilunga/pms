<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $vendors = [
            [
                'name' => 'Global Medics Ltd',
                'phone' => '0767001122',
                'email' => 'sales@globalmedics.com',
                'tin' => '123456789',
                'address' => 'Kariakoo Market',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'is_active' => true,
            ],
            [
                'name' => 'MediSupply Ltd',
                'phone' => '0755112233',
                'email' => 'contact@medisupply.com',
                'tin' => '987654321',
                'address' => 'Kivukoni',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'is_active' => true,
            ],
            [
                'name' => 'HealthPro Distributors',
                'phone' => '0744223344',
                'email' => 'info@healthpro.com',
                'tin' => '456789123',
                'address' => 'Oysterbay',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'is_active' => true,
            ],
            [
                'name' => 'PharmaLink Tanzania',
                'phone' => '0788334455',
                'email' => 'support@pharmalinktz.com',
                'tin' => '321654987',
                'address' => 'Masaki',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'is_active' => true,
            ],
            [
                'name' => 'Medicare Supplies',
                'phone' => '0722445566',
                'email' => 'admin@medicaresupplies.com',
                'tin' => '159753486',
                'address' => 'Upanga',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'is_active' => true,
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
