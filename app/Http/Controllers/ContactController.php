<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Basic validation
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:40',
            'location' => 'nullable|string|max:255',
            'service' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'nickname' => 'nullable|string|max:10',
        ]);

        // Honeypot: if filled, treat as bot
        if (!empty($data['nickname'])) {
            Log::warning('Contact form honeypot triggered', ['ip' => $request->ip(), 'data' => $data]);
            return response()->json(['status' => 'error', 'message' => 'error!'], 422);
        }

        // Rate limit per IP: 5 per hour (adjust as needed)
        $ip = $request->ip();
        $key = "contact_submissions:{$ip}";
        $max = 500;
        $ttlSeconds = 3600; // 1 hour

        $count = Cache::get($key, 0);
        if ($count >= $max) {
            return response()->json(['status' => 'error', 'message' => 'Too many submissions. Please try again later.'], 429);
        }

        // Proceed: increment rate counter
        Cache::put($key, $count + 1, $ttlSeconds);

        try {
            // Prepare message with additional details
            $fullMessage = $data['message'];
            if (!empty($data['location'])) {
                $fullMessage .= "\n\nLocation: " . $data['location'];
            }
            if (!empty($data['service'])) {
                $fullMessage .= "\nService: " . $data['service'];
            }

            // Send to SkyPush API
            $response = Http::withHeaders([
                'X-API-KEY' => env('SKYPUSH_API_KEY'),
                'Accept' => 'application/json',
            ])->post('https://skypush.skylinksolutions.co.tz/api/v1/contact/contact', [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'message' => $fullMessage,
                'source' => 'contact',
            ]);

            if ($response->successful()) {
                return response()->json(['status' => 'success', 'message' => 'Message sent. We will contact you shortly.']);
            } else {
                Log::error('SkyPush API Error: ' . $response->body());
                return response()->json(['status' => 'error', 'message' => 'Failed to send message. Please try again later.'], $response->status());
            }

        } catch (\Throwable $e) {
            Log::error('Contact API failed: ' . $e->getMessage(), ['data' => $data]);
            return response()->json(['status' => 'error', 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
