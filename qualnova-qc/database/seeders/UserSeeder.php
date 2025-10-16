<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'QC Febrian',
            'username' => 'qc1',
            'email' => 'qc1@example.com',
            'password' => Hash::make('qc123'),
            'role' => 'qc',
        ]);

        User::create([
            'name' => 'Verifikator Sinta',
            'username' => 'verifikator1',
            'email' => 'verifikator1@example.com',
            'password' => Hash::make('verify123'),
            'role' => 'verifikator',
        ]);
    }
}
