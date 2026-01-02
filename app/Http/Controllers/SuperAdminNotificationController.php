<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class SuperAdminNotificationController extends Controller
{
    public function index()
    {
        // Load Global Settings (pharmacy_id = null)
        $settings = SystemSetting::whereNull('pharmacy_id')
            ->whereIn('key', [
                'whatsapp_enabled', 'whatsapp_phone_number_id', 'whatsapp_access_token', 'whatsapp_business_account_id',
                'sms_enabled', 'sms_api_key', 'sms_sender_id'
            ])
            ->pluck('value', 'key');

        return view('superAdmin.settings.notification', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_phone_number_id' => 'nullable|string',
            'whatsapp_access_token' => 'nullable|string',
            'whatsapp_business_account_id' => 'nullable|string',
            'sms_api_key' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
        ]);

        $data = $request->except(['_token']);

        // Handle toggles (checkboxes might not send if unchecked)
        $data['whatsapp_enabled'] = $request->has('whatsapp_enabled') ? 'true' : 'false';
        $data['sms_enabled'] = $request->has('sms_enabled') ? 'true' : 'false';

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key, 'pharmacy_id' => null], // Global Scope
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Notification settings updated successfully.');
    }

    public function manageUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('superAdmin.users.notifications', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $preferences = [
            'whatsapp' => $request->has('whatsapp'),
            'sms' => $request->has('sms'),
            'mail' => $request->has('mail'),
        ];

        $user->notification_preferences = $preferences;
        $user->save();

        return redirect()->back()->with('success', 'User notification preferences updated.');
    }
}
