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
        
        $globalKeys = [
            'pricing_mode', 'profit_share_percentage', 
            'dynamic_rate_per_branch', 'dynamic_rate_per_staff', 'dynamic_rate_per_item',
            'upgrade_rate_whatsapp', 'upgrade_rate_sms', 'upgrade_rate_reports',
            'upgrade_rate_stock_management', 'upgrade_rate_stock_transfer',
            'upgrade_rate_staff_management', 'upgrade_rate_receipts', 'upgrade_rate_analytics'
        ];

        foreach ($data as $key => $value) {
            $saveId = in_array($key, $globalKeys) ? null : $pharmacyId;
            
            SystemSetting::updateOrCreate(
                ['key' => $key, 'pharmacy_id' => $saveId],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
