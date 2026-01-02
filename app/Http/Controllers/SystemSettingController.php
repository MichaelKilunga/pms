<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('Superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        $pharmacyId = session('current_pharmacy_id');
        
        // Fetch global definitions/defaults
        $globalSettings = SystemSetting::whereNull('pharmacy_id')->get()->keyBy('key');
        
        // Fetch tenant-specific overrides
        $tenantSettings = SystemSetting::where('pharmacy_id', $pharmacyId)->get()->keyBy('key');

        // Merge: Tenant settings overwrite global defaults for display
        $settings = $globalSettings->merge($tenantSettings);

        return view('admin.settings.system', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->hasRole('Superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        $pharmacyId = session('current_pharmacy_id');
        $data = $request->except(['_token']);
        
        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key, 'pharmacy_id' => $pharmacyId],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
