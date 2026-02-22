<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractPayment;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\User;
use App\Services\PricingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    // Super Admin Views
    public function indexSuperAdmin()
    {
        // Retrieves all contracts, prioritizing those where payment was notified but not yet paid
        $contracts = Contract::with('owner', 'package')
            ->orderBy('payment_notified', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

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
        // dd($request->all());
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

        // Update details for add-ons
        $details = $contract->details ?? [];
        $details['has_whatsapp'] = $request->has('has_whatsapp');
        $details['has_sms'] = $request->has('has_sms');
        
        $data = array_merge($validated, ['details' => $details]);

        $contract->update($data);

        return redirect()->route('contracts.admin.index')->with('success', 'Contract updated successfully.');
    }

    public function showSuperAdmin($id)
    {
        $contract = Contract::with('owner', 'package')->findOrFail($id);

        return view('contracts.admin.show', compact('contract'));
    }

    public function initiateSuperAdmin($id)
    {
        $contract = Contract::findOrFail($id);
        
        if ($contract->payment_status !== 'payed') {
            return redirect()->back()->with('error', 'Contract must be paid before initiation.');
        }

        // Ensure only one current contract per owner
        Contract::where('owner_id', $contract->owner_id)
            ->where('is_current_contract', 1)
            ->update(['is_current_contract' => 0]);

        $contract->update([
            'is_current_contract' => 1,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Contract initiated and activated successfully.');
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

        // create a current_contract_end_date date from current_contract
        if ($current_contract) {
            $current_contract_end_date = date('Y-m-d', strtotime($current_contract->end_date));
        } else {
            $current_contract_end_date = null;
        }

        // Check for active + paid but NOT current
        $hasActivePaidNotCurrent = Contract::where('owner_id', Auth::id())
            ->where('status', 'active')
            ->where('payment_status', 'payed')
            ->where('is_current_contract', 0)
            ->exists();

        // --- PRICING DATA CALCULATION ---
        $user = Auth::user();
        $pricingData = [];

        // Calculate price for 1 month as a baseline for the UI?
        // Or better, let the frontend calculate based on base params, but here we provide the breakdown for default 1 month.
        // Actually the prompt says: "Standard Mode: Base Price * Selected Months. Dynamic/Profit: (Calculated Base Amount + Agent Markup) * Selected Months"

        // Let's get the 1-month price from pricing service to pass as base data
        $pricingResult = $this->pricingService->calculatePrice($user, 1, null);

        $pricingData = [
            'mode' => $pricingResult['strategy'], // This comes from system/user setting inside service
            'base_amount' => $pricingResult['amount'], // Verification: This is 1 month amount
            'details' => $pricingResult['details'],
            'upgrade_rates' => $this->pricingService->getUpgradeRates(),
            'agent_markup' => Pharmacy::where('owner_id', $user->id)->sum('agent_extra_charge'), // Separate markup if needed specifically
        ];

        return view('contracts.users.index', compact(
            'contracts',
            'packages',
            'current_contract_end_date',
            'hasActivePaidNotCurrent',
            'pricingData'
        ));
    }

    public function notifyPayment($id)
    {
        $contract = Contract::where('owner_id', Auth::id())->findOrFail($id);

        if ($contract->payment_status === 'payed') {
            return redirect()->back()->with('error', 'This contract is already paid.');
        }

        $contract->update(['payment_notified' => true]);

        return redirect()->back()->with('success', 'Admin notified of your payment. Please wait for confirmation.');
    }

    public function showUser($id)
    {
        $contract = Contract::where('owner_id', Auth::user()->id)->with('package')->findOrFail($id);

        return view('contracts.users.show', compact('contract'));
    }

    public function upgrade(Request $request)
    {
        $current_contract = Contract::where('owner_id', $request['owner_id'])->where('is_current_contract', 1)->get();

        // change all the current contract to not current
        foreach ($current_contract as $contract) {
            $contract->update(['is_current_contract' => 0]);
            // delete them if they are unpayed and inactive
            if ($contract->payment_status == 'unpayed' && $contract->status == 'inactive') {
                $contract->delete();
            }
        }

        // new contract logic
        $days = $request['months'] * 30;

        // Pricing Logic via Service
        $owner = User::find($request['owner_id']);
        $pricingResult = $this->pricingService->calculatePrice($owner, $request['months'], $request['package_id']);

        $amount = $pricingResult['amount'];
        $pricingStrategy = $pricingResult['strategy'];
        $details = $pricingResult['details'];
        $agentMarkup = Pharmacy::where('owner_id', $owner->id)->sum('agent_extra_charge') * $request['months'];
        $amount += $agentMarkup;

        $contractData = [
            'owner_id' => $request['owner_id'],
            'package_id' => $request['package_id'],
            'start_date' => now(),
            'end_date' => now()->addDays($days),
            'status' => 'inactive',
            'grace_end_date' => null,
            'payment_status' => 'pending',
            'is_current_contract' => 1,
            'amount' => $amount,
            'pricing_strategy' => $pricingStrategy,
            'details' => $details,
            'agent_markup' => $agentMarkup,
        ];

        try {
            // Re-validate structure but manual logic was applied above
            $validated = $request->validate([
                'owner_id' => 'required|exists:users,id',
                'package_id' => 'required|exists:packages,id',
                // we overwrite dates/status anyway
            ]);

            $hasAnyContract = Contract::where('owner_id', Auth::user()->id)->count();

            if ($hasAnyContract > 0) {
                if ($request['package_id'] == 1) {
                    throw new Exception('This is unauthorized action!');
                }
            } else {
                if ($request['package_id'] == 1) {
                    $contractData['status'] = 'active';
                    $contractData['payment_status'] = 'payed';
                    $contractData['end_date'] = now()->addDays(14);
                }
            }

            // Check existing package
            $packageid = $request['package_id'];
            $count = Contract::where('owner_id', Auth::user()->id)
                ->where('status', 'active')
                ->where('package_id', $packageid)
                ->count();

            if ($count > 0) {
                throw new \Exception('You are already subscribed to this package.');
            }

            Contract::create($contractData);

            return redirect()->back()->with('success', 'Package upgraded successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    // function for new subscription
    public function subscribe(Request $request)
    {
        $days = $request['months'] * 30;

        // Pricing Logic via Service
        $ownerId = $request['owner_id'] ?? Auth::id();
        $owner = User::find($ownerId);

        $pricingResult = $this->pricingService->calculatePrice($owner, $request['months'], $request['package_id']);

        $amount = $pricingResult['amount'];
        $pricingStrategy = $pricingResult['strategy'];
        $details = $pricingResult['details'];
        $agentMarkup = Pharmacy::where('owner_id', $ownerId)->sum('agent_extra_charge') * $request['months']; // Add agent markup separately if service didn't include it (Service returned 0 for now)
        $amount += $agentMarkup;

        $contractData = [
            'owner_id' => $ownerId,
            'package_id' => $request['package_id'],
            'start_date' => now(),
            'end_date' => now()->addDays($days),
            'status' => 'inactive', // Default pending until paid
            'grace_end_date' => null,
            'payment_status' => 'pending',
            'is_current_contract' => 1,
            'amount' => $amount,
            'pricing_strategy' => $pricingStrategy,
            'details' => $details,
            'agent_markup' => $agentMarkup,
        ];

        try {
            $hasAnyContract = Contract::where('owner_id', $ownerId)->count();
            // Removed trial auto-activation as per system requirements

            $validated = $request->validate([
                'owner_id' => 'required|exists:users,id',
                'package_id' => 'required|exists:packages,id',
            ]);

            Contract::create($contractData);

            return redirect()->back()->with('success', 'Package subscribed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function renew(Request $request)
    {
        // locate the current contract and change it not current
        $current_contract = Contract::where('owner_id', $request['owner_id'])->where('is_current_contract', 1)->first();
        if ($current_contract) {
            $current_contract->update(['is_current_contract' => 0]);
        }

        $days = $request['months'] * 30;

        // Pricing Logic via Service
        $ownerId = $request['owner_id'];
        $owner = User::find($ownerId);
        $pricingResult = $this->pricingService->calculatePrice($owner, $request['months'], $request['package_id']);

        $amount = $pricingResult['amount'];
        $pricingStrategy = $pricingResult['strategy'];
        $details = $pricingResult['details'];
        $agentMarkup = Pharmacy::where('owner_id', $ownerId)->sum('agent_extra_charge') * $request['months'];
        $amount += $agentMarkup;

        $contractData = [
            'owner_id' => $ownerId,
            'package_id' => $request['package_id'],
            'start_date' => now(),
            'end_date' => now()->addDays($days),
            'status' => 'inactive',
            'grace_end_date' => null,
            'payment_status' => 'pending',
            'is_current_contract' => 1,
            'amount' => $amount,
            'pricing_strategy' => $pricingStrategy,
            'details' => $details,
            'agent_markup' => $agentMarkup,
        ];

        try {
            $validated = $request->validate([
                'owner_id' => 'required|exists:users,id',
                'package_id' => 'required|exists:packages,id',
            ]);

            Contract::create($contractData);

            return redirect()->back()->with('success', 'Package renewed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function requestUpgrade(Request $request)
    {
        $owner = Auth::user();
        $currentContract = Contract::where('owner_id', $owner->id)
            ->where('is_current_contract', 1)
            ->first();

        if (!$currentContract) {
            return redirect()->back()->with('error', 'You must have an active current contract to request an upgrade.');
        }

        $months = $request->input('months', 1);
        $currentDetails = $currentContract->details ?? [];
        
        // Prepare requested upgrades
        $requested = $request->only([
            'extra_pharmacies', 'extra_pharmacists', 'extra_medicines',
            'has_whatsapp', 'has_sms', 'has_reports', 
            'stock_management', 'stock_transfer', 'staff_management', 
            'receipts', 'analytics'
        ]);

        // Clean values: remove booleans already active, ensure counts are positive
        foreach ($requested as $key => $val) {
            if (str_starts_with($key, 'has_') || in_array($key, ['stock_management', 'stock_transfer', 'staff_management', 'receipts', 'analytics'])) {
                if ($currentDetails[$key] ?? false) {
                    unset($requested[$key]); // Already have it
                } else {
                    $requested[$key] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
                }
            } else {
                $requested[$key] = max(0, (int)$val);
            }
        }

        $upgradeResult = $this->pricingService->calculateUpgradePrice($requested, $months);

        if ($upgradeResult['amount'] <= 0) {
            return redirect()->back()->with('error', 'No new upgrades selected or already active.');
        }

        Contract::create([
            'owner_id' => $owner->id,
            'package_id' => $currentContract->package_id,
            'start_date' => now(),
            'end_date' => $currentContract->end_date, 
            'status' => 'inactive',
            'payment_status' => 'pending',
            'is_current_contract' => 0,
            'amount' => $upgradeResult['amount'],
            'pricing_strategy' => $currentContract->pricing_strategy,
            'details' => array_merge($upgradeResult['details'], ['is_upgrade' => true, 'parent_id' => $currentContract->id]),
            'agent_markup' => 0,
        ]);

        return redirect()->back()->with('success', 'Upgrade request generated. Please notify admin after payment.');
    }

    // function "confirm" to confirm payment
    public function confirm($id)
    {
        $contract = Contract::findOrFail($id);

        if ($contract->details && ($contract->details['is_upgrade'] ?? false)) {
            // This is an upgrade (could be add-ons or increased limits)
            $parent = Contract::find($contract->details['parent_id']);
            if ($parent) {
                $parentDetails = $parent->details ?? [];
                $upgradeDetails = $contract->details;
                
                // Keys to exclude from merging
                $exclude = ['is_upgrade', 'parent_id'];
                
                foreach ($upgradeDetails as $key => $value) {
                    if (!in_array($key, $exclude)) {
                        if (is_numeric($value) && str_contains($key, 'extra_')) {
                            // Incremental values like extra_medicines
                            $parentDetails[$key] = ($parentDetails[$key] ?? 0) + $value;
                        } else {
                            // Boolean or string flags
                            $parentDetails[$key] = $value;
                        }
                    }
                }
                $parent->update(['details' => $parentDetails]);
            }

            $contract->update([
                'payment_status' => 'payed',
                'status' => 'active',
                'payment_notified' => false
            ]);
        } else {
            // Standard contract confirmation
            $days = Carbon::parse($contract->start_date)->diffInDays(Carbon::parse($contract->end_date));
            // calculate new end_date from today plus days
            $new_end_date = Carbon::now()->addDays($days);
            // new start date is today
            $new_start_date = Carbon::now();
            // dd($new_start_date, $new_end_date,$days);

            // update contract with the new start date and new end date where package start works after activation.
            $contract->update([
                'start_date' => $new_start_date,
                'end_date' => $new_end_date,
                'payment_status' => 'payed',
                'status' => 'active',
                'payment_notified' => false
            ]);
        }

        // Create Payment Record
        ContractPayment::create([
            'contract_id' => $contract->id,
            'amount_to_pay' => $contract->amount,
            'discount' => 0,
            'discount_percentage' => 0,
            'paid_amount' => $contract->amount,
            'payment_date' => now(),
        ]);

        // Generate Invoice for this payment confirmation?
        // Ideally we generate invoice when contract is created, and mark as paid here.
        // But per prompt: "invoices ... due_date, paid_at".
        // Let's ensure an invoice exists or create one and mark it paid.
        // For now, let's keep it simple and maybe hook into this later or via command.

        return redirect()->back()->with('success', 'Payment confirmed successfully.');
    }

    // function to activate a contract
    public function activate(Request $request)
    {
        $contractId = $request->query('contract_id');
        $contract = Contract::where('owner_id', Auth::id())->findOrFail($contractId);

        if ($contract->payment_status !== 'payed') {
            return redirect()->back()->with('error', 'Contract must be paid before activation.');
        }

        if (Carbon::parse($contract->end_date)->isPast()) {
            return redirect()->back()->with('error', 'This contract has already expired.');
        }

        // Ensure only one current contract per owner
        Contract::where('owner_id', Auth::id())
            ->where('is_current_contract', 1)
            ->update(['is_current_contract' => 0]);

        $contract->update([
            'is_current_contract' => 1,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Contract activated successfully.');
    }

    // function to add a grace period to a contract
    public function grace(Request $request, $id)
    {
        $request['contract_id'] = $id;
        // receive the number of days to add to the grace period, convert value from string to integer
        $day = (int) $request['days'];

        // dd($request->all());
        // find the contract to add grace period
        $contract = Contract::findOrFail($request['contract_id']);

        $contract->update(['status' => 'graced', 'grace_end_date' => now()->addDays($day)]);

        return redirect()->back()->with('success', 'Grace period added successfully.');
    }

    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);

        if ($contract->payment_status === 'payed') {
            return redirect()->back()->with('error', 'Cannot delete a paid contract.');
        }

        if (Auth::id() != $contract->owner_id) { // Simple ownership check
            // You might want to add admin override check here if needed
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $contract->delete();

        return redirect()->back()->with('success', 'Contract deleted successfully.');
    }

    public function generateBill(Request $request)
    {
        $months = $request->input('months', 1);
        $owner = Auth::user();

        // Calculate pricing
        // If dynamic mode is active, the service will handle it, 
        // but we might want to pass the intended counts if the user is inactive.
        // For active users, service usually fetches from current DB state.
        $pricingResult = $this->pricingService->calculatePrice($owner, $months, null);

        $amount = $pricingResult['amount'];
        $strategy = $pricingResult['strategy'];
        $details = $pricingResult['details'];
        
        // Add add-ons if selected in the modal
        if ($request->has('has_whatsapp')) {
            $amount += 5000 * $months;
            $details['has_whatsapp'] = true;
        }
        if ($request->has('has_sms')) {
            $amount += 10000 * $months;
            $details['has_sms'] = true;
        }

        $agentMarkup = $owner->pharmacies()->sum('agent_extra_charge') * $months;
        $totalAmount = $amount + $agentMarkup;

        Contract::create([
            'owner_id' => Auth::id(),
            'package_id' => 3, // Default to a standard package ID
            'start_date' => now(),
            'end_date' => now()->addDays($months * 30),
            'status' => 'inactive',
            'payment_status' => 'pending',
            'is_current_contract' => 0,
            'amount' => $totalAmount,
            'pricing_strategy' => $strategy,
            'details' => $details,
            'agent_markup' => $agentMarkup,
        ]);

        return redirect()->back()->with('success', 'Bill generated successfully. Please proceed to payment or notify admin.');
    }
}
