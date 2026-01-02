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

        // --- MIGRATE EXISTING USERS ---
        
        $users = User::all();
        
        foreach ($users as $user) {
            $currentRole = $user->role; // 'super', 'admin', 'staff', 'owner', 'agent'
            
            if ($currentRole === 'super') {
                if (!$user->hasRole('Superadmin')) {
                    $user->assignRole($superAdminRole);
                }
            } 
            elseif ($currentRole === 'owner') {
                if (!$user->hasRole('Owner')) {
                    $user->assignRole($ownerRole);
                }
            }
            elseif ($currentRole === 'agent') {
                if (!$user->hasRole('Agent')) {
                    $user->assignRole($agentRole);
                }
            }
            elseif ($currentRole === 'admin') {
                 if (!$user->hasRole('Manager')) {
                    $user->assignRole($managerRole);
                }
            }
            elseif ($currentRole === 'staff') {
                if (!$user->hasRole('Staff')) {
                    $user->assignRole($staffRole);
                }
            }
        }
    }
}
