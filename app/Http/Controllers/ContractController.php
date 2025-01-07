<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    // Super Admin Views
    public function indexSuperAdmin()
    {
        $contracts = Contract::with('owner', 'package')->get();
        return view('contracts.admin.index', compact('contracts'));
    }

    public function createSuperAdmin()
    {
        $packages = Package::all();
        $owners = User::all();
        return view('contracts.admin.create', compact('packages', 'owners'));
    }

    public function storeSuperAdmin(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,graced',
            'grace_end_date' => 'nullable|date|after_or_equal:end_date',
            'payment_status' => 'required|in:payed,unpayed,pending',
            'is_current_contract' => 'required|boolean',
        ]);

        Contract::create($validated);
        return redirect()->route('contracts.admin.index')->with('success', 'Contract created successfully.');
    }

    public function editSuperAdmin($id)
    {
        $contract = Contract::findOrFail($id);
        $packages = Package::all();
        $owners = User::all();
        return view('contracts.admin.edit', compact('contract', 'packages', 'owners'));
    }

    public function updateSuperAdmin(Request $request, $id)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,graced',
            'grace_end_date' => 'nullable|date|after_or_equal:end_date',
            'payment_status' => 'required|in:payed,unpayed,pending',
            'is_current_contract' => 'required|boolean',
        ]);

        $contract = Contract::findOrFail($id);
        $contract->update($validated);
        return redirect()->route('contracts.admin.index')->with('success', 'Contract updated successfully.');
    }

    public function showSuperAdmin($id)
    {
        $contract = Contract::with('owner', 'package')->findOrFail($id);
        return view('contracts.admin.show', compact('contract'));
    }

    // User (Owner) Views
    public function indexUser()
    {
        $contracts = Contract::where('owner_id', auth::user()->id)->with('package')->get();
        return view('contracts.user.index', compact('contracts'));
    }

    public function showUser($id)
    {
        $contract = Contract::where('owner_id', Auth::user()->id)->with('package')->findOrFail($id);
        return view('contracts.user.show', compact('contract'));
    }
}
