<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $client;
    protected $apiUrl;
    protected $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('NEXTSMS_API_URL');
        $this->headers = [
            'Authorization' => 'Basic ' . env('NEXTSMS_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function sendSms($to, $message, $reference = null)
    {
        $body = [
            'from' => env('NEXTSMS_SENDER_ID'),
            'to' => $to,
            'text' => $message,
            'reference' => $reference ?: uniqid(),
        ];

        try {
            // Sending the request
            $response = $this->client->post($this->apiUrl, [
                'headers' => $this->headers,
                'json' => $body
            ]);

            // Return the response body as a string
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            Log::error("SMS send failed: " . $e->getMessage());
            return false; // You can return a custom error message here
        }
    }
}
