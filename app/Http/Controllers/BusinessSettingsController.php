<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessSettingsController extends Controller
{
    public function index()
    {
        // Get the current authenticated owner's pharmacy (business)
        // Assuming one pharmacy per owner for settings context, or we pick the first one, 
        // or the UI allows selecting which business to configure.
        // For this task, let's assume we configure the primary pharmacy or iterate.
        // The prompt says "Fetches the current authenticated owner's business."
        
        $pharmacyId = session('current_pharmacy_id');
        $pharmacy = $pharmacyId ? Pharmacy::find($pharmacyId) : Pharmacy::where('owner_id', Auth::id())->first();
        
        if (!$pharmacy) {
             return redirect()->back()->with('error', 'No active business/pharmacy found.');
        }

        $config = $pharmacy->config ?? [];

        return view('settings.index', compact('config', 'pharmacy'));
    }

    public function update(Request $request)
    {
        $pharmacyId = session('current_pharmacy_id');
        $pharmacy = $pharmacyId ? Pharmacy::find($pharmacyId) : Pharmacy::where('owner_id', Auth::id())->first();

        if (!$pharmacy) {
            abort(404, 'Business not found');
        }

        // List of known toggle keys to handle unchecked state
        $booleanKeys = [
            'require_supplier',
            'require_expiry_date',
            'receipt_printing',
            'sales_notebook',
            'stock_transfers',
            'email_notification',
            'sms_notification',
            'whatsapp_notification',
            'in_app_notification',
            'allow_staff_edit_delete_stock',
            'allow_staff_add_stock',
            'allow_staff_view_stock'
        ];
        
        $newConfig = $pharmacy->config ?? [];
        
        // Update boolean keys
        foreach ($booleanKeys as $key) {
            $newConfig[$key] = $request->has($key);
        }
        
        // Update other keys (thresholds, etc)
        if ($request->has('low_stock_alert_level')) {
            $newConfig['low_stock_alert_level'] = $request->input('low_stock_alert_level');
        }
        
        if ($request->has('short_dated_stock_duration')) {
            $newConfig['short_dated_stock_duration'] = $request->input('short_dated_stock_duration');
        }

        $pharmacy->config = $newConfig;
        $pharmacy->save();

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
