<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $baseUrl = 'https://skypush.skylinksolutions.co.tz/api/v1'; 
    protected $apiKey;
    protected $senderId;
    protected $isGlobalEnabled;
    protected $pharmacyId;

    public function __construct()
    {
        $this->pharmacyId = session('current_pharmacy_id');
        $this->loadSettings();
    }

    public function setPharmacyId($id)
    {
        $this->pharmacyId = $id;
        return $this;
    }

    protected function loadSettings()
    {
        // Load GLOBAL settings (pharmacy_id = null)
        $settings = SystemSetting::whereNull('pharmacy_id')
            ->whereIn('key', ['sms_enabled', 'sms_api_key', 'sms_sender_id'])
            ->pluck('value', 'key');

        $this->isGlobalEnabled = filter_var($settings['sms_enabled'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $this->apiKey = $settings['sms_api_key'] ?? null;
        $this->senderId = $settings['sms_sender_id'] ?? 'PILPOINTONE'; 
    }

    public function isEnabled($pharmacyId = null)
    {
        // 1. Core global check
        if (!$this->isGlobalEnabled || !$this->apiKey) {
            return false;
        }

        $pid = $pharmacyId ?? $this->pharmacyId;

        // 2. If no pharmacy context, follow global setting (System Level)
        if (!$pid) {
            return true;
        }

        // 3. Contract Check for Pharmacy
        $pharmacy = \App\Models\Pharmacy::find($pid);
        if (!$pharmacy) return false;

        $owner = \App\Models\User::find($pharmacy->owner_id);
        if (!$owner) return false;

        $contract = \App\Models\Contract::where('owner_id', $owner->id)
            ->where('is_current_contract', 1)
            ->whereIn('status', ['active', 'graced'])
            ->first();

        if (!$contract || !($contract->details['has_sms'] ?? false)) {
            return false;
        }

        return true;
    }

    public function send($to, $message)
    {
        if (!$this->isEnabled()) {
            Log::warning("SmsService: SMS disabled, missing API key, or no active contract for pharmacy {$this->pharmacyId}.");
            return false;
        }

        try {
            $formattedTo = $this->formatPhoneNumber($to);

            // Documented endpoint for Skypush
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-API-KEY' => $this->apiKey,
            ])->post("{$this->baseUrl}/send", [
                'to' => $formattedTo,
                'message' => $message,
                'sender' => $this->senderId,
                'client_app' => '1',
                'sender_id' => $this->senderId
            ]);

            if ($response->successful()) {
                Log::info("SmsService: Sent to {$to}");
                return true;
            } else {
                Log::error("SmsService: Failed. " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("SmsService Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number to 255XXXXXXXXX
     * Example: 0742177328 -> 255742177328
     * Example: +255742... -> 255742...
     */
    private function formatPhoneNumber($phone)
    {
        // 1. Remove any non-numeric characters (spaces, +, -, etc.)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 2. If starts with 0, replace with 255
        if (substr($phone, 0, 1) === '0') {
            $phone = '255' . substr($phone, 1);
        }

        // 3. If starts with 2550..., remove the 0 after 255
        if (substr($phone, 0, 4) === '2550') {
             $phone = '255' . substr($phone, 4);
        }

        // 4. If length is less than 12 (e.g. 9 digits w/o prefix?), prepend 255?
        //    Let's assume standard TZ numbers without prefix are 9 digits (7xxxxxxxx).
        //    If length == 9, prepend 255.
        if (strlen($phone) === 9) {
            $phone = '255' . $phone;
        }

        return $phone;
    }
}
