<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\JenisCacat;
use App\Models\DataCacat;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        $cacatTerbanyak = DataCacat::select('jenis_cacat')
            ->groupBy('jenis_cacat')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        Laporan::create([
            'periode' => 'Oktober 2025',
            'total_cacat' => DataCacat::count(),
            'jenis_cacat_terbanyak' => $cacatTerbanyak->jenis_cacat ?? 'Belum ada data',
            'mesin_bermasalah' => 'Mesin A1',
        ]);
    }
}
