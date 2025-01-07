<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('packages.index', compact('packages'));
    }

    public function show($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.show', compact('package'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'status' => 'required|boolean',
            'number_of_pharmacies' => 'required|numeric',
            'number_of_pharmacists' => 'required|numeric',
            'number_of_medicines' => 'required|numeric',
            'in_app_notification' => 'required|boolean',
            'email_notification' => 'required|boolean',
            'sms_notifications' => 'required|boolean',
            'online_support' => 'required|boolean',
            'number_of_owner_accounts' => 'required|numeric',
            'number_of_admin_accounts' => 'required|numeric',
            'reports' => 'required|boolean',
            'stock_transfer' => 'required|boolean',
            'stock_management' => 'required|boolean',
            'staff_management' => 'required|boolean',
            'receipts' => 'required|boolean',
            'analytics' => 'required|boolean',
            'whatsapp_chats' => 'required|boolean',
        ]);

        Package::create($request->all());
        return redirect()->route('packages')->with('success', 'Package created successfully.');
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'status' => 'required|boolean',
            'number_of_pharmacies' => 'required|numeric',
            'number_of_pharmacists' => 'required|numeric',
            'number_of_medicines' => 'required|numeric',
            'in_app_notification' => 'required|boolean',
            'email_notification' => 'required|boolean',
            'sms_notifications' => 'required|boolean',
            'online_support' => 'required|boolean',
            'number_of_owner_accounts' => 'required|numeric',
            'number_of_admin_accounts' => 'required|numeric',
            'reports' => 'required|boolean',
            'stock_transfer' => 'required|boolean',
            'stock_management' => 'required|boolean',
            'staff_management' => 'required|boolean',
            'receipts' => 'required|boolean',
            'analytics' => 'required|boolean',
            'whatsapp_chats' => 'required|boolean',
        ]);

        $package->update($request->all());
        return redirect()->route('packages')->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();
        return redirect()->route('packages')->with('success', 'Package deleted successfully.');
    }
}
