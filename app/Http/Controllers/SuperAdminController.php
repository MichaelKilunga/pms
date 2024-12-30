<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\User;

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
        $users = User::all(); // Fetch all users
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
        return view('superAdmin.users.edit', compact('user'));
    }

    /**
     * Update a user.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

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
