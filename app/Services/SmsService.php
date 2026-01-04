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
    protected $isEnabled;

    public function __construct()
    {
        $this->loadSettings();
    }

    protected function loadSettings()
    {
        // Load GLOBAL settings (pharmacy_id = null)
        $settings = SystemSetting::whereNull('pharmacy_id')
            ->whereIn('key', ['sms_enabled', 'sms_api_key', 'sms_sender_id'])
            ->pluck('value', 'key');

        $this->isEnabled = filter_var($settings['sms_enabled'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $this->apiKey = $settings['sms_api_key'] ?? null;
        $this->senderId = $settings['sms_sender_id'] ?? 'PILPOINTONE'; 
    }

    public function send($to, $message)
    {
        if (!$this->isEnabled || !$this->apiKey) {
            Log::warning("SmsService: SMS disabled or missing API key.");
            return false;
        }

        try {
            // Documented endpoint for Skypush
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-API-KEY' => $this->apiKey,
            ])->post("{$this->baseUrl}/send", [
                'to' => $to,
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
}
