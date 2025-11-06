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
        // Basic validation (note recaptcha_token used below)
        $data = $request->validate([
            'phone' => 'nullable|string|max:40',
            'location' => 'nullable|string|max:255',
            'service' => 'required|string|max:255',
            'message' => 'nullable|string|max:2000',
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
        $max = 5;
        $ttlSeconds = 10;//3600; // 1 hour

        $count = Cache::get($key, 0);
        if ($count >= $max) {
            return response()->json(['status' => 'error', 'message' => 'Too many submissions. Please try again later.'], 429);
        }


        // Proceed: increment rate counter
        Cache::put($key, $count + 1, $ttlSeconds);

        try {
            // Prefer queue if available; fallback to send
                Mail::to(env('SUPPORT_EMAIL')?? "micksimon30@gmail.com")->send(new ContactUsMail($data));

            return response()->json(['status' => 'success', 'message' => 'Message sent. We will contact you shortly.']);
        } catch (\Throwable $e) {
            Log::error('Contact mail failed: ' . $e->getMessage(), ['data' => $data]);
            return response()->json(['status' => 'error', 'message' => 'Failed to send message. Try again later.'], 500);
        }
    }
}
