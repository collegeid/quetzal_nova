<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Supervisor Produksi',
            'username' => 'supervisor',
            'email' => 'supervisor@qualnova.team',
            'password' => Hash::make('QualNovaSecure'),
            'role' => 'manager_produksi',
            'whatsapp' => '62823276328633', // ✅ ditambahkan
        ]);

        User::create([
            'name' => 'Petugas QC',
            'username' => 'qc',
            'email' => 'qc@qualnova.team',
            'password' => Hash::make('QualNovaSecure'),
            'role' => 'petugas_qc',
            'whatsapp' => '62823276328633',
        ]);

        User::create([
            'name' => 'Operator Produksi',
            'username' => 'operator',
            'email' => 'operator@qualnova.team',
            'password' => Hash::make('QualNovaSecure'),
            'role' => 'operator_produksi',
            'whatsapp' => '62823276328633',
        ]);
    }
}
