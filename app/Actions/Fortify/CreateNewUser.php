<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeNotification;
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
            'role' => ['required','string','max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required','numeric','unique:users,phone', 'regex:/^[0-9]{10}$/'],
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

        $this->notify(
            $user,
            'Welcome PILLPOINT, choose a plan to continue using our services!',
            'success'
        );
        //send welcome notification
        try{
            $user->notify(new WelcomeNotification);
        }catch(\Exception $e){
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
