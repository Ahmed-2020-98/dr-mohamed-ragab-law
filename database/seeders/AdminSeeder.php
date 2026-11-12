<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates the first admin from ADMIN_EMAIL / ADMIN_PASSWORD in .env.
 * Nothing is created when they are not set — use `php artisan make:filament-user` instead.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD are not set in .env — no admin user created.');

            return;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => env('ADMIN_NAME', 'مدير الموقع'),
            'password' => $password,
            'email_verified_at' => now(),
        ]);
    }
}
