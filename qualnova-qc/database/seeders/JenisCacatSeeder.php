<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisCacat;

class JenisCacatSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Kain Sobek',
            'Benang Tarik',
            'Warna Tidak Merata',
        ];

        foreach ($items as $name) {
            JenisCacat::updateOrCreate(['nama_jenis' => $name], ['nama_jenis' => $name]);
        }
    }
}
