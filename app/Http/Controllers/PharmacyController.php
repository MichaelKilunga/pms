<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PharmacyController extends Controller
{
    /**
     * Display a listing of pharmacies.
     */
    public function index()
    {
        // Fetch pharmacies owned by the authenticated user
        $pharmacies = Pharmacy::where('owner_id', Auth::id())->get();
        // dd($pharmacies);

        return view('pharmacies.index', compact('pharmacies'));
    }

    /**
     * Show the form for creating a new pharmacy.
     */
    public function create()
    {
        return view('pharmacies.create');
    }

    /**
     * Store a newly created pharmacy in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:1000',
        ]);

        $pharmacy = Pharmacy::create([
            'name' => $request->name,
            'location' => $request->location,
            'owner_id' => Auth::id(),
            'status' => 'active',
            'package_id' => 1,
        ]);

        session()->forget('guest-owner');
        session(['current_pharmacy_id' => $pharmacy->id]);

        //SEND EMAIL
        $notification = [
            'subject' => 'NEW PHARMACY CREATED '.$pharmacy->name,
            'body'=>'You\'ve created your pharmacy successfully',
            'action' => 'Manage Pharmacy',
            'path' => 'dashboard',
        ];
        $user = User::where('id', Auth::user()->id)->first();
        
        try{
            //check if user has access to receive email notification
                                    
        $user->notify( new GeneralNotification($notification));
        } catch(Exception $e){
            //
        }

        return redirect()->route('dashboard')->with('success', 'Pharmacy created successfully!');
    }

    /**
     * Display the specified pharmacy.
     */
    public function show(Pharmacy $pharmacy)
    {
        $this->authorizeAccess($pharmacy);

        return view('pharmacies.show', compact('pharmacy'));
    }

    /**
     * Show the form for editing the specified pharmacy.
     */
    public function edit(Pharmacy $pharmacy)
    {
        $this->authorizeAccess($pharmacy);

        return view('pharmacies.edit', compact('pharmacy'));
    }

    /**
     * Update the specified pharmacy in storage.
     */
    public function update(Request $request)
    {
        // $this->authorizeAccess($pharmacy);

        $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:1000',
        ]);
        // dd($request->id);
        $pharmacy = Pharmacy::where('id',$request->id);
        $pharmacy->update($request->only(['name', 'location']));


        return redirect()->route('pharmacies')->with('success', 'Pharmacy updated successfully.');
    }

    /**
     * Remove the specified pharmacy from storage.
     */

    public function destroy(Request $request)
    {
        Pharmacy::destroy($request->id);
        // dd($request->id);

        return redirect()->route('pharmacies')->with('success', 'Pharmacy deleted successfully.');
    }

    /**
     * Check if the authenticated user is authorized to access the pharmacy.
     */
    private function authorizeAccess(Pharmacy $pharmacy)
    {
        if ($pharmacy->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
    }
}
