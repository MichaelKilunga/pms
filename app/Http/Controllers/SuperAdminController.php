<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccountDeletionRequest;
use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\Facades\Auth;

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
        $pharmacies = Pharmacy::with('owner')->latest()->get();
        $owners = User::where('role', 'owner')->get();
        if($owners->isEmpty()){
             $owners = User::role('Owner')->get();
        }
        return view('superAdmin.pharmacies.index', compact('pharmacies', 'owners'));
    }

    public function storePharmacy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:1000',
            'owner_id' => 'required|exists:users,id',
        ]);

        Pharmacy::create([
            'name' => $request->name,
            'location' => $request->location,
            'owner_id' => $request->owner_id,
            'status' => 'active',
            'package_id' => 1,
        ]);

        return redirect()->route('superadmin.pharmacies')->with('success', 'Pharmacy created successfully.');
    }

    public function showPharmacy($id)
    {
        $pharmacy = Pharmacy::with('owner')->findOrFail($id);
        return view('superAdmin.pharmacies.show', compact('pharmacy'));
    }

    public function editPharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $owners = User::role('Owner')->get();
        if($owners->isEmpty()){
             $owners = User::where('role', 'owner')->get();
        }
        return view('superAdmin.pharmacies.edit', compact('pharmacy', 'owners'));
    }

    public function updatePharmacy(Request $request, $id)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:1000',
            'owner_id' => 'required|exists:users,id',
        ]);

        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->update([
            'name' => $request->name,
            'location' => $request->location,
            'owner_id' => $request->owner_id,
        ]);

        return redirect()->route('superadmin.pharmacies')->with('success', 'Pharmacy updated successfully.');
    }

    public function deletePharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->delete();

        return redirect()->route('superadmin.pharmacies')->with('success', 'Pharmacy deleted successfully.');
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
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('superadmin.users')->with('success', 'User deleted successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for foreign key constraint violation (Code 23000 is common for SQL integrity constraint violations)
            if ($e->getCode() == "23000") {
                return redirect()->route('superadmin.users')->with('error', 'Cannot delete user because they are associated with other records (e.g., Pharmacies, Contracts).');
            }
            return redirect()->route('superadmin.users')->with('error', 'An error occurred while deleting the user.');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.users')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Show a user.
     */
    public function showUser($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('superAdmin.users.show', compact('user'));
    }

    /**
     * List all account deletion requests.
     */
    public function deletionRequests()
    {
        $requests = AccountDeletionRequest::with('user')->latest()->get();
        return view('superAdmin.deletion_requests.index', compact('requests'));
    }

    /**
     * Approve an account deletion request.
     */
    public function approveDeletionRequest(Request $request, $id)
    {
        $deletionRequest = AccountDeletionRequest::findOrFail($id);
        
        if ($deletionRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        $user = $deletionRequest->user;

        // Perform the deletion using Jetstream action
        $deleter = new DeleteUser();
        $deleter->delete($user);

        // Clear the request (or mark as approved, though user is gone)
        $deletionRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('superadmin.deletion_requests')->with('success', 'User account deleted successfully.');
    }

    /**
     * Reject an account deletion request.
     */
    public function rejectDeletionRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $deletionRequest = AccountDeletionRequest::findOrFail($id);

        if ($deletionRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        $deletionRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('superadmin.deletion_requests')->with('success', 'Deletion request rejected.');
    }
}
