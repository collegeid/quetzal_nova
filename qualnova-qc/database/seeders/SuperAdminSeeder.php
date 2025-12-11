<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@qualnova.team'],
            [
                'name' => 'Qual Nova | Super Admin',
                'username' => 'superadmin',
                'password' => 'QualNovaSecure',
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}