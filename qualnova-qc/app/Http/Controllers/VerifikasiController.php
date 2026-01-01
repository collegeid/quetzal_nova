<?php

namespace App\Http\Controllers;

use App\Models\DataCacat;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function prosesVerifikasi(DataCacat $data, $statusVerifikasi)
    {
        $data->update(['status_verifikasi' => $statusVerifikasi]);

        $verifikasi = Verifikasi::firstOrNew(['id_cacat' => $data->id_cacat]);
        $verifikasi->qc_id = Auth::id();
        $verifikasi->tanggal_verifikasi = Carbon::now()->format('Y-m-d H:i:s');
        
        $verifikasi->valid = ($statusVerifikasi == 1) ? 1 : 0;
        $verifikasi->save();

        return $verifikasi;
    }
}