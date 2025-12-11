<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use App\Models\Laporan;
use App\Models\Mesin;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistik dasar
        $totalCacat = DataCacat::count();
        $totalJenis = JenisCacat::count();
        $totalUser = User::count();
        $verifikasiValid = Verifikasi::where('valid', true)->count();
        $verifikasiBelum = Verifikasi::where('valid', false)->count();

        // Ambil laporan terakhir
        $laporan = Laporan::orderByDesc('total_cacat')->first();

        $jenisTerbanyak = $laporan->jenis_cacat_terbanyak ?? null;
        $mesinBermasalah = $laporan->mesin_bermasalah ?? null;

        // Hitung manual jika laporan kosong
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

        // ================================
        // 🔹 Data untuk Chart Dashboard
        // ================================

        // Grafik tren jumlah cacat per hari
        $tanggalCacat = DataCacat::selectRaw('DATE(created_at) as tanggal')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('tanggal');

        $jumlahCacatPerHari = DataCacat::selectRaw('COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->pluck('total');

        // Grafik distribusi jenis cacat
        $namaJenisCacat = JenisCacat::pluck('nama_jenis');
        $jumlahPerJenis = JenisCacat::withCount('dataCacat')->pluck('data_cacat_count');

        // Grafik performa mesin
        $namaMesin = Mesin::pluck('nama');
        $kinerjaMesin = Mesin::pluck('kinerja'); // kolom kinerja (%) di tabel mesin

        // Kirim semua data ke view
        return view('dashboard.index', compact(
            'user',
            'totalCacat',
            'totalJenis',
            'totalUser',
            'verifikasiValid',
            'verifikasiBelum',
            'jenisTerbanyak',
            'mesinBermasalah',
            'tanggalCacat',
            'jumlahCacatPerHari',
            'namaJenisCacat',
            'jumlahPerJenis',
            'namaMesin',
            'kinerjaMesin'
        ));
    }
}
