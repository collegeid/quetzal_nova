<?php

namespace App\Http\Controllers;

use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DataCacatController extends Controller
{
    public function index()
    {
        $dataCacat = DataCacat::with('jenisCacat', 'user', 'verifikasi')->latest()->get();
        $jenisCacat = JenisCacat::all();
        return view('data_cacat.index', compact('dataCacat', 'jenisCacat'));
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

            $jenis = JenisCacat::find($request->id_jenis);
            $validated['jenis_cacat'] = $jenis ? $jenis->nama_jenis : 'Tidak Dikenal';

            if ($request->hasFile('foto_bukti')) {
                $validated['foto_bukti'] = $request->file('foto_bukti')->store('bukti', 'public');
            }

            $validated['id_user'] = Auth::id();
            $validated['status_verifikasi'] = 0;

            $newData = DataCacat::create($validated);

            // Panggil Controller WhatsApp
            app(WhatsappNotificationController::class)->sendUpdateNotification($newData, 'Menambahkan');

            return redirect()->back()->with('success', 'Data cacat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $data = DataCacat::find($id);
        if (!$data) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        try {
            $role = Auth::user()->role;
            $statusInput = $request->input('status_verifikasi');

            // 1. Logika Khusus Petugas QC (Hanya Verifikasi)
            if ($role === 'petugas_qc') {
                $request->validate(['status_verifikasi' => 'required|integer|in:0,1,2,3']);
                app(VerifikasiController::class)->prosesVerifikasi($data, $statusInput);
            } 
            // 2. Logika Manager/Admin/Operator (Update Data + Verifikasi jika Manager)
            else {
                $validated = $request->validate([
                    'tanggal' => 'required|date',
                    'shift' => 'required|string',
                    'jenis_kain' => 'nullable|string',
                    'lokasi_mesin' => 'required|string',
                    'foto_bukti' => 'nullable|image|max:2048',
                    'id_jenis' => 'required|exists:jenis_cacat,id_jenis',
                ]);

                if ($request->hasFile('foto_bukti')) {
                    if ($data->foto_bukti) Storage::disk('public')->delete($data->foto_bukti);
                    $validated['foto_bukti'] = $request->file('foto_bukti')->store('bukti', 'public');
                }

                $data->update($validated);

                // Jika Manager/Admin juga mengubah status
                if (in_array($role, ['manager_produksi', 'super_admin']) && isset($statusInput)) {
                    app(VerifikasiController::class)->prosesVerifikasi($data, $statusInput);
                }
            }

            // Kirim Notifikasi setelah update selesai
            app(WhatsappNotificationController::class)->sendUpdateNotification($data, 'Memverifikasi/Memperbarui');

            return redirect()->back()->with('success', 'Data cacat berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = DataCacat::findOrFail($id);
            if ($data->foto_bukti) Storage::disk('public')->delete($data->foto_bukti);
            
            // Cleanup sesuai diagram
            $data->verifikasi()->delete();
            $data->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}