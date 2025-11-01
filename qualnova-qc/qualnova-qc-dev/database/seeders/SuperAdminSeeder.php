<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // <── Tambahkan ini

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Qual Nova | Super Admin',
            'username' => 'superadmin', // <── Pastikan kolom ini ada
            'email' => 'superadmin@qualnova.team',
            'password' => Hash::make('QualNovaSecure'),
            'role' => 'super_admin',
        ]);
    }
}
