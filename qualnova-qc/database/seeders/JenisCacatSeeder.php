<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisCacat;

class JenisCacatSeeder extends Seeder
{
    public function run()
    {
        $jenis = ['Kain Sobek', 'Warna Tidak Merata', 'Benang Tarik', 'Lubang', 'Kotoran', 'Jahitan Rusak'];

        foreach ($jenis as $j) {
            JenisCacat::create(['nama_jenis' => $j]);
        }
    }
}
