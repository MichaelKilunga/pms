<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Unique;

class StaffController extends Controller
{
    /**
     * Display a listing of staff.
     */
    public function index()
    {
        // // Get the current business ID from the session
        // if (Auth::user()->role == "owner") {
        //     $currentPharmacyId = session('current_pharmacy_id');
        //     if (!$currentPharmacyId) {
        //         // Redirect to a page where the user can select a business
        //         return redirect()->route('pharmacies.selection');
        //     }
        // }

        // Get staff for the pharmacy owned by the authenticated user
        $pharmacies = Pharmacy::where('owner_id', auth::id())->pluck('id');
        $staff = Staff::with(['user', 'pharmacy'])->where('pharmacy_id', session('current_pharmacy_id'))->get();

        // dd($staff);

        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();

        return view('staff.create', compact('pharmacies'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    // public function store(Request $request)
    // {
    // $request->validate([
    //     'user_id' => 'required|exists:users,id',
    //     'pharmacy_id' => 'required|exists:pharmacies,id',
    // ]);

    public function store(Request $request)
    {
        $request['pharmacy_id'] = session('current_pharmacy_id');

        // Validate the incoming request
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255|unique:users,phone',
                'email' => 'required|string|email|max:255|unique:users,email',
                'role' => 'required|in:admin,staff', // Restrict to specific roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }

        // Check if a user with the same phone or email already exists
        $existingUser = User::where('phone', $request->phone)
            ->orWhere('email', $request->email)
            ->first();

        if ($existingUser) {
            return redirect()->route('staff')->with('error', 'User with this phone or email already exists.');
        }

        try {
            // Add the hashed password to the request data
            $validatedData['password'] = Hash::make('password');

            // Create the user
            $user = User::create($validatedData);

            // Assign Spatie role
            if ($request->role == 'admin') {
                $user->assignRole('Manager');
                 // Pharmacy::where('pharmacy_id',$request->pharmacy_id)->update(['admin_id' => $user->id]);
            } else {
                $user->assignRole('Staff');
            }

            // Create the staff record
            $staff = Staff::create([
                'user_id' => $user->id,
                'pharmacy_id' => $request->pharmacy_id,
            ]);

            return redirect()->route('staff')->with('success', 'Staff added successfully!');
        } catch (\Exception $e) {
            // Handle any errors during user or staff creation
            return redirect()->route('staff')->with('error', 'Failed to add staff. Please try again.');
        }
    }


    /**
     * Display the specified staff member.
     */
    public function show(Staff $staff)
    {
        $this->authorizeAccess($staff);

        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    // public function edit(Staff $staff)
    // {
    //     $this->authorizeAccess($staff);

    //     $pharmacies = Pharmacy::where('owner_id', auth::id())->get();

    //     return view('staff.edit', compact('staff', 'pharmacies'));
    // }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request)
    {

        // $this->authorizeAccess($staff);
        //    dd($request);
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'role' => 'required|in:admin,staff',
        ]);

        $user = User::findOrFail($request->id);
        
        $user->update($request->only(['name', 'email', 'phone']));
        
        // Update Legacy Role
        $user->role = $request->role;
        $user->save();

        // Sync Spatie Role
        if ($request->role === 'admin') {
             $user->syncRoles(['Manager']);
        } else {
             $user->syncRoles(['Staff']);
        }

        return redirect()->route('staff')->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified staff member from storage.
     */

    public function destroy(Request $request)
    {
        User::destroy($request->id);

        return redirect()->route('staff')->with('success', 'Staff deleted successfully!');
    }
    /**
     * Authorize access to the staff member.
     */
    private function authorizeAccess(Staff $staff)
    {
        if ($staff->pharmacy->owner_id !== auth::id()) {
            abort(403, 'Unauthorized access');
        }
    }
}
