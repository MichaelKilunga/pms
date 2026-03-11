<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', \Illuminate\Validation\Rules\Password::min(8), 'confirmed', function ($attribute, $value, $fail) {
            if (strtolower($value) === 'password') {
                $fail('The new password cannot be the default "password".');
            }
        }];
    }
}
