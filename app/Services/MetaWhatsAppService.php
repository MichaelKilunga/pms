<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaWhatsAppService
{
    protected $baseUrl = 'https://graph.facebook.com/v17.0'; // Or latest version
    protected $pharmacyId;
    protected $isGlobalEnabled;
    protected $phoneNumberId;
    protected $accessToken;
    protected $businessAccountId;

    public function __construct()
    {
        $this->pharmacyId = session('current_pharmacy_id');
        $this->loadSettings();
    }

    public function setPharmacyId($id)
    {
        $this->pharmacyId = $id;
        $this->loadSettings(); // Reload settings if pharmacy changed
        return $this;
    }

    protected function loadSettings()
    {
        // 1. Try to load Pharmacy specific settings first
        $keys = ['whatsapp_enabled', 'whatsapp_phone_number_id', 'whatsapp_access_token', 'whatsapp_business_account_id'];
        
        $settings = SystemSetting::where('pharmacy_id', $this->pharmacyId)
            ->whereIn('key', $keys)
            ->pluck('value', 'key');

        $this->isGlobalEnabled = filter_var($settings['whatsapp_enabled'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $this->phoneNumberId = $settings['whatsapp_phone_number_id'] ?? null;
        $this->accessToken = $settings['whatsapp_access_token'] ?? null;
        $this->businessAccountId = $settings['whatsapp_business_account_id'] ?? null;

        if (!$this->isGlobalEnabled && empty($settings['whatsapp_enabled'])) {
            // Not explicitly set, so try global
            $globalSettings = SystemSetting::whereNull('pharmacy_id')
                ->whereIn('key', $keys)
                ->pluck('value', 'key');
                
            $this->isGlobalEnabled = filter_var($globalSettings['whatsapp_enabled'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
            if ($this->isGlobalEnabled) {
                 $this->phoneNumberId = $globalSettings['whatsapp_phone_number_id'] ?? null;
                 $this->accessToken = $globalSettings['whatsapp_access_token'] ?? null;
                 $this->businessAccountId = $globalSettings['whatsapp_business_account_id'] ?? null;
            }
        }
    }

    public function isEnabled($pharmacyId = null)
    {
        // 1. Core platform check (is it configured correctly?)
        if (!$this->isGlobalEnabled || !$this->phoneNumberId || !$this->accessToken) {
            return false;
        }

        $pid = $pharmacyId ?? $this->pharmacyId;

        // 2. If no pharmacy context, follow global setting (System Level)
        if (!$pid) {
            return true;
        }

        // 3. Subscription/Contract Check
        $pharmacy = \App\Models\Pharmacy::find($pid);
        if (!$pharmacy) return false;

        $owner = \App\Models\User::find($pharmacy->owner_id);
        if (!$owner) return false;

        // Fetch active current contract
        $contract = \App\Models\Contract::where('owner_id', $owner->id)
            ->where('is_current_contract', 1)
            ->whereIn('status', ['active', 'graced'])
            ->first();

        // Check if WhatsApp is included in the contract details
        if (!$contract || !($contract->details['has_whatsapp'] ?? false)) {
            return false;
        }

        return true;
    }

    /**
     * Send a text message
     */
    public function sendMessage($to, $message)
    {
        if (!$this->isEnabled()) {
            $msg = "WhatsApp is disabled or missing credentials for pharmacy {$this->pharmacyId}";
            Log::warning($msg);
            return ['success' => false, 'error' => $msg];
        }

        $formattedTo = $this->formatPhoneNumber($to);

        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        try {
            $response = Http::withToken($this->accessToken)
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $formattedTo,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Unknown Meta API Error';
                Log::error("WhatsApp Send Failed: " . $response->body());
                return ['success' => false, 'error' => $errorMessage];
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Exception: " . $e->getMessage());
            return ['success' => false, 'error' => 'Exception: ' . $e->getMessage()];
        }
    }

    /**
     * Send a document (PDF link)
     */
    public function sendDocumentLink($to, $link, $caption = "Here is your Stock Suggestion Report")
    {
        $message = "{$caption}\n\nDownload here: {$link}";
        return $this->sendMessage($to, $message);
    }

    /**
     * Format phone number to 255XXXXXXXXX
     */
    private function formatPhoneNumber($phone)
    {
        // 1. Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 2. If starts with 0, replace with 255
        if (substr($phone, 0, 1) === '0') {
            $phone = '255' . substr($phone, 1);
        }

        // 3. If starts with 2550..., remove the 0 after 255
        if (substr($phone, 0, 4) === '2550') {
             $phone = '255' . substr($phone, 4);
        }

        // 4. Fallback for 9 digits
        if (strlen($phone) === 9) {
            $phone = '255' . $phone;
        }

        return $phone;
    }
}
