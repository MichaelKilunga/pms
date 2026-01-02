<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaWhatsAppService
{
    protected $baseUrl = 'https://graph.facebook.com/v17.0'; // Or latest version
    protected $pharmacyId;
    protected $isEnabled;
    protected $phoneNumberId;
    protected $accessToken;
    protected $businessAccountId;

    public function __construct()
    {
        $this->pharmacyId = session('current_pharmacy_id');
        $this->loadSettings();
    }

    protected function loadSettings()
    {
        // 1. Try to load Pharmacy specific settings first
        $keys = ['whatsapp_enabled', 'whatsapp_phone_number_id', 'whatsapp_access_token', 'whatsapp_business_account_id'];
        
        $settings = SystemSetting::where('pharmacy_id', $this->pharmacyId)
            ->whereIn('key', $keys)
            ->pluck('value', 'key');

        $this->isEnabled = filter_var($settings['whatsapp_enabled'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $this->phoneNumberId = $settings['whatsapp_phone_number_id'] ?? null;
        $this->accessToken = $settings['whatsapp_access_token'] ?? null;
        $this->businessAccountId = $settings['whatsapp_business_account_id'] ?? null;

        // 2. If not enabled or missing credentials locally, try Global Settings (pharmacy_id = null)
        // Only if pharmacy not explicitly disabled it? Or should global override? 
        // Logic: specific setting overrides global. If specific is NOT set (missing), fallback to global.
        // If specific is set to "false", it means "disabled for this pharmacy".
        
        if (!$this->isEnabled && empty($settings['whatsapp_enabled'])) {
            // Not explicitly set, so try global
            $globalSettings = SystemSetting::whereNull('pharmacy_id')
                ->whereIn('key', $keys)
                ->pluck('value', 'key');
                
            $this->isEnabled = filter_var($globalSettings['whatsapp_enabled'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
            if ($this->isEnabled) {
                 $this->phoneNumberId = $globalSettings['whatsapp_phone_number_id'] ?? null;
                 $this->accessToken = $globalSettings['whatsapp_access_token'] ?? null;
                 $this->businessAccountId = $globalSettings['whatsapp_business_account_id'] ?? null;
            }
        }
    }

    public function isEnabled()
    {
        return $this->isEnabled && $this->phoneNumberId && $this->accessToken;
    }

    /**
     * Send a text message
     */
    public function sendMessage($to, $message)
    {
        if (!$this->isEnabled()) {
            Log::warning("WhatsApp is disabled or missing credentials for pharmacy {$this->pharmacyId}");
            return false;
        }

        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        try {
            $response = Http::withToken($this->accessToken)
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("WhatsApp Send Failed: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a document (PDF link)
     * Note: sending a link as a message body is often easier than uploading media if not verified.
     * But we can also implement 'type' => 'document' with a link if the link is public.
     */
    public function sendDocumentLink($to, $link, $caption = "Here is your Stock Suggestion Report")
    {
        // For simplicity, sending as text with link first.
        // True "document" type requires the file to be accessible by Facebook servers or uploaded via Media API.
        // Given we are running on localhost/dev potentially, a link might not be reachable by FB.
        // FOR NOW: We will send it as a text message containing the link.
        
        $message = "{$caption}\n\nDownload here: {$link}";
        return $this->sendMessage($to, $message);
    }
}
