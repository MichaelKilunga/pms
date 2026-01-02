<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Staff;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view dashboard',
            'manage sales',
            'manage stock',
            'manage staff',
            'manage settings',
            'view reports',
            'manage pharmacy',
            'manage subscriptions',
            'manage agents',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- GLOBAL ROLES ---
        
        // Superadmin
        $superAdminRole = Role::firstOrCreate(['name' => 'Superadmin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Agent
        $agentRole = Role::firstOrCreate(['name' => 'Agent']);
        $agentRole->givePermissionTo(['view dashboard', 'manage pharmacy']); 

        // Owner
        $ownerRole = Role::firstOrCreate(['name' => 'Owner']);
        $ownerRole->givePermissionTo(['view dashboard', 'manage sales', 'manage stock', 'manage staff', 'manage settings', 'view reports', 'manage pharmacy']);
        
        // Pharmacy Manager (Admin)
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerRole->givePermissionTo(['view dashboard', 'manage sales', 'manage stock', 'manage staff', 'view reports']);

        // Staff
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->givePermissionTo(['view dashboard', 'manage sales']); 

        // --- SMART RECOVERY USERS ---
        
        $users = User::all();
        
        foreach ($users as $user) {
            $assigned = false;

            // 1. Superadmin (ID 1 is always Superadmin)
            if ($user->id === 1 || $user->role === 'super') {
                $user->assignRole($superAdminRole);
                $this->command->info("Assigned Superadmin to: {$user->email}");
                $assigned = true;
            }

            // 2. Owner (Check if they own any pharmacy)
            if (Pharmacy::where('owner_id', $user->id)->exists() || $user->role === 'owner') {
                if (!$user->hasRole('Owner')) {
                     $user->assignRole($ownerRole);
                     $this->command->info("Assigned Owner to: {$user->email}");
                     $assigned = true;
                }
            }

            // 3. Agent (Check if they are listed as an agent for any pharmacy)
            // Assuming 'agent_id' exists on pharmacies table or if they are marked as agent
            if (Pharmacy::where('agent_id', $user->id)->exists() || $user->role === 'agent') {
                 if (!$user->hasRole('Agent')) {
                    $user->assignRole($agentRole);
                    $this->command->info("Assigned Agent to: {$user->email}");
                    $assigned = true;
                 }
            }

            // 4. Staff / Manager (Check staff table)
            $staffRecord = Staff::where('user_id', $user->id)->first();
            if ($staffRecord) {
                if ($staffRecord->role === 'admin' || $user->role === 'admin') {
                     if (!$user->hasRole('Manager')) {
                        $user->assignRole($managerRole);
                        $this->command->info("Assigned Manager to: {$user->email}");
                     }
                } else {
                     if (!$user->hasRole('Staff')) {
                        $user->assignRole($staffRole);
                        $this->command->info("Assigned Staff to: {$user->email}");
                     }
                }
                $assigned = true;
            }

            // 5. Fallback for 'staff' string in role column if no staff record found yet
            if (!$assigned && $user->role === 'staff') {
                $user->assignRole($staffRole);
                $this->command->info("Fallback: Assigned Staff to: {$user->email}");
            }
        }
    }
}
