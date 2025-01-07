<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => 'user', // Default role
            'phone' => $this->faker->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];
        // return [
        //     [
        //         'name' => 'Super Admin',
        //         'email' => 'info@skylinksolutions.co.tz',
        //         'email_verified_at' => now(),
        //         'role' => 'super',
        //         'phone' => '0742177328',
        //         'password' => static::$password ??= Hash::make('password'),
        //         'two_factor_secret' => null,
        //         'two_factor_recovery_codes' => null,
        //         'remember_token' => Str::random(10),
        //         'profile_photo_path' => null,
        //         'current_team_id' => null,
        //     ],
        //     [
        //         'name' => 'Default Owner',
        //         'email' => 'owner@mail.com',
        //         'email_verified_at' => now(),
        //         'role' => 'owner',
        //         'phone' => '0742177328',
        //         'password' => static::$password ??= Hash::make('password'),
        //         'two_factor_secret' => null,
        //         'two_factor_recovery_codes' => null,
        //         'remember_token' => Str::random(10),
        //         'profile_photo_path' => null,
        //         'current_team_id' => null,
        //     ],
        //     [
        //         'name' => 'Default Staff',
        //         'email' => 'staff@mail.com',
        //         'email_verified_at' => now(),
        //         'role' => 'staff',
        //         'phone' => '0742177328',
        //         'password' => static::$password ??= Hash::make('password'),
        //         'two_factor_secret' => null,
        //         'two_factor_recovery_codes' => null,
        //         'remember_token' => Str::random(10),
        //         'profile_photo_path' => null,
        //         'current_team_id' => null,
        //     ]
        // ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn(array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
