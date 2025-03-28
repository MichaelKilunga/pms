<?php

namespace App\Http\Controllers;

use App\Models\Agent;
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
            // return  all conversations, their messages, and their users
            try {
                // Fetch all conversations for the authenticated user
                $conversations = Conversation::whereHas('creator', function ($query) {
                    $query->where('role', Auth::user()->role);
                })->with(['messages' => function ($query) {
                    $query->with(['sender', 'usersWhoRead']); // Only fetch unread messages
                }, 'participants'])
                    ->get()
                    // Add all other conversations he is a participant in
                    ->merge(Conversation::whereHas('participants', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    })->with(['messages' => function ($query) {
                        $query->with(['sender', 'usersWhoRead']); // Only fetch unread messages
                    }, 'participants'])
                        ->get());

                $users = User::where('role', Auth::user()->role)->get();
                // add the super admin, id = 1
                $users->push(User::find(1));

                return view('agent.messages', compact('conversations', 'users'));
            } catch (\Exception $e) {
                // Return the conversations with their unread messages in a json response
                return response()->json(['error' => $e->getMessage()]);
            }
        }
        if ($request->action == 'delete') {

            try {
                $message = Message::find($request->id);

                if (!$message) {
                    return response()->json(['success' => false, 'message' => 'Message not found']);
                }

                Message::destroy($message->id);

                return response()->json([
                    'success' => 'true',
                    'message' => 'Message deleted successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
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
                return response()->json(['error' => $e->getMessage()]);
            }
        }

        if ($request->action == 'createConversation') {
            try {
                // validate
                $request->validate([
                    'recipients' => 'required|array',
                    'recipients.*' => 'required|integer',

                    'title' => 'required|string',
                    'description' => 'required|string',
                ]);
                // create conversation
                $conversation = Conversation::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => 'open',
                    'creator_id' => Auth::user()->id,
                ]);

                // add each participants to the conversation
                foreach ($request->recipients as $user_id) {
                    $conversation->participants()->attach($user_id);
                    // attach the authenticated user to the conversation
                    $conversation->participants()->attach(Auth::user()->id);
                }

                return response()->json(['success' => 'Conversation created successfully']);
            } catch (\Exception $e) {
                // Return the conversations with their unread messages in a json response
                return response()->json(['error' => $e->getMessage()]);
            }
        }

        // get conversation messages
        if ($request->action == 'getMessages') {
            try {
                // Fetch conversation messages with sender and users who read
                $messages = Message::with(['sender', 'usersWhoRead', 'parentMessage' => function ($query) {
                    $query->with('sender');
                }])
                    ->where('conversation_id', $request->conversation_id)
                    ->get();

                // Return the messages in JSON format
                return response()->json([
                    'success' => true,
                    'messages' => $messages
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // send message
        if ($request->action == 'sendMessage') {
            try {
                // validate
                $request->validate([
                    'conversation_id' => 'required|integer',
                    'content' => 'required|string',
                ]);

                // create message
                $message = Message::create([
                    'conversation_id' => $request->conversation_id,
                    'sender_id' => Auth::user()->id,
                    'content' => $request->content,
                    'status' => 'unread',
                ]);

                // return response
                return response()->json([
                    'success' => true,
                    'message' => $message ? 'Message sent successfully' : 'Message not sent'
                ]);
            } catch (\Exception $e) {
                // Return the conversations with their unread messages in a json response
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        // send reply
        if ($request->action == 'sendReply') {
            try {
                // validate
                $request->validate([
                    'conversationId' => 'required|integer',
                    'parentMessageId' => 'required|integer',
                    'message' => 'required|string',
                ]);

                // return response()->json([
                //     'success' => true,
                //     'message' => $request->all()
                // ]);

                // create message
                $message = Message::create([
                    'conversation_id' => $request->conversationId,
                    'sender_id' => Auth::user()->id,
                    'content' => $request->message,
                    'status' => 'unread',
                    'parent_message_id' => $request->parentMessageId,
                ]);

                // return response
                return response()->json([
                    'success' => true,
                    'message' => $message ? 'Message replied successfully' : 'Message not replied'
                ]);
            } catch (\Exception $e) {
                // Return the conversations with their unread messages in a json response
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        // return recepients for a conversations
        if ($request->action == 'getRecipients') {
            try {
                // fetch users with same role as the authenticated user, and  super admin
                $users = User::where('role', Auth::user()->role)->get();
                // add the super admin, id = 1
                $users->push(User::find(1));

                return response()->json($users);
            } catch (\Exception $e) {
                // Return the conversations with their unread messages in a json response
                return response()->json(['error' => $e->getMessage()]);
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
        return redirect()->back()->with('info', 'This module is still under Construction, thanks for your patience!');
    }

    public function completeRegistration(Request $request)
    {
        if ($request->action == 'index') {
            $me = Auth::user()->isAgent;
            if (Auth::user()->role == 'super') {
                // $agents = Agent::with('user')->get();
                // fetch all user with role agents and their data from table agents
                $agents = User::with('isAgent')->where('role', 'agent')->get();
                // dd($agents[4]->isAgent);
                return view('agent.complete', compact('me', 'agents'));
            }

            return view('agent.complete', compact('me'));
        }

        // Store agent data
        if ($request->action == 'store') {
            try {
                $request->validate([
                    'country' => 'required|string|max:100',
                    'NIN' => 'required|string|max:50|unique:agents',
                    'address' => 'required|string',
                    // 'signed_agreement_form' => 'required|file|mimes:pdf,jpg,png|max:2048',
                    'document_attachment_1_name' => 'required|string',
                    'document_attachment_1' => 'required|file|mimes:pdf,jpg,png|max:2048',
                    'acceptTerms' => 'accepted',
                    'ruleOfLaw' => 'accepted',
                    'verifyInformations' => 'accepted',
                ]);


                // Handle document uploads
                // $signedAgreementPath = $request->file('signed_agreement_form')->store('documents');
                $compulsoryDocumentPath = $request->file('document_attachment_1')->store('documents', 'public');


                $agent = [];
                $agent['country'] = $request->country;
                $agent['NIN'] = $request->NIN;
                $agent['address'] = $request->address;
                // $agent->signed_agreement_form = $signedAgreementPath;
                $agent['registration_status'] = 'step_2';
                $agent['document_attachment_1_name'] = $request->document_attachment_1_name;
                $agent['document_attachment_1'] = $compulsoryDocumentPath;
                $agent['request_date'] = now();

                // Handle optional documents
                for ($i = 2; $i <= 3; $i++) {
                    $docNameField = "document_attachment_{$i}_name";
                    $docFileField = "document_attachment_{$i}";
                    if ($request->hasFile($docFileField)) {
                        $documentPath = $request->file($docFileField)->store('documents', 'public');
                        $agent[$docNameField] = $request->$docNameField;
                        $agent[$docFileField] = $documentPath;
                    }
                }

                $me = Auth::user()->isAgent;
                if ($me) {
                    Agent::where('user_id', Auth::user()->id)->update($agent);
                } else {
                    Agent::create([
                        'user_id' => Auth::user()->id,
                        'country' => $request->country,
                        'NIN' => $request->NIN,
                        'address' => $request->address,
                        'document_attachment_1_name' => $request->document_attachment_1_name,
                        'document_attachment_1' => $compulsoryDocumentPath,
                        'document_attachment_2_name' => $agent['document_attachment_2_name'],
                        'document_attachment_2' => $agent['document_attachment_2'],
                        'document_attachment_3_name' => $agent['document_attachment_3_name'],
                        'document_attachment_3' => $agent['document_attachment_3'],
                        'request_date' => now(),
                        'registration_status' => 'step_2',
                    ]);
                }
                return redirect()->back()->with('success', 'Step 1 is completed successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // Update agent's data
        if ($request->action == 'update') {
            $agent = Agent::find($request->agent_id);

            if (!$agent) {
                return redirect()->back()->with('error', 'Agent not found.');
            }

            $agent->country = $request->country ?? $agent->country;
            $agent->NIN = $request->NIN ?? $agent->NIN;
            $agent->address = $request->address ?? $agent->address;

            // Update signed agreement if uploaded
            if ($request->hasFile('signed_agreement_form')) {
                $signedAgreementPath = $request->file('signed_agreement_form')->store('documents');
                $agent->signed_agreement_form = $signedAgreementPath;
            }

            // Update compulsory document if uploaded
            if ($request->hasFile('document_attachment_1')) {
                $compulsoryDocumentPath = $request->file('document_attachment_1')->store('documents');
                $agent->document_attachment_1 = $compulsoryDocumentPath;
            }

            // Update optional documents
            for ($i = 2; $i <= 3; $i++) {
                $docNameField = "document_attachment_{$i}_name";
                $docFileField = "document_attachment_{$i}";
                if ($request->hasFile($docFileField)) {
                    $documentPath = $request->file($docFileField)->store('documents');
                    $agent->$docNameField = $request->$docNameField;
                    $agent->$docFileField = $documentPath;
                }
            }

            $agent->save();

            return redirect()->back()->with('success', 'Agent information updated successfully!');
        }

        // next step
        if ($request->action == 'next_step') {
            $agent = Auth::user()->isAgent;
            if ($agent) {
                $agent->registration_status = 'step_3';
                $agent->save();
                return redirect()->back()->with('success', 'Step 2 is completed successfully.');
            } else {
                return redirect()->back()->with('error', 'Agent not found.');
            }
        }

        // verify
        if ($request->action == 'verify') {
            // dd($request->all());
            $agent = Agent::find($request->id);
            if ($request->set_status == "accepted") {
                $agent->status = 'verified';
                $agent->verified_by = Auth::user()->id;
                $agent->verified_date = now();
                $agent->save();
                return redirect()->back()->with('success', 'Agent is verified successfully.');
            }
            if ($request->set_status == 'rejected') {
                $agent->status = 'unverified';
                $agent->verified_by = Auth::user()->id;
                $agent->verified_date = now();
                $agent->registration_status = 'incomplete';
                $agent->save();
                return redirect()->back()->with('success', 'Agent is rejected successfully.');
            }
        }

        // upload aggreement form
        if ($request->action == 'upload_agreement_form') {
            // dd($request->all());
            $agent = Agent::find($request->agent_id);
            if ($agent) {
                $agent->signed_agreement_form = $request->file('signed_agreement_form')->store('documents', 'public');
                $agent->registration_status = 'complete';
                $agent->save();
                return redirect()->back()->with('success', 'Agreement form uploaded successfully.');
            } else {
                return redirect()->back()->with('error', 'Agent not found.');
            }
        }

        // generate agent code
        if ($request->action == 'generateAgentCode') {
            // dd($request->all());
            $agent = Agent::find($request->id);
            if ($agent) {
                $agent->agent_code = $this->generateAgentCode();
                $agent->save();
                return redirect()->back()->with('success', 'Agent code generated successfully.');
            } else {
                return redirect()->back()->with('error', 'Agent not found.');
            }
        }

        // restart step 2
        if ($request->action == 'restart_steps') {
            $agent = Auth::user()->isAgent;
            if ($agent) {
                $agent->registration_status = 'step_1';
                $agent->save();
                return redirect()->back()->with('success', 'Step 2 restarted successfully.');
            } else {
                return redirect()->back()->with('error', 'Agent not found.');
            }
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
