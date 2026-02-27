<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastNotification;
use App\Models\User;
use App\Models\Package;
use App\Notifications\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ManualNotificationController extends Controller
{
    public function index()
    {
        $broadcasts = BroadcastNotification::with('user')->latest()->paginate(10);
        return view('superAdmin.notifications.history', compact('broadcasts'));
    }

    public function create()
    {
        $packages = Package::all();
        $roles = ['owner', 'agent', 'pharmacy_staff']; // Basic roles
        $users = User::orderBy('name')->get();
        return view('superAdmin.notifications.compose', compact('packages', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'channels' => 'required|array',
            'channels.*' => 'in:database,mail,sms,whatsapp',
            'target_roles' => 'nullable|array',
            'target_packages' => 'nullable|array',
            'specific_users' => 'nullable|array',
            'specific_users.*' => 'exists:users,id',
        ]);

        $broadcast = BroadcastNotification::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'channels' => $validated['channels'],
            'target_criteria' => [
                'roles' => $validated['target_roles'] ?? [],
                'packages' => $validated['target_packages'] ?? [],
                'users' => $validated['specific_users'] ?? [],
            ],
            'status' => 'processing',
            'user_id' => Auth::id(),
        ]);

        // Logic to find target users
        $query = User::query();

        if (!empty($validated['specific_users'])) {
            $query->whereIn('id', $validated['specific_users']);
        } else {
            if (!empty($validated['target_roles'])) {
                $query->whereIn('role', $validated['target_roles']);
            }

            if (!empty($validated['target_packages'])) {
                $query->whereHas('pharmacies.contract', function ($q) use ($validated) {
                    $q->whereIn('package_id', $validated['target_packages'])
                      ->where('is_current_contract', 1);
                });
            }
        }

        $recipients = $query->get();
        $sentCount = 0;

        foreach ($recipients as $user) {
            try {
                // Determine which channels this user actually receives based on their preferences
                // The BaseNotification and InAppNotification classes we improved earlier handle this.
                $notification = (new InAppNotification([
                    'title' => $broadcast->title,
                    'message' => $broadcast->body,
                    'type' => 'info',
                    'action_url' => route('notifications'),
                ]))->withChannels($broadcast->channels);

                Notification::send($user, $notification);
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to send broadcast {$broadcast->id} to user {$user->id}: " . $e->getMessage());
            }
        }

        $broadcast->update([
            'status' => 'completed',
            'sent_count' => $sentCount,
        ]);

        return redirect()->route('superAdmin.notifications.history')->with('success', "Notification broadcast sent to {$sentCount} users.");
    }
}
