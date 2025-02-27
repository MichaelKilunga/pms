<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    // Super Admin Views
    public function indexSuperAdmin()
    {
        // Retrieves a collection of contracts
        $contracts = Contract::with('owner', 'package')->where('is_current_contract', 1)->get();

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

        // Get pharmacies that belong to the authenticated owner
        $pharmacies = Pharmacy::where('owner_id', Auth::user()->id)->get();
        // Detect the agent responsible for these pharmacies
        $agent = User::whereHas('agent', function ($query) use ($pharmacies) {
            $query->whereIn('id', $pharmacies->pluck('id'));
        })->first();
        // Store agent ID in the session if found
        if ($agent) {
            session(['agent' => $agent->id]);
            session(['agentData' => $agent]);
        } else {
            session(['agent' => '']);
            session(['agentData' => '']);
        }

        $packages = Package::all();
        // $owners = User::all();
        $contracts = Contract::where('owner_id', Auth::user()->id)->with('package')->orderBy('created_at', 'desc')->get();
        $current_contract = Contract::where('owner_id', Auth::user()->id)->where('is_current_contract', 1)->first();

        //create a current_contract_end_date date from current_contract 
        if ($current_contract) {
            $current_contract_end_date = date('Y-m-d', strtotime($current_contract->end_date));
        } else {
            $current_contract_end_date = null;
        }

        return view('contracts.users.index', compact('contracts', 'packages', 'current_contract_end_date'));
    }

    public function showUser($id)
    {
        $contract = Contract::where('owner_id', Auth::user()->id)->with('package')->findOrFail($id);
        return view('contracts.users.show', compact('contract'));
    }

    public function upgrade(Request $request)
    {
        $current_contract = Contract::where('owner_id', $request['owner_id'])->where('is_current_contract', 1)->get();
        //change all the current contract to not current
        foreach ($current_contract as $contract) {
            $contract->update(['is_current_contract' => 0]);
            // delete them if they are unpayed and inactive
            if ($contract->payment_status == 'unpayed' && $contract->status == 'inactive') {
                $contract->delete();
            }
        }

        // create a new contract
        $request['status'] = 'inactive';
        $request['payment_status'] = 'pending';
        $request['is_current_contract'] = 1;
        $request['start_date'] = now();
        $request['end_date'] = now()->addDays(30);
        $request['grace_end_date'] = null;

        try {

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

            $hasAnyContract = Contract::where('owner_id', Auth::user()->id)->count();

            if ($hasAnyContract > 0) {
                if ($validated['package_id'] ==  1) {
                    throw new Exception('This is unauthorized action!');
                }
            } else {
                if ($validated['package_id'] ==  1) {
                    $validated['status'] = 'active';
                    $validated['payment_status'] = 'payed';
                    $validated['end_date'] = now()->addDays(14);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

        $current_contract = Contract::where('owner_id', Auth::user()->id)->where('status', 'active')->orWhere('is_current_contract', 1)->first();

        try {
            if ($current_contract->package_id == $validated['package_id']) {
                throw new \Exception('You are already subscribed to this package.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

        // change the current contract to inactive
        $current_contract->update(['is_current_contract' => 0]);

        try {
            Contract::create($validated);

            $packages = Package::all();

            $contracts = Contract::where('owner_id', Auth::user()->id)->with('package')->get();

            $current_contract = Contract::where('is_current_contract', 1)->get('end_date')->first();

            $current_contract_end_date = date('Y-m-d', strtotime($current_contract->end_date));

            //redirect to route 'myContracts" with success message
            return redirect()->back()->with('success', 'Package upgraded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    //function for new subscription
    public function subscribe(Request $request)
    {
        $request['status'] = 'inactive';
        $request['payment_status'] = 'pending';
        $request['is_current_contract'] = 1;
        $request['start_date'] = now();
        $request['end_date'] = now()->addDays(30);
        $request['grace_end_date'] = null;


        try {
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


            $hasAnyContract = Contract::where('owner_id', Auth::user()->id)->count();

            if ($hasAnyContract > 0) {
                if ($validated['package_id'] ==  1) {
                    throw new Exception('This is unauthorized action!');
                }
            } else {
                if ($validated['package_id'] ==  1) {
                    $validated['status'] = 'active';
                    $validated['payment_status'] = 'payed';
                    $validated['end_date'] = now()->addDays(14);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

        Contract::create($validated);

        return redirect()->back()->with('success', 'Package subscribed successfully.');
    }

    public function renew(Request $request)
    {
        // dd($request->all());
        //locate the current contract and change it not current
        $current_contract = Contract::where('owner_id', $request['owner_id'])->where('is_current_contract', 1)->first();
        $current_contract->update(['is_current_contract' => 0]);

        $request['status'] = 'inactive';
        $request['payment_status'] = 'pending';
        $request['is_current_contract'] = 1;
        $request['start_date'] = now();
        $request['end_date'] = now()->addDays(30);
        $request['grace_end_date'] = null;

        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

        Contract::create($validated);

        return redirect()->back()->with('success', 'Package renewed successfully.');
    }

    // function "confirm" to confirm payment
    public function confirm($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->update(['payment_status' => 'payed', 'status' => 'active']);
        return redirect()->back()->with('success', 'Payment confirmed successfully.');
    }

    //function to activate a contract
    public function activate(Request $request)
    {
        // dd($request->all());
        //find the current contract
        $current_contract = Contract::where('owner_id', $request['owner_id'])->where('is_current_contract', 1)->get();
        //change all the current contract to not current
        foreach ($current_contract as $contract) {
            $contract->update(['is_current_contract' => 0]);
            // delete them if they are unpayed and inactive
            if ($contract->payment_status == 'unpayed' && $contract->status == 'inactive') {
                $contract->delete();
            }
        }

        //find the contract to activate
        $contract = Contract::findOrFail($request['contract_id']);
        $contract->update(['is_current_contract' => 1]);
        return redirect()->back()->with('success', 'Contract activated successfully.');
    }

    //function to add a grace period to a contract
    public function grace(Request $request, $id)
    {
        $request['contract_id'] = $id;
        //receive the number of days to add to the grace period, convert value from string to integer
        $day = (int)$request['days'];

        // dd($request->all());
        //find the contract to add grace period
        $contract = Contract::findOrFail($request['contract_id']);

        $contract->update(['status' => 'graced', 'grace_end_date' => now()->addDays($day)]);

        return redirect()->back()->with('success', 'Grace period added successfully.');
    }
}
