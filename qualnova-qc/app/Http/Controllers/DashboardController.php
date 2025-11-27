<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use App\Models\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{


    public function trendChart(Request $request)
    {
        $range = $request->range ?? 'day';
    
        $query = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
            ->where('verifikasi.valid', true);
    
        switch ($range) {
            case 'day':
                $query->selectRaw('DATE(data_cacat.created_at) as label, COUNT(*) as total')
                      ->groupByRaw('DATE(data_cacat.created_at)')
                      ->orderByRaw('DATE(data_cacat.created_at)');
                break;
                case 'week':
                    $query->selectRaw('YEAR(data_cacat.created_at) as year, WEEK(data_cacat.created_at, 1) as week, MIN(DATE(data_cacat.created_at)) as start_date, MAX(DATE(data_cacat.created_at)) as end_date, COUNT(*) as total')
                          ->groupByRaw('YEAR(data_cacat.created_at), WEEK(data_cacat.created_at, 1)')
                          ->orderByRaw('YEAR(data_cacat.created_at), WEEK(data_cacat.created_at, 1)');
                    break;                
            case 'month':
                $query->selectRaw('DATE_FORMAT(data_cacat.created_at, "%Y-%m") as label, COUNT(*) as total')
                      ->groupByRaw('DATE_FORMAT(data_cacat.created_at, "%Y-%m")')
                      ->orderByRaw('DATE_FORMAT(data_cacat.created_at, "%Y-%m")');
                break;
            case 'year':
                $query->selectRaw('YEAR(data_cacat.created_at) as label, COUNT(*) as total')
                      ->groupByRaw('YEAR(data_cacat.created_at)')
                      ->orderByRaw('YEAR(data_cacat.created_at)');
                break;
        }
    
        $data = $query->get();
    
        if($range === 'week') {
            $labels = $data->map(function($item) {
                $start = \Carbon\Carbon::parse($item->start_date)->format('d M');
                $end = \Carbon\Carbon::parse($item->end_date)->format('d M');
                return "$start - $end";
            });
        } else if($range === 'month') {
            $labels = $data->pluck('label')->map(fn($v) => \Carbon\Carbon::parse($v.'-01')->format('M Y'));
        } else if($range === 'year') {
            $labels = $data->pluck('label');
        } else { // day
            $labels = $data->pluck('label')->map(fn($v) => \Carbon\Carbon::parse($v)->format('d M Y'));
        }
        
        return response()->json([
            'labels' => $labels,
            'totals' => $data->pluck('total')
        ]);
        
    }
    


    public function statusSistemJson() {
        $status = $this->cekStatusSistem();
        return response()->json(['status' => $status]);
    }
    
    private function cekStatusSistem() {
        $status = 'Semua proses berjalan normal';
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            return 'Database tidak terkoneksi';
        }
    
        $connected = @fsockopen("www.google.com", 80);
        if (!$connected) return 'Tidak ada koneksi jaringan';
        fclose($connected);
    
        return $status;
    }
    
    // =============================================
// 🔮  F U N G S I   R E G R E S I   L O G I S T I K
// =============================================
private function prediksiRegresiLogistik()
{
    // 1️⃣ Ambil data training
    $dataset = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
        ->select('data_cacat.id_jenis', 'data_cacat.lokasi_mesin', 'verifikasi.valid')
        ->get();

    if ($dataset->count() < 3) {
        return collect(); // terlalu sedikit data
    }

    // 2️⃣ Fitur kategori → angka
    $jenisMap = JenisCacat::pluck('id_jenis')->flip()->map(fn($i) => $i + 1)->toArray();
    $mesinMap = DataCacat::pluck('lokasi_mesin')->unique()->flip()->map(fn($i) => $i + 1)->toArray();

    $training = collect();

    foreach ($dataset as $row) {
        $training->push([
            'x1' => $jenisMap[$row->id_jenis] ?? 0,
            'x2' => $mesinMap[$row->lokasi_mesin] ?? 0,
            'y'  => $row->valid ? 1 : 0
        ]);
    }

    // 3️⃣ Hitung bobot sederhana (average estimation)
    $b0 = $training->avg('y');      // intercept
    $b1 = $training->avg('x1');     // koef jenis
    $b2 = $training->avg('x2');     // koef mesin

    // 4️⃣ Fungsi sigmoid
    $sigmoid = function($z) {
        return 1 / (1 + exp(-$z));
    };

    // 5️⃣ Ambil data hari ini untuk prediksi
    $dataHariIni = DataCacat::whereDate('created_at', today())->get();

    $hasilPrediksi = collect();

    foreach ($dataHariIni as $row) {
        $x1 = $jenisMap[$row->id_jenis] ?? 0;
        $x2 = $mesinMap[$row->lokasi_mesin] ?? 0;

        $z = ($b1 * $x1) + ($b2 * $x2) + $b0;
        $prob = $sigmoid($z);

        $hasilPrediksi->push([
            'jenis_cacat' => $row->jenis_cacat,
            'mesin'       => $row->lokasi_mesin,
            'probabilitas' => round($prob * 100, 2) . '%'
        ]);
    }

    return $hasilPrediksi;
}


    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        if ($user->role === 'operator_produksi') {
            return view('dashboard.operator', compact('user'));
        }
    
        if (in_array($user->role, ['super_admin', 'manager_produksi', 'petugas_qc'])) {
    
        $totalCacat = DataCacat::count();
        $totalJenis = JenisCacat::count();
        $totalUser = User::count();
        $verifikasiValid = Verifikasi::where('valid', true)->count();
        $verifikasiBelum = Verifikasi::where('valid', false)->count();
    
        $laporan = Laporan::orderByDesc('total_cacat')->first();
    
            $jenisTerbanyak = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
                ->join('jenis_cacat', 'data_cacat.id_jenis', '=', 'jenis_cacat.id_jenis')
                ->where('verifikasi.valid', true)
                ->selectRaw('jenis_cacat.nama_jenis, COUNT(data_cacat.id_cacat) as total')
                ->groupBy('jenis_cacat.nama_jenis')
                ->orderByDesc('total')
                ->first()?->nama_jenis ?? 'Belum ada data';
                
            $mesinBermasalah = DataCacat::join('verifikasi', function($join) {
                $join->on('data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
                    ->where('verifikasi.valid', true);
                 })
                ->orderByDesc('verifikasi.tanggal_verifikasi')
                ->orderByDesc('data_cacat.created_at') // fallback jika tanggal verifikasi sama
                ->select('data_cacat.lokasi_mesin')
                ->first()?->lokasi_mesin ?? 'Belum ada data';

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
        
            $kinerjaMesinData = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
                ->where('verifikasi.valid', true)
                ->select('lokasi_mesin')
                ->selectRaw('COUNT(data_cacat.id_cacat) as total_cacat')
                ->groupBy('lokasi_mesin')
                ->get();

            // Hitung jumlah cacat valid per mesin
            $mesinCacat = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
                ->where('verifikasi.valid', true)
                ->select('lokasi_mesin')
                ->selectRaw('COUNT(*) as total_cacat')
                ->groupBy('lokasi_mesin')
                ->get();

            $maxCacat = $mesinCacat->max('total_cacat') ?: 1;

            $namaMesin = $mesinCacat->pluck('lokasi_mesin');
            $kinerjaMesin = $mesinCacat->map(function($item) use ($maxCacat) {
                return round(100 - ($item->total_cacat / $maxCacat) * 100, 2); 
            });

            $topMesin = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
                ->where('verifikasi.valid', true)
                ->select('lokasi_mesin')
                ->selectRaw('COUNT(*) as total_cacat')
                ->groupBy('lokasi_mesin')
                ->orderByDesc('total_cacat')
                ->limit(5)
                ->get();

        $namaMesin = $topMesin->pluck('lokasi_mesin');
        $jumlahCacatMesin = $topMesin->pluck('total_cacat');


        $topMesin = DataCacat::join('verifikasi', 'data_cacat.id_cacat', '=', 'verifikasi.id_cacat')
        ->where('verifikasi.valid', true)
        ->select('lokasi_mesin')
        ->selectRaw('COUNT(*) as total_cacat')
        ->groupBy('lokasi_mesin')
        ->orderByDesc('total_cacat')
        ->limit(5)
        ->get();


        $namaMesin = $topMesin->pluck('lokasi_mesin');
        $jumlahCacatMesin = $topMesin->pluck('total_cacat');

        $statusSistem = $this->cekStatusSistem();

        $bulanTersedia = DataCacat::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan')
        ->groupBy('bulan')
        ->orderByDesc('bulan')
        ->pluck('bulan');


            $statusRaw = DataCacat::select('status_verifikasi')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status_verifikasi')
            ->orderBy('status_verifikasi')
            ->get();

            $statusMapping = [
            0 => 'Belum Validasi',
            1 => 'Terverifikasi',
            2 => 'Revisi',
            3 => 'Rejected'
            ];

            $statusLabels = $statusRaw->map(fn($row) => $statusMapping[$row->status_verifikasi] ?? 'Tidak Diketahui');
            $statusTotals = $statusRaw->pluck('total');

            $prediksiRegresi = $this->prediksiRegresiLogistik();

            return view('dashboard.index', compact(
              'user',
                'totalCacat',
                'totalJenis',
                'totalUser',
                'verifikasiValid',
                'verifikasiBelum',
                'jenisTerbanyak',
                'mesinBermasalah',
                'namaMesin',
                'jumlahCacatMesin',
                'tanggalCacat',
                'namaJenisCacat',
                'jumlahPerJenis',
                'kinerjaMesin',
                'jumlahCacatPerHari',
                'statusSistem',
                'bulanTersedia',
                'statusLabels',
                'statusTotals',
                'prediksiRegresi'
            ));

    }
    return abort(403, 'Akses ditolak untuk role ini.');

}
}
