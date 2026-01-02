<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\User;

use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    /**
     * Display the dashboard for the super admin.
     */
    public function dashboard()
    {
        return view('superAdmin.dashboard');
    }

    /**
     * Manage Users.
     */
    public function manageUsers()
    {
        $users = User::with('roles')->get(); // Fetch all users with roles
        return view('superAdmin.users.index', compact('users'));
    }

    public function managePharmacies(){
        $pharmacies = Pharmacy::with('owner')->get();
        // dd($pharmacies);
        return view('superAdmin.pharmacies.index', compact('pharmacies'));
    }
    /**
     * Edit a user.
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('superAdmin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update a user.
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        
        // Update basic info
        $user->update($request->except('role'));

        // Sync Spatie Role
        $user->syncRoles([$request->role]);

        // Update Legacy Role Column (for backward compatibility)
        // Map Spatie role names to legacy strings if necessary, or just use the name
        // Legacy seems to use lowercase: 'super', 'admin', 'staff', 'owner', 'agent'
        // Spatie uses Capitalized: 'Superadmin', 'Manager', 'Staff', 'Owner', 'Agent'
        
        $roleMap = [
            'Superadmin' => 'super',
            'Manager' => 'admin',
            'Staff' => 'staff',
            'Owner' => 'owner',
            'Agent' => 'agent'
        ];

        $legacyRole = $roleMap[$request->role] ?? strtolower($request->role);
        $user->role = $legacyRole;
        $user->save();

        return redirect()->route('superadmin.users')->with('success', 'User updated successfully');
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('superadmin.users')->with('success', 'User deleted successfully');
    }
}
