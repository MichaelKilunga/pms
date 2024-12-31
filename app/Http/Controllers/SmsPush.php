<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;

class SmsPush extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendSmsNotification(Request $request)
    {
        $phoneNumber = $request->input('phoneNumber');
        $message = $request->input('message');
        $response = $this->smsService->sendSms($phoneNumber, $message);

        if ($response) {
            return response()->json(['status' => 'success', 'message' => 'SMS sent successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send SMS.']);
        }
    }
}
