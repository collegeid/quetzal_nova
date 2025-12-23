<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use App\Models\Laporan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function downloadPdf(Request $request)
    {
        // 1. Sinkronisasi Periode & Waktu (Sesuai Filter Dashboard)
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // 2. Mengambil Statistik Dasar (8 Kartu Utama Dashboard)
        $totalCacat = DataCacat::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalJenis = JenisCacat::count();
        $totalUser = User::count();
        $verifikasiValid = Verifikasi::where('valid', true)
            ->whereBetween('tanggal_verifikasi', [$startDate, $endDate])
            ->count();
        $verifikasiBelum = DataCacat::where('status_verifikasi', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $statusSistem = $this->cekStatusSistem();

        // Insight: Cacat Terbanyak (Top Issues)
        $jenisTerbanyak = DataCacat::join('jenis_cacat', 'data_cacat.id_jenis', '=', 'jenis_cacat.id_jenis')
            ->whereBetween('data_cacat.created_at', [$startDate, $endDate])
            ->selectRaw('jenis_cacat.nama_jenis, COUNT(data_cacat.id_cacat) as total')
            ->groupBy('jenis_cacat.nama_jenis')
            ->orderByDesc('total')
            ->first()?->nama_jenis ?? 'Belum ada data';

        // Insight: Mesin Bermasalah Terbaru (Latest Machine)
        $mesinBermasalah = DataCacat::whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->select('lokasi_mesin')
            ->first()?->lokasi_mesin ?? 'Belum ada data';

        // 3. Analisis Distribusi & Chart Data (Dikonversi ke Tabel PDF)
        $distribusiJenis = JenisCacat::withCount(['dataCacat as jumlah_cacat' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])->orderByDesc('jumlah_cacat')->get();

        $topMesin = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
            ->where('verifikasi.valid', true)
            ->whereBetween('data_cacat.created_at', [$startDate, $endDate])
            ->select('lokasi_mesin', DB::raw('COUNT(*) as total_cacat'))
            ->groupBy('lokasi_mesin')
            ->orderByDesc('total_cacat')
            ->limit(5)->get();

        $statusRaw = DataCacat::whereBetween('created_at', [$startDate, $endDate])
            ->select('status_verifikasi', DB::raw('COUNT(*) AS total'))
            ->groupBy('status_verifikasi')->get();
        
        $statusMapping = [0 => 'Belum Validasi', 1 => 'Terverifikasi', 2 => 'Revisi', 3 => 'Rejected'];

        // 4. Integrasi AI: Jalankan Regresi Logistik
        $prediksiRegresi = $this->prediksiRegresiLogistik();

        // 5. Rekapitulasi Harian (Log)
        $rekapPerTanggal = DataCacat::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();

        // 6. Rendering HTML dengan CSS Terintegrasi (Indigo Theme)
        $html = '
        <html>
        <head>
            <style>
                @page { margin: 80px 40px; }
                body { font-family: "Helvetica", Arial, sans-serif; color: #1e293b; line-height: 1.5; font-size: 11px; }
                .header { position: fixed; top: -60px; left: 0; right: 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
                .footer { position: fixed; bottom: -40px; left: 0; right: 0; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px; }
                .brand { font-size: 18px; font-weight: 900; font-style: italic; }
                .brand span { color: #4f46e5; }
                .title-container { text-align: center; margin-bottom: 30px; }
                .report-title { font-size: 22px; font-weight: 900; text-transform: uppercase; italic; margin: 0; }
                
                /* Grid 4 Kolom untuk Statistik */
                .stats-table { width: 100%; border-collapse: separate; border-spacing: 5px; margin-bottom: 20px; }
                .stat-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; border-radius: 12px; text-align: center; }
                .stat-label { font-size: 7px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 3px; }
                .stat-value { font-size: 14px; font-weight: 900; color: #1e293b; display: block; }
                
                /* AI Box */
                .ai-container { background: #fff1f2; border: 1px solid #fecdd3; border-radius: 15px; padding: 15px; margin-bottom: 25px; }
                .ai-header { color: #e11d48; font-weight: 900; font-size: 10px; margin-bottom: 10px; text-transform: uppercase; }

                /* Tables */
                .section-title { font-size: 10px; font-weight: 900; text-transform: uppercase; border-left: 4px solid #4f46e5; padding-left: 10px; margin: 20px 0 10px 0; }
                table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                table.data-table th { background: #f8fafc; padding: 10px; text-align: left; font-size: 8px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; color: #64748b; }
                table.data-table td { padding: 10px; border-bottom: 1px solid #f1f5f9; font-weight: 500; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="brand">QUAL <span>NOVA</span></div>
            </div>

            <div class="footer">
                Laporan Analytics Qual Nova — Dikembangkan oleh Quetzal Team | Halaman {PAGENO}
            </div>

            <div class="title-container">
                <p style="color: #4f46e5; font-weight: 900; letter-spacing: 2px; font-size: 9px;">MONITORING REPORT</p>
                <h1 class="report-title">Analisis Performa QC</h1>
                <p style="font-weight: bold; font-size: 12px; color: #64748b;">Periode: '.$startDate->format('F Y').'</p>
            </div>

            <div class="section-title">Dashboard Statistics</div>
            <table class="stats-table">
                <tr>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Total Cacat</span><span class="stat-value">'.$totalCacat.'</span></div></td>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Jenis Cacat</span><span class="stat-value">'.$totalJenis.'</span></div></td>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Total User</span><span class="stat-value">'.$totalUser.'</span></div></td>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Validasi Valid</span><span class="stat-value" style="color:#10b981">'.$verifikasiValid.'</span></div></td>
                </tr>
                <tr>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Pending</span><span class="stat-value" style="color:#f59e0b">'.$verifikasiBelum.'</span></div></td>
                    <td width="25%"><div class="stat-box"><span class="stat-label">System Status</span><span class="stat-value" style="font-size:8px; color:#10b981">'.$statusSistem.'</span></div></td>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Top Issues</span><span class="stat-value" style="font-size:9px;">'.$jenisTerbanyak.'</span></div></td>
                    <td width="25%"><div class="stat-box"><span class="stat-label">Latest Machine</span><span class="stat-value">'.$mesinBermasalah.'</span></div></td>
                </tr>
            </table>

            <div class="section-title">Analisis Prediksi (AI)</div>
            <div class="ai-container">
                <div class="ai-header">Prediksi Probabilitas Validasi (Logistic Regression)</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kategori Cacat</th>
                            <th>Unit Mesin</th>
                            <th style="text-align: right;">Probabilitas</th>
                        </tr>
                    </thead>
                    <tbody>';
                    if($prediksiRegresi->isEmpty()){
                        $html .= '<tr><td colspan="3" style="text-align:center; color:#94a3b8">Data histori verifikasi belum cukup untuk menjalankan analisis AI</td></tr>';
                    } else {
                        foreach($prediksiRegresi as $p) {
                            $html .= '<tr>
                                <td style="font-weight:900; font-style:italic;">'.$p['jenis_cacat'].'</td>
                                <td>UNIT-'.$p['mesin'].'</td>
                                <td style="text-align: right; color:#4f46e5; font-weight:900;">'.$p['probabilitas'].'</td>
                            </tr>';
                        }
                    }
                $html .= '</tbody>
                </table>
            </div>

            <div class="section-title">Distribusi Sumber Kerusakan</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="75%">Klasifikasi Cacat</th>
                        <th width="25%" style="text-align: right;">Jumlah Temuan</th>
                    </tr>
                </thead>
                <tbody>';
                foreach($distribusiJenis as $item) {
                    $html .= '<tr>
                        <td>'.strtoupper($item->nama_jenis).'</td>
                        <td style="text-align: right; font-weight:bold;">'.$item->jumlah_cacat.' pt</td>
                    </tr>';
                }
            $html .= '</tbody>
            </table>

            <div style="page-break-before: always;"></div>

            <div class="section-title">Top Unit Mesin Bermasalah</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Identitas Mesin</th>
                        <th style="text-align: right;">Total Akumulasi Cacat Valid</th>
                    </tr>
                </thead>
                <tbody>';
                foreach($topMesin as $mesin) {
                    $html .= '<tr>
                        <td>MESIN-'.strtoupper($mesin->lokasi_mesin).'</td>
                        <td style="text-align: right; color:#e11d48; font-weight:bold;">'.$mesin->total_cacat.' pt</td>
                    </tr>';
                }
            $html .= '</tbody>
            </table>

            <div class="section-title">Status Data Operasional</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Status Laporan</th>
                        <th style="text-align: right;">Total Data</th>
                    </tr>
                </thead>
                <tbody>';
                foreach($statusRaw as $row) {
                    $html .= '<tr>
                        <td>'.($statusMapping[$row->status_verifikasi] ?? 'Lainnya').'</td>
                        <td style="text-align: right; font-weight:bold;">'.$row->total.'</td>
                    </tr>';
                }
            $html .= '</tbody>
            </table>

            <div class="section-title">Rekapitulasi Tren Harian</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal Produksi</th>
                        <th style="text-align: right;">Total Laporan Cacat</th>
                    </tr>
                </thead>
                <tbody>';
                foreach($rekapPerTanggal as $row) {
                    $html .= '<tr>
                        <td>'.Carbon::parse($row->tanggal)->format('d F Y').'</td>
                        <td style="text-align: right;">'.$row->total.' Laporan</td>
                    </tr>';
                }
            $html .= '</tbody>
            </table>

        </body>
        </html>';

        // 7. Eksekusi PDF
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('QUALNOVA_ANALYTICS_REPORT_'.$month.'.pdf');
    }

    private function prediksiRegresiLogistik()
    {
        $dataset = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
            ->select('data_cacat.id_jenis', 'data_cacat.lokasi_mesin', 'verifikasi.valid')
            ->get();

        if ($dataset->count() < 2) return collect();

        $jenisMap = JenisCacat::pluck('id_jenis')->flip()->toArray();
        $mesinMap = DataCacat::distinct()->pluck('lokasi_mesin')->flip()->toArray();

        $samples = [];
        foreach ($dataset as $row) {
            $samples[] = [
                'x1' => (isset($jenisMap[$row->id_jenis]) ? $jenisMap[$row->id_jenis] : 0) / (count($jenisMap) ?: 1),
                'x2' => (isset($mesinMap[$row->lokasi_mesin]) ? $mesinMap[$row->lokasi_mesin] : 0) / (count($mesinMap) ?: 1),
                'y'  => (int)$row->valid
            ];
        }

        $w1 = 0.5; $w2 = 0.5; $b = 0.1; $lr = 0.1;
        for ($i = 0; $i < 50; $i++) {
            foreach ($samples as $s) {
                $z = ($w1 * $s['x1']) + ($w2 * $s['x2']) + $b;
                $prediction = 1 / (1 + exp(-max(min($z, 20), -20)));
                $error = $s['y'] - $prediction;
                $w1 += $lr * $error * $s['x1'];
                $w2 += $lr * $error * $s['x2'];
                $b  += $lr * $error;
            }
        }

        $dataTarget = DataCacat::with('jenisCacat')->where('status_verifikasi', 0)->latest()->take(5)->get();
        $hasilPrediksi = collect();
        foreach ($dataTarget as $row) {
            $x1 = (isset($jenisMap[$row->id_jenis]) ? $jenisMap[$row->id_jenis] : 0) / (count($jenisMap) ?: 1);
            $x2 = (isset($mesinMap[$row->lokasi_mesin]) ? $mesinMap[$row->lokasi_mesin] : 0) / (count($mesinMap) ?: 1);
            $z = ($w1 * $x1) + ($w2 * $x2) + $b;
            $prob = 1 / (1 + exp(-max(min($z, 20), -20)));
            $hasilPrediksi->push([
                'jenis_cacat' => $row->jenisCacat->nama_jenis ?? 'Unknown',
                'mesin'       => $row->lokasi_mesin,
                'probabilitas' => round($prob * 100, 2) . '%'
            ]);
        }
        return $hasilPrediksi;
    }

    private function cekStatusSistem() {
        try {
            DB::connection()->getPdo();
            return 'SEMUA PROSES BERJALAN NORMAL';
        } catch (\Exception $e) {
            return 'DATABASE TIDAK TERKONEKSI';
        }
    }
}