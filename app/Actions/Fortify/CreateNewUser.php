<?php

namespace App\Actions\Fortify;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'numeric', 'unique:users,phone', 'regex:/^[0-9]{10}$/'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'role' => $input['role'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'phone' => $input['phone_number'],
        ]);

        if ($input['role'] == 'agent') {
            
            $this->notify(
                $user,
                'Welcome on board! Follow the instructions to continue.',
                'success'
            );

            // add this agent to agent's list
            Agent::create([
                'user_id' => $user->id,
                'registration_status' => 'step_1'
            ]);
        }

        if ($input['role'] == 'owner') {
            $this->notify(
                $user,
                'Welcome to PILLPOINT, Contact Your Agent '.Auth::user()->name.', '.Auth::user()->phone.', to assign you to a subscription package.',
                'success'
            );
        }

        //send welcome notification
        try {
            $user->notify(new WelcomeNotification);
        } catch (\Exception $e) {
            // 
        }

        return $user;
    }
    private function notify(User $user, string $message, string $type): void
    {
        $notification = [
            'message' => $message,
            'type' => $type,
        ];
        $user->notify(new InAppNotification($notification));
    }
}
