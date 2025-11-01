<?php

namespace App\Http\Controllers;

use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use App\Models\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class DataCacatController extends Controller
{
    public function index()
    {
        $dataCacat = DataCacat::with('jenisCacat', 'user')->latest()->get();
        $jenisCacat = JenisCacat::all();

        return view('qc.index', compact('dataCacat', 'jenisCacat'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'shift' => 'required|string',
                'jenis_kain' => 'nullable|string',
                'lokasi_mesin' => 'required|string',
                'foto_bukti' => 'nullable|image|max:2048',
                'id_jenis' => 'required|exists:jenis_cacat,id_jenis',
            ]);
    
            // Tambahkan nama jenis cacat
            $jenis = JenisCacat::find($request->id_jenis);
            $validated['jenis_cacat'] = $jenis ? $jenis->nama_jenis : 'Tidak Dikenal';
    
            // Upload foto jika ada
            if ($request->hasFile('foto_bukti')) {
                $validated['foto_bukti'] = $request->file('foto_bukti')->store('bukti', 'public');
                Log::info('📸 Foto berhasil di-upload', ['path' => $validated['foto_bukti']]);
            }
    
            // Set user
            $validated['id_user'] = Auth::id();
    
            // Set status_verifikasi default QC
            $validated['status_verifikasi'] = 0;
    
            // Simpan data cacat
            $newData = DataCacat::create($validated);
    
            // 🔹 Catat ke tabel verifikasi dengan valid 0
            Verifikasi::create([
                'id_cacat' => $newData->id_cacat,
                'qc_id' => Auth::id(),
                'tanggal_verifikasi' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'valid' => 0, // default
                'catatan' => $request->catatan ?? null,
            ]);
    
            return redirect()->back()->with('success', 'Data cacat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
        Log::info('🛠️ Update request diterima', [
            'id' => $id,
            'data' => $request->all()
        ]);
    
        $data = DataCacat::find($id);
    
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    
        try {
            $role = Auth::user()->role;
    
            if ($role === 'Verifikator') {
                // ✅ Verifikator hanya update status_verifikasi
                $validated = $request->validate([
                    'status_verifikasi' => 'required|boolean',
                ]);
    
                $data->update(['status_verifikasi' => $validated['status_verifikasi']]);
    
                // Catat/update tabel verifikasi
                $verifikasi = Verifikasi::firstOrNew(['id_cacat' => $data->id_cacat]);
                $verifikasi->qc_id = Auth::id();
                $verifikasi->tanggal_verifikasi = Carbon::now()->format('Y-m-d H:i:s');
                $verifikasi->valid = $validated['status_verifikasi'];
                $verifikasi->catatan = $request->catatan ?? $verifikasi->catatan;
                $verifikasi->save();
    
                return redirect()->back()->with('success', 'Status berhasil diperbarui dan tercatat di verifikasi!');
            }
    
            // QC atau Admin / Super Admin
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'shift' => 'required|string',
                'jenis_kain' => 'nullable|string',
                'lokasi_mesin' => 'required|string',
                'foto_bukti' => 'nullable|image|max:2048',
                'id_jenis' => 'required|exists:jenis_cacat,id_jenis',
                'status_verifikasi' => 'nullable|boolean', // hanya untuk admin/super_admin
            ]);
    
            $jenis = JenisCacat::find($request->id_jenis);
            $validated['jenis_cacat'] = $jenis ? $jenis->nama_jenis : 'Tidak Dikenal';
            $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d');
    
            // Upload foto
            if ($request->hasFile('foto_bukti')) {
                if ($data->foto_bukti) {
                    Storage::disk('public')->delete($data->foto_bukti);
                }
                $validated['foto_bukti'] = $request->file('foto_bukti')->store('bukti', 'public');
            }
    
            // Admin / Super Admin bisa update status juga
            if (in_array($role, ['admin', 'super_admin']) && isset($validated['status_verifikasi'])) {
                $data->status_verifikasi = $validated['status_verifikasi'];
    
                // Catat/update tabel verifikasi
                $verifikasi = Verifikasi::firstOrNew(['id_cacat' => $data->id_cacat]);
                $verifikasi->qc_id = Auth::id();
                $verifikasi->tanggal_verifikasi = Carbon::now()->format('Y-m-d H:i:s');
                $verifikasi->valid = $validated['status_verifikasi'];
                $verifikasi->catatan = $request->catatan ?? $verifikasi->catatan;
                $verifikasi->save();
            }
    
            // Update semua field
            $data->update($validated);
    
            return redirect()->back()->with('success', 'Data cacat berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('❌ Gagal update data', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function destroy($id)
    {
        try {
            $data = DataCacat::findOrFail($id);
    
            // Hapus file foto jika ada
            if ($data->foto_bukti) {
                Storage::disk('public')->delete($data->foto_bukti);
            }
    
            // Hapus record verifikasi terkait
            Verifikasi::where('id_cacat', $data->id_cacat)->delete();
    
            // Hapus record laporan terkait (jika ada laporan yang spesifik untuk data ini)
            // Contoh: hapus laporan berdasarkan total_cacat yang termasuk data ini
            // Jika laporan dibuat secara agregat, biasanya tidak dihapus, tapi update agregasi saja
            // Lanjutkan sesuai kebutuhan:
            Laporan::where('periode', $data->tanggal)->delete();
    
            // Hapus data cacat
            $data->delete();
    
            return redirect()->back()->with('success', 'Data cacat beserta verifikasi dan laporan terkait berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('❌ Gagal hapus data', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
