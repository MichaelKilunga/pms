<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'owner') {
            $pharmacies = Pharmacy::where('owner_id', Auth::user()->id)->get();
            return view('dashboard', compact('pharmacies'));
        } else {
            $staff = Staff::where('user_id',Auth::user()->id)->first();
            // dd($staff->pharmacy_id);
            $pharmacy = Pharmacy::where('id',$staff->pharmacy_id)->first();
            // dd($pharmacy);
            return view('dashboard', compact('pharmacy'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
