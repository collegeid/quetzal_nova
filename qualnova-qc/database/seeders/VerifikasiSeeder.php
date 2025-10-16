<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Verifikasi;
use App\Models\User;
use App\Models\DataCacat;

class VerifikasiSeeder extends Seeder
{
    public function run()
    {
        $qc = User::where('role', 'qc')->first();
        $dataCacat = DataCacat::first();

        if ($qc && $dataCacat) {
            Verifikasi::create([
                'id_cacat' => $dataCacat->id_cacat,
                'qc_id' => $qc->id_user ?? $qc->id,
                'tanggal_verifikasi' => now(),
                'valid' => true,
                'catatan' => 'Hasil pemeriksaan untuk ' . $dataCacat->jenis_cacat,
            ]);
        }
    }
}
