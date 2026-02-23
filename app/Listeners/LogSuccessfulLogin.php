<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        if ($event->user) {
            Audit::create([
                'auditable_type' => get_class($event->user),
                'auditable_id' => $event->user->id, // Use the user's ID
                'user_id' => $event->user->id,
                'event' => 'login',
                'old_values' => [],
                'new_values' => ['logged_in_at' => now()],
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);


        }
    }
}

