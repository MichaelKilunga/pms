<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $staff = Staff::with(['user','pharmacy'])->where('pharmacy_id', session('current_pharmacy_id'))->get();

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
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pharmacy_id' => 'required|exists:pharmacies,id',
        ]);

        Staff::create($request->only('user_id', 'pharmacy_id'));

        return redirect()->route('staff.index')->with('success', 'Staff added successfully.');
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
    public function edit(Staff $staff)
    {
        $this->authorizeAccess($staff);

        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();

        return view('staff.edit', compact('staff', 'pharmacies'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $this->authorizeAccess($staff);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pharmacy_id' => 'required|exists:pharmacies,id',
        ]);

        $staff->update($request->only('user_id', 'pharmacy_id'));

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff)
    {
        $this->authorizeAccess($staff);

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff removed successfully.');
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
