<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectPharmacyController extends Controller
{
    public function show()
    {
        // Get all businesses for the authenticated user
        $pharmacies = Pharmacy::where('owner_id', Auth::user()->id)->get();
        
        if (!$pharmacies) {
            // return redirect()->route('business.create');
            dd("You don't have pharmacy");
        }
        // dd(compact('businesses'));
        return view('pharmacies.selection', compact('pharmacies'));
    }

    public function switch()
    {
        session(['current_pharmacy_id'=>'']);
        return redirect()->route('dashboard');
    }

    public function set(Request $request)
    {
        // Store the selected business in session
        session(['current_pharmacy_id' => $request->pharmacy_id]);

        return redirect()->route('dashboard');  // Redirect to the home page or dashboard
    }
}
