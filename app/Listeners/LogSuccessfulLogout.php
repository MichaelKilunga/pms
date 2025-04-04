<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        if ($event->user) {
            Audit::create([
                'user_id' => $event->user->id,
                'event' => 'logout',
                'old_values' => [],
                'new_values' => ['logged_out_at' => now()],
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            Log::info('User logged out:', [
                'user_id' => $event->user->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}

