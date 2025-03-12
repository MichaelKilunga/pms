<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    // public function messages(){
    //     $messages = Messages::all();
    //     return  view('agent.message', compact('messages'));
    // }

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
            ]);

            // dd($request->all());

            try {
                $pharmacy = Pharmacy::find($request->id);
                $pharmacy->name = $request->pharmacy_name;
                $pharmacy->location = $request->location;
                $pharmacy->status = $request->status;
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

                return view('agent.packages', compact('contracts', 'packages', 'current_contract_end_date', 'owners'));
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
        if ($request->action == 'index' || !$request->action) {

            // dd(Auth::user()->conversations);
            // dd(Conversation::first()->participants);
            // dd(Message::find(1)->usersWhoRead);

            // $messages = Message::where('agent_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            // return view('agent.messages', compact('messages'));
            return redirect()->back()->with('info', 'This module is still under Construction, thanks for your patience!');
        }
        if ($request->action == 'delete') {
            try {
                $message = Message::find($request->id);
                // dd($message);
                Message::destroy($message->id);
                return redirect()->back()->with('success', 'Message deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        if ($request->action == 'read') {
            try {
                $message = Message::find($request->id);
                // dd($message);
                $message->is_read = 1;
                $message->save();
                return redirect()->back()->with('success', 'Message marked as read successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        if ($request->action == 'unread') {
            try {
                // Fetch all conversations for the authenticated user
                $conversations = Auth::user()->conversations()
                    ->with(['messages' => function ($query) {
                        $query->where('status', 'unread')->with('sender'); // Only fetch unread messages
                    }])
                    ->get();

                // Return the conversations with their unread messages in a json response
                return response()->json($conversations);
            } catch (\Exception $e) {
                // Return the conversations with their unread messages in a json response
                return response()->json(['error'=>$e->getMessage()]);                
            }
        }

        if ($request->action == 'delete_all') {
            try {
                Message::where('agent_id', Auth::user()->id)->delete();
                return redirect()->back()->with('success', 'All messages deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    public function cases(Request $request)
    {
        // dd($request->all());
        return redirect()->back()->with('info', 'This module is still under Construction, thanks for your patience!');
    }

    public function contracts(Request $request)
    {
        // dd($request->all());
        return redirect()->back()->with('info', 'This module is still under Construction, thanks for your patience!');
    }
}
