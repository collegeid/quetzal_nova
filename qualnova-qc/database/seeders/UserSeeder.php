<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'supervisor@qualnova.team'],
            [
                'name' => 'Supervisor Produksi',
                'username' => 'supervisor',
                'password' => 'QualNovaSecure',
                'role' => 'manager_produksi',
                'whatsapp' => '62823276328633',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'qc@qualnova.team'],
            [
                'name' => 'Petugas QC',
                'username' => 'qc',
                'password' => 'QualNovaSecure',
                'role' => 'petugas_qc',
                'whatsapp' => '62823276328633',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@qualnova.team'],
            [
                'name' => 'Operator Produksi',
                'username' => 'operator',
                'password' => 'QualNovaSecure',
                'role' => 'operator_produksi',
                'whatsapp' => '62823276328633',
                'email_verified_at' => now(),
            ]
        );
    }
}
