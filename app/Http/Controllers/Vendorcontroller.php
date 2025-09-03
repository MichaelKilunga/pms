<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // List all vendors
    public function index()
    {
        $vendors = Vendor::orderBy('name')->get();
        return view('vendors.index', compact('vendors'));
    }

    // Show create form (modal will handle this in Blade)
    public function create()
    {
        return view('vendors.create'); // optional if using modal
    }

    // Store new vendor
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'tin' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        Vendor::create($request->all());

        return redirect()->route('vendors.index')->with('success', 'Vendor added successfully.');
    }

    // Show edit form
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    // Update vendor
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'tin' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $vendor->update($request->all());

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    // Delete vendor
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
