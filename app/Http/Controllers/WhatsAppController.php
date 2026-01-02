<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use App\Services\MetaWhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class WhatsAppController extends Controller
{
    protected $inventoryService;
    protected $whatsAppService;

    public function __construct(InventoryService $inventoryService, MetaWhatsAppService $whatsAppService)
    {
        $this->inventoryService = $inventoryService;
        $this->whatsAppService = $whatsAppService;
    }

    public function sendSuggestedStock(Request $request)
    {
        $pharmacyId = session('current_pharmacy_id');

        // Check if WhatsApp is enabled
        if (!$this->whatsAppService->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp integration is not configured. Please check System Settings.'
            ], 400);
        }

        // Get Stock Data
        $stocks = $this->inventoryService->getSuggestedStock($pharmacyId);

        if ($stocks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No suggested stock items found.'
            ]);
        }

        // Generate PDF
        $pdf = Pdf::loadView('reports.suggested_stock_pdf', [
            'stocks' => $stocks,
            'date' => now()->format('Y-m-d H:i')
        ]);

        // Save to temporary public storage
        // We use a random filename to avoid collisions and basic "security"
        $filename = 'suggested_stock_' . Str::random(10) . '.pdf';
        $path = 'reports/temp/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Create Public URL
        $url = asset('storage/' . $path);

        // Determine User Phone Number
        // Priority: Request input -> Auth User -> Pharmacy Owner
        $rawPhone = $request->input('phone_number') ?? Auth::user()->phone ?? null;
        
        // Format phone number
        $phoneNumber = $this->formatPhoneNumber($rawPhone);

        if (!$phoneNumber) {
             return response()->json([
                'success' => false,
                'message' => 'No valid phone number found. Please update your profile.'
            ], 400);
        }

        // Send Message
        $result = $this->whatsAppService->sendDocumentLink($phoneNumber, $url);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Report sent to WhatsApp successfully!'
            ]);
        } else {
             return response()->json([
                'success' => false,
                'message' => 'Error: ' . ($result['error'] ?? 'Unknown WhatsApp Error')
            ], 500);
        }
    }

    /**
     * Format phone number to Tanzania format (255...)
     * Handles: 07..., +255..., 2550..., etc.
     */
    private function formatPhoneNumber($number)
    {
        if (!$number) return null;

        // 1. Remove any non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // 2. Initial cleanup log (optional, for debug)
        // Log::info("Formatting phone: $number");

        // 3. Logic: Strip common prefixes to get the 'core' number, then add 255
        // Standard TZ number (without code) is 9 digits: 7xxxxxxxx, 6xxxxxxxx
        // If we strip '255' from start, then strip '0' from start, we should be left with 9 digits.
        
        if (Str::startsWith($number, '255')) {
            $number = substr($number, 3);
        }
        
        if (Str::startsWith($number, '0')) {
            $number = substr($number, 1);
        }

        // 4. Re-add 255
        return '255' . $number;
    }
}
