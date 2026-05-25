<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

<<<<<<< HEAD
=======
    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\User $user): void {
            // Seeder/role mungkin belum ada di beberapa test; pastikan role tersedia.
            \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'user',
                'guard_name' => 'web',
            ]);

            $user->assignRole('user');
        });
    }


>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
