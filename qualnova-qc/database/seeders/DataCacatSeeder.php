<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\User;
use Carbon\Carbon;

class DataCacatSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'qc@qualnova.team')->first() ?? User::first();
        $jenisMap = JenisCacat::pluck('id_jenis', 'nama_jenis');

        if (!$user || $jenisMap->isEmpty()) {
            return;
        }

        $data = [
            [
                'tanggal' => Carbon::now()->subDays(3)->toDateString(),
                'shift' => 'Pagi',
                'jenis_kain' => 'Katun 30s',
                'lokasi_mesin' => 'Mesin A1',
                'jenis_cacat' => 'Kain Sobek',
                'id_jenis' => $jenisMap['Kain Sobek'] ?? $jenisMap->first(),
                'status_verifikasi' => true,
            ],
            [
                'tanggal' => Carbon::now()->subDays(2)->toDateString(),
                'shift' => 'Siang',
                'jenis_kain' => 'Polyester',
                'lokasi_mesin' => 'Mesin B2',
                'jenis_cacat' => 'Benang Tarik',
                'id_jenis' => $jenisMap['Benang Tarik'] ?? $jenisMap->first(),
                'status_verifikasi' => true,
            ],
            [
                'tanggal' => Carbon::now()->subDay()->toDateString(),
                'shift' => 'Malam',
                'jenis_kain' => 'Rayon',
                'lokasi_mesin' => 'Mesin C3',
                'jenis_cacat' => 'Warna Tidak Merata',
                'id_jenis' => $jenisMap['Warna Tidak Merata'] ?? $jenisMap->first(),
                'status_verifikasi' => false,
            ],
        ];

        foreach ($data as $row) {
            DataCacat::updateOrCreate(
                [
                    'tanggal' => $row['tanggal'],
                    'lokasi_mesin' => $row['lokasi_mesin'],
                    'jenis_cacat' => $row['jenis_cacat'],
                    'id_user' => $user->id,
                ],
                $row + [
                    'id_user' => $user->id,
                ]
            );
        }
    }
}