<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fetch users for the "New Conversation" modal
        // Logic copied from legacy AgentController: Users with same role + Superadmin (ID 1)
        $potentialRecipients = User::role($user->getRoleNames())->get();
        if (!$potentialRecipients->contains('id', 1)) {
            $potentialRecipients->push(User::find(1));
        }

        // We'll fetch conversations via AJAX to keep the initial load light, 
        // but we can pass an initial set if preferred. For now, let's load the view.
        return view('agent.messages', compact('potentialRecipients'));
    }

    /**
     * Fetch all conversations for the authenticated user.
     */
    public function fetchConversations()
    {
        $user = Auth::user();

        try {
            // Fetch conversations where user is creator or participant
            $conversations = Conversation::where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                  ->orWhereHas('participants', function ($pq) use ($user) {
                      $pq->where('user_id', $user->id);
                  });
            })
            ->with(['messages' => function ($q) {
                $q->latest()->take(1); // Get latest message for preview
            }, 'participants'])
            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('status', 'unread')
                  ->where('sender_id', '!=', $user->id)
                  ->whereDoesntHave('usersWhoRead', function ($rq) use ($user) {
                      $rq->where('user_id', $user->id);
                  });
            }])
            ->orderByDesc('updated_at') // Sort by most recent activity
            ->get();

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Fetch messages for a specific conversation.
     */
    public function fetchMessages(Request $request, $conversationId)
    {
        try {
            // Ensure user is part of the conversation
            $conversation = Conversation::findOrFail($conversationId);
            $user = Auth::user();

            if (!$this->isParticipant($conversation, $user)) {
                return response()->json(['success' => false, 'error' => 'Unauthorized']);
            }

            $messages = Message::with(['sender', 'parentMessage.sender'])
                ->where('conversation_id', $conversationId)
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages as read
            $this->markMessagesAsRead($conversation, $user);

            return response()->json([
                'success' => true,
                'messages' => $messages,
                'current_user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'parent_message_id' => 'nullable|exists:messages,id'
        ]);

        if (empty($request->content) && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'error' => 'Message cannot be empty']);
        }

        try {
            DB::beginTransaction();

            $attachmentPath = null;
            $messageType = 'text';

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('attachments', 'public');
                $mime = $file->getMimeType();
                
                if (str_starts_with($mime, 'image/')) {
                    $messageType = 'image';
                } else {
                    $messageType = 'file';
                }
            }

            $message = Message::create([
                'conversation_id' => $request->conversation_id,
                'sender_id' => Auth::id(),
                'content' => $request->content,
                'status' => 'unread',
                'attachment' => $attachmentPath,
                'message_type' => $messageType,
                'parent_message_id' => $request->parent_message_id
            ]);

            // Update conversation timestamp
            $message->conversation->touch();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message->load('sender', 'parentMessage.sender')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Create a new conversation.
     */
    public function createConversation(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $conversation = Conversation::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'open',
                'creator_id' => Auth::id(),
            ]);

            // Add participants (Creator + Recipients)
            $participants = array_unique(array_merge($request->recipients, [Auth::id()]));
            $conversation->participants()->attach($participants);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Conversation created']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a message (soft or hard delete depending on policy).
     */
    public function destroy($id)
    {
        try {
            $message = Message::findOrFail($id);
            
            if ($message->sender_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized']);
            }

            $message->delete();

            return response()->json(['success' => true, 'message' => 'Message deleted']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Helper to check if user is participant.
     */
    private function isParticipant($conversation, $user)
    {
        return $conversation->creator_id === $user->id || 
               $conversation->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Helper to mark messages as read.
     */
    private function markMessagesAsRead($conversation, $user)
    {
        // Find unread messages not sent by me
        $unreadMessages = $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereDoesntHave('usersWhoRead', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();

        foreach ($unreadMessages as $msg) {
            $msg->usersWhoRead()->attach($user->id, ['read_at' => now()]);
        }
    }
}
