<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use App\Models\Laporan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalCacat = DataCacat::count();
        $totalJenis = JenisCacat::count();
        $totalUser = User::count();
        $verifikasiValid = Verifikasi::where('valid', true)->count();
        $verifikasiBelum = Verifikasi::where('valid', false)->count();

        // ambil laporan kalau ada
        $laporan = Laporan::orderByDesc('total_cacat')->first();

        $jenisTerbanyak = $laporan->jenis_cacat_terbanyak ?? null;
        $mesinBermasalah = $laporan->mesin_bermasalah ?? null;

        // kalau tabel laporan kosong atau nilainya null, hitung manual dari data_cacat
        if (!$jenisTerbanyak) {
            $jenisTerbanyak = DataCacat::with('jenisCacat')
                ->selectRaw('jenis_cacat_id, COUNT(*) as jumlah')
                ->groupBy('jenis_cacat_id')
                ->orderByDesc('jumlah')
                ->first()?->jenisCacat?->nama ?? 'Belum ada data';
        }

        if (!$mesinBermasalah) {
            $mesinBermasalah = DataCacat::selectRaw('mesin, COUNT(*) as jumlah')
                ->groupBy('mesin')
                ->orderByDesc('jumlah')
                ->first()?->mesin ?? 'Belum ada data';
        }

        $tanggalCacat = DataCacat::selectRaw('DATE(created_at) as tanggal')
        ->groupByRaw('DATE(created_at)')
        ->orderByRaw('DATE(created_at)')
        ->pluck('tanggal');
    
        $jumlahCacatPerHari = DataCacat::selectRaw('COUNT(*) as total')
        ->groupByRaw('DATE(created_at)')
        ->orderByRaw('DATE(created_at)')
        ->pluck('total');
    
$namaJenisCacat = JenisCacat::pluck('nama_jenis');
$jumlahPerJenis = JenisCacat::withCount('dataCacat')->pluck('data_cacat_count');

$namaMesin = DataCacat::select('lokasi_mesin')->distinct()->pluck('lokasi_mesin');
$kinerjaMesin = DataCacat::selectRaw('lokasi_mesin, COUNT(*) as total')
    ->groupBy('lokasi_mesin')
    ->orderByDesc('total')
    ->pluck('total');


return view('dashboard.index', compact(
    'user','totalCacat','totalJenis','totalUser','verifikasiValid','verifikasiBelum',
    'jenisTerbanyak','mesinBermasalah',
    'tanggalCacat','jumlahCacatPerHari','namaJenisCacat','jumlahPerJenis',
    'namaMesin','kinerjaMesin'
));

        return view('dashboard.index', compact(
            'user',
            'totalCacat', 
            'totalJenis', 
            'totalUser', 
            'verifikasiValid', 
            'verifikasiBelum', 
            'jenisTerbanyak', 
            'mesinBermasalah'
        ));
    }
}
