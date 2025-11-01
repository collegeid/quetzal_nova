<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function downloadPdf(Request $request)
    {
        // Ambil bulan dari query ?month=YYYY-MM, default bulan ini
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // ===== Ambil data utama (filter bulan) =====
        $totalCacat = DataCacat::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalJenis = JenisCacat::count();
        $totalUser = User::count();
        $verifikasiValid = Verifikasi::where('valid', true)
            ->whereBetween('tanggal_verifikasi', [$startDate, $endDate])
            ->count();
        $verifikasiBelum = Verifikasi::where('valid', false)
            ->whereBetween('tanggal_verifikasi', [$startDate, $endDate])
            ->count();

        // ===== Jenis cacat terbanyak bulan ini =====
        $jenisTerbanyak = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
            ->join('jenis_cacat', 'data_cacat.id_jenis', '=', 'jenis_cacat.id_jenis')
            ->where('verifikasi.valid', true)
            ->whereBetween('data_cacat.created_at', [$startDate, $endDate])
            ->selectRaw('jenis_cacat.nama_jenis, COUNT(data_cacat.id_cacat) as total')
            ->groupBy('jenis_cacat.nama_jenis')
            ->orderByDesc('total')
            ->first()?->nama_jenis ?? 'Belum ada data';

        // ===== Top 5 mesin rusak bulan ini =====
        $topMesin = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
            ->where('verifikasi.valid', true)
            ->whereBetween('data_cacat.created_at', [$startDate, $endDate])
            ->select('lokasi_mesin')
            ->selectRaw('COUNT(*) as total_cacat')
            ->groupBy('lokasi_mesin')
            ->orderByDesc('total_cacat')
            ->limit(5)
            ->get();

        // ===== Distribusi jenis cacat bulan ini =====
        $distribusiJenis = JenisCacat::withCount(['dataCacat as jumlah_cacat' => function($q) use ($startDate, $endDate) {
            $q->join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
              ->where('verifikasi.valid', true)
              ->whereBetween('data_cacat.created_at', [$startDate, $endDate]);
        }])->get();

        // ===== Mesin bermasalah terbaru bulan ini =====
        $mesinBermasalah = DataCacat::join('verifikasi', function($join) {
                $join->on('data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
                     ->where('verifikasi.valid', true);
            })
            ->whereBetween('data_cacat.created_at', [$startDate, $endDate])
            ->orderByDesc('verifikasi.tanggal_verifikasi')
            ->orderByDesc('data_cacat.created_at')
            ->select('data_cacat.lokasi_mesin')
            ->first()?->lokasi_mesin ?? 'Belum ada data';

        // ===== Status sistem =====
        $statusSistem = $this->cekStatusSistem();

        // ===== Rekap per tanggal =====
        $rekapPerTanggal = DataCacat::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();

        // ===== Generate HTML PDF =====
        $html = '<h2 style="text-align:center;">Laporan Data Cacat</h2>';
        $html .= '<p>Periode: '.$startDate->format('d M Y').' s/d '.$endDate->format('d M Y').'</p>';
        $html .= '<p>Status Sistem: '.$statusSistem.'</p>';

        $html .= '<h3>Ringkasan</h3>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr><th>Total Data Cacat</th><td>'.$totalCacat.'</td></tr>
            <tr><th>Total Jenis Cacat</th><td>'.$totalJenis.'</td></tr>
            <tr><th>Total User</th><td>'.$totalUser.'</td></tr>
            <tr><th>Verifikasi Valid</th><td>'.$verifikasiValid.'</td></tr>
            <tr><th>Verifikasi Belum Valid</th><td>'.$verifikasiBelum.'</td></tr>
            <tr><th>Jenis Cacat Terbanyak</th><td>'.$jenisTerbanyak.'</td></tr>
            <tr><th>Mesin Bermasalah Terbaru</th><td>'.$mesinBermasalah.'</td></tr>
        </table>';

        // Distribusi jenis cacat
        $html .= '<h3>Distribusi Jenis Cacat</h3>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr><th>Jenis Cacat</th><th>Jumlah</th></tr>';
        foreach($distribusiJenis as $item) {
            $html .= '<tr><td>'.$item->nama_jenis.'</td><td>'.$item->jumlah_cacat.'</td></tr>';
        }
        $html .= '</table>';

      // Top 5 mesin rusak
        $html .= '<h3>Top 5 Mesin Rusak</h3>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr><th>Mesin</th><th>Total Cacat</th></tr>';
        foreach($topMesin as $mesin) {
            $html .= '<tr><td>'.$mesin->lokasi_mesin.'</td><td>'.$mesin->total_cacat.'</td></tr>';
        }
        $html .= '</table>';

        // Page break sebelum rekap per tanggal
        $html .= '<div style="page-break-before: always;"></div>';

        // Rekap per tanggal
        $html .= '<h3>Rekap Per Tanggal</h3>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr><th>Tanggal</th><th>Total Cacat</th></tr>';
        foreach($rekapPerTanggal as $row) {
            $html .= '<tr><td>'.Carbon::parse($row->tanggal)->format('d M Y').'</td><td>'.$row->total.'</td></tr>';
        }
        $html .= '</table>';

        // ===== Generate & Download PDF =====
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('laporan_'.$month.'_'.Carbon::now()->format('Ymd_His').'.pdf');
    }

    private function cekStatusSistem() {
        $status = '✅ Semua proses berjalan normal';
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            return '❌ Database tidak terkoneksi';
        }
        $connected = @fsockopen("www.google.com", 80);
        if (!$connected) return '❌ Tidak ada koneksi jaringan';
        fclose($connected);
        return $status;
    }
}
