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
                $messages = Message::with(['sender', 'usersWhoRead', 'parentMessage'=>function($query){
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
}
