<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Contract;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\User;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{

    public function pharmacies(Request $request)
    {
        if (!$request->action) {
            $pharmacies = Pharmacy::where('agent_id', Auth::user()->id)->get();
            return  view('agent.pharmacies', compact('pharmacies'));
        }
        if ($request->action == 'index') {
            $pharmacies = Pharmacy::where('agent_id', Auth::user()->id)->get();
            return  view('agent.pharmacies', compact('pharmacies'));
        }

        if ($request->action == 'create') {
            $owner = '';
            $pharmacy = '';
            $request->validate([
                'pharmacy_name' => 'required|string|unique:pharmacies,name',
                'location' => 'nullable',
                'status' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|unique:users,phone|regex:/^[0-9]{10}$/',
            ]);

            try {
                try {
                    $owner = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone_number,
                        'password' => Hash::make("password"),
                        'role' => 'owner',
                    ]);
                    $owner->assignRole('Owner');
                } catch (\Exception $e) {
                    throw new \Exception("There is an error creating the owner!");
                }

                try {
                    $pharmacy = Pharmacy::create([
                        'name' => $request->pharmacy_name,
                        'location' => $request->location,
                        'status' => $request->status,
                        'agent_id' => Auth::user()->id,
                        'owner_id' => $owner->id,
                        'package_id' => 1,
                    ]);
                } catch (\Exception $e) {
                    // delete created user and pharmacy
                    $owner->delete();
                    $pharmacy->delete();
                    throw new \Exception("There is an error creating the pharmacy!");
                }

                return redirect('agent/pharmacies')->with('success', 'Pharmacy created successfully');
            } catch (\Exception $e) {
                return redirect('agent/pharmacies')->with('error', $e->getMessage());
            }
        }

        if ($request->action == 'update') {


            // validate new data
            $request->validate([
                'pharmacy_name' => 'required|string|unique:pharmacies,name,' . $request->id,
                'location' => 'nullable',
                'status' => 'required',
                'agent_extra_charge' => 'nullable|numeric|min:0',
            ]);

            // dd($request->all());

            try {
                $pharmacy = Pharmacy::find($request->id);
                $pharmacy->name = $request->pharmacy_name;
                $pharmacy->location = $request->location;
                $pharmacy->status = $request->status;
                $pharmacy->agent_extra_charge = $request->agent_extra_charge ?? 0;
                $pharmacy->save();
                return redirect()->back()->with('success', 'Pharmacy updated successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        if ($request->action == 'delete') {
            try {
                $pharmacy = Pharmacy::find($request->id);
                // dd($pharmacy);
                Pharmacy::destroy($pharmacy->id);
                return redirect()->back()->with('success', 'Pharmacy deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    public function packages(Request $request)
    {
        // dd($request->all());
        if ($request->action == 'index' || !$request->action) {

            $pharmacies = Pharmacy::where('agent_id', Auth::user()->id)->get();

            $owners = User::whereHas('pharmacies', function ($query) {
                $query->where('agent_id', Auth::id());
            })->distinct()->get();

            $packages = Package::all();

            if ($request->owner_id) {

                // check if owner's pharmacy are under this agent otherwise don't set session
                $owner_pharmacies = Pharmacy::where('owner_id', $request->owner_id)->where('agent_id', Auth::user()->id);
                if ($owner_pharmacies->count() < 1) {
                    // clear session
                    session()->forget('owner_id');
                    session()->forget('owner');
                    return redirect('agent/packages')->with('error', 'You are not authorized to view this owner\'s packages');
                } else {
                    // dd($owner_pharmacies->count());
                    session(['owner_id' => $request->owner_id]);
                    session(['owner' => User::where('id', $request->owner_id)->first()->name]);
                    return redirect('agent/packages');
                }
            }

            if (session('owner_id')) {

                $packages = Package::all();
                // $owners = User::all();
                $contracts = Contract::where('owner_id', session('owner_id'))->with('package')->orderBy('created_at', 'desc')->get();
                $current_contract = Contract::where('owner_id', session('owner_id'))->where('is_current_contract', 1)->first();

                //create a current_contract_end_date date from current_contract 
                if ($current_contract) {
                    $current_contract_end_date = date('Y-m-d', strtotime($current_contract->end_date));
                } else {
                    $current_contract_end_date = null;
                }

                $user = User::find(session('owner_id'));
                
                // --- PRICING DATA CALCULATION (Copied from ContractController for consistency) ---
                $pricingMode = $user->pricing_mode ?? (SystemSetting::where('key', 'pricing_mode')->value('value') ?? 'standard');
                
                $pricingData = [
                    'mode' => $pricingMode,
                    'amount' => 0,
                    'details' => [],
                    'agent_markup' => Pharmacy::where('owner_id', $user->id)->sum('agent_extra_charge')
                ];

                if ($pricingMode === 'dynamic') {
                    $totalItems = Pharmacy::where('owner_id', $user->id)->withCount('item')->get()->sum('item_count');
                    $rate = (float) (SystemSetting::where('key', 'system_use_rate')->value('value') ?? 100);
                    $divisor = (int) (SystemSetting::where('key', 'item_tier_divisor')->value('value') ?? 500);
                    $divisor = $divisor > 0 ? $divisor : 500;
                    $multiplier = max(1, floor($totalItems / $divisor));
                    $monthlyPrice = $multiplier * $rate * $totalItems;
                    
                    $pricingData['amount'] = $monthlyPrice;
                    $pricingData['details'] = [
                        'total_items' => $totalItems,
                        'rate' => $rate,
                        'multiplier' => $multiplier
                    ];
                } elseif ($pricingMode === 'profit_share') {
                    $percentage = (float) (SystemSetting::where('key', 'profit_share_percentage')->value('value') ?? 25);
                    $pharmacyIds = Pharmacy::where('owner_id', $user->id)->pluck('id');
                    $startDate = now()->subDays(30);
                    
                    $sales = Sales::whereIn('pharmacy_id', $pharmacyIds)
                        ->where('created_at', '>=', $startDate)
                        ->with('stock')
                        ->get();
                        
                    $monthlyProfit = 0;
                    foreach ($sales as $sale) {
                        if ($sale->stock) {
                            $cost = $sale->stock->buying_price * $sale->quantity;
                            $profit = $sale->total_price - $cost;
                            $monthlyProfit += $profit;
                        }
                    }
                    $monthlyProfit = max(0, $monthlyProfit);
                    $monthlyPrice = $monthlyProfit * ($percentage / 100);
                    
                    $pricingData['amount'] = $monthlyPrice;
                    $pricingData['details'] = [
                        'monthly_profit' => $monthlyProfit,
                        'percentage' => $percentage
                    ];
                }

                return view('agent.packages', compact('contracts', 'packages', 'current_contract_end_date', 'owners', 'pricingData'));
            } else {
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

                return view('agent.packages', compact('contracts', 'packages', 'current_contract_end_date', 'owners'));
            }
        }
    }

    public function messages(Request $request)
    {
        // DEPRECATED: Use MessageController instead.
        return redirect()->route('agent.messages');
    }

    public function cases(Request $request)
    {
        // dd($request->all());
        return redirect()->back()->with('info', 'This module is still under Construction, thanks for your patience!');
    }

    public function contracts(Request $request)
    {
        return redirect()->back()->with('info', 'This module is still under Construction, thanks for your patience!');
    }

    public function completeRegistration(Request $request)
    {
        $user = Auth::user();
        $me = $user->isAgent;

        if ($request->action == 'index') {
            if ($user->hasRole('Superadmin')) {
                $agents = User::role('Agent')->with('isAgent')->get();
                return view('agent.complete', compact('me', 'agents'));
            }
            return view('agent.complete', compact('me'));
        }

        // Step 1: Store agent data
        if ($request->action == 'store') {
            try {
                $request->validate([
                    'country' => 'required|string|max:100',
                    'NIN' => 'required|string|max:50|unique:agents,NIN' . ($me ? ',' . $me->id : ''),
                    'address' => 'required|string',
                    'document_attachment_1_name' => 'required|string',
                    'document_attachment_1' => 'required|file|mimes:pdf,jpg,png|max:4096',
                    'acceptTerms' => 'accepted',
                    'ruleOfLaw' => 'accepted',
                    'verifyInformations' => 'accepted',
                ]);

                // Handle document uploads
                $compulsoryDocumentPath = $request->file('document_attachment_1')->store('documents', 'public');

                $agentData = [
                    'user_id' => $user->id,
                    'country' => $request->country,
                    'NIN' => $request->NIN,
                    'address' => $request->address,
                    'document_attachment_1_name' => $request->document_attachment_1_name,
                    'document_attachment_1' => $compulsoryDocumentPath,
                    'registration_status' => 'step_2', // Move to Verification Pending
                    'request_date' => now(),
                    'status' => 'unverified'
                ];

                // Handle optional documents
                for ($i = 2; $i <= 3; $i++) {
                    $docNameField = "document_attachment_{$i}_name";
                    $docFileField = "document_attachment_{$i}";
                    if ($request->hasFile($docFileField)) {
                        $agentData[$docFileField] = $request->file($docFileField)->store('documents', 'public');
                        $agentData[$docNameField] = $request->$docNameField;
                    }
                }

                if ($me) {
                    $me->update($agentData);
                } else {
                    Agent::create($agentData);
                }

                return redirect()->back()->with('success', 'Application submitted successfully. Please wait for verification.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // Step 2 -> Step 3: Move to Agreement Upload (after verification)
        if ($request->action == 'next_step') {
            if ($me && $me->status == 'verified') {
                $me->update(['registration_status' => 'step_3']);
                return redirect()->back()->with('success', 'Verification confirmed. Please upload the signed agreement.');
            }
            return redirect()->back()->with('error', 'Action not allowed.');
        }

        // Step 3: Upload agreement form
        if ($request->action == 'upload_agreement_form') {
            try {
                $request->validate([
                    'signed_agreement_form' => 'required|file|mimes:pdf,jpg,png|max:4096',
                ]);

                if ($me) {
                    $me->update([
                        'signed_agreement_form' => $request->file('signed_agreement_form')->store('documents', 'public'),
                        'registration_status' => 'complete'
                    ]);
                    return redirect()->back()->with('success', 'Agreement uploaded successfully. Registration complete!');
                }
                return redirect()->back()->with('error', 'Agent record not found.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // SuperAdmin Actions: Verify/Reject
        if ($request->action == 'verify') {
            $agent = Agent::findOrFail($request->id);
            if ($request->set_status == "accepted") {
                $agent->update([
                    'status' => 'verified',
                    'verified_by' => $user->id,
                    'verified_date' => now()
                ]);
                return redirect()->back()->with('success', 'Agent application verified.');
            }
            if ($request->set_status == 'rejected') {
                $agent->update([
                    'status' => 'unverified',
                    'registration_status' => 'incomplete', // Will show rejection message
                    'verified_by' => $user->id,
                    'verified_date' => now()
                ]);
                return redirect()->back()->with('success', 'Agent application rejected.');
            }
        }

        // Generate agent code (SuperAdmin)
        if ($request->action == 'generateAgentCode') {
            $agent = Agent::findOrFail($request->id);
            $agent->update(['agent_code' => $this->generateAgentCode()]);
            return redirect()->back()->with('success', 'Agent code generated successfully.');
        }

        // Restart application (if rejected or canceled)
        if ($request->action == 'restart_steps') {
            if ($me) {
                $me->update(['registration_status' => 'step_1', 'status' => 'unverified']);
                return redirect()->back()->with('success', 'Registration restarted.');
            }
            return redirect()->back()->with('error', 'Agent record not found.');
        }
    }

    public function generateAgentCode()
    {
        $lastAgent = Agent::where('agent_code', '!=', null)->orderBy('id', 'desc')->first();
        if ($lastAgent) {
            $lastCode = $lastAgent->agent_code;
            $lastNumber = intval(substr($lastCode, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $newCode = 'PILLPOINTONE/AG/' . $newNumber;
            return $newCode;
        } else {
            $newCode = 'PILLPOINTONE/AG/0001';
            return $newCode;
        }
    }
}
