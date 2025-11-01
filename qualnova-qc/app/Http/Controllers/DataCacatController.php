<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laporan;
use App\Models\DataCacat;
use App\Models\JenisCacat;
use App\Models\Verifikasi;
use App\Jobs\SendWhatsappJob;
use App\Models\WhatsappNotification;
use App\Jobs\SendWhatsappMessageJob;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class DataCacatController extends Controller
{

    protected function sendWhatsappUpdateNotification(DataCacat $data, string $actionBy)
    {
        $authUser = Auth::user();
    
        if (!$authUser) {
            Log::warning('⚠️ Auth user belum login, notifikasi dibatalkan', ['id_cacat' => $data->id_cacat]);
            return;
        }
        $tanggal = Carbon::parse($data->tanggal); 
        $now = Carbon::now(); 
        
        $tanggalWaktu = $tanggal->setTime($now->hour, $now->minute, $now->second)
                                ->format('d/m/Y H:i:s');
        Log::info('🚀 Memulai sendWhatsappUpdateNotification', [
            'id_cacat' => $data->id_cacat,
            'actionBy' => $actionBy,
            'auth_user' => $authUser->id,
            'auth_user_whatsapp' => $authUser->whatsapp,
        ]);
    
        if ($authUser->whatsapp) {
            $pesanPengupdate = "Halo {$authUser->name},\n\n".
                "Kamu telah {$actionBy} data cacat dengan detail berikut:\n".
                "ID Data: {$data->id_cacat}\n".
                "Tanggal & Jam: {$tanggalWaktu}\n".
                "Shift: {$data->shift}\n".
                "Jenis Kain: {$data->jenis_kain}\n".
                "Jenis Cacat: {$data->jenis_cacat}\n".
                "Lokasi Mesin: {$data->lokasi_mesin}\n";
    
            try {
                $notif = WhatsappNotification::create([
                    'id_cacat' => $data->id_cacat,
                    'nomor_tujuan' => $authUser->whatsapp,
                    'pesan' => $pesanPengupdate,
                    'status' => 'pending',
                ]);
    
                SendWhatsappMessageJob::dispatch($notif);
                Log::info('Notifikasi auth user berhasil dibuat & di-queue', [
                    'notif_id' => $notif->id_notif,
                    'nomor_tujuan' => $authUser->whatsapp
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal membuat notifikasi auth user', [
                    'error' => $e->getMessage(),
                    'id_cacat' => $data->id_cacat,
                    'nomor_tujuan' => $authUser->whatsapp
                ]);
            }
        } else {
            Log::warning('⚠️ Auth user tidak punya nomor WA, notifikasi dilewati', ['user_id' => $authUser->id]);
        }
    
        $recipients = User::whereIn('role', ['manager_produksi', 'super_admin', 'petugas_qc'])
            ->where('id', '!=', $authUser->id)
            ->where(function($query) {
                $query->where('whatsapp', 'like', '62%')
                      ->orWhere('whatsapp', 'like', '08%')
                      ->orWhere('whatsapp', 'like', '+62%');
            })
            ->get();
    
        Log::info('📢 Jumlah recipients ditemukan', ['count' => $recipients->count()]);
    
        foreach ($recipients as $user) {
            if (!$user->whatsapp) {
                Log::warning('⚠️ Recipient tidak punya nomor WA, dilewati', ['user_id' => $user->id]);
                continue;
            }
    
            $pesanRecipient = "Halo {$user->name},\n\n".
                "{$authUser->name} telah {$actionBy} data cacat dengan detail berikut:\n".
                "ID Data: {$data->id_cacat}\n".
                "Tanggal & Jam: {$tanggalWaktu}\n".
                "Shift: {$data->shift}\n".
                "Jenis Kain: {$data->jenis_kain}\n".
                "Jenis Cacat: {$data->jenis_cacat}\n".
                "Lokasi Mesin: {$data->lokasi_mesin}\n";
    
            try {
                $existing = WhatsappNotification::where('nomor_tujuan', $user->whatsapp)
                    ->where('id_cacat', $data->id_cacat)
                    ->latest()
                    ->first();
    
                if (!$existing || $actionBy === 'Memverifikasi') {
                    $notif = WhatsappNotification::create([
                        'id_cacat' => $data->id_cacat,
                        'nomor_tujuan' => $user->whatsapp,
                        'pesan' => $pesanRecipient,
                        'status' => 'pending',
                    ]);
    
                    SendWhatsappMessageJob::dispatch($notif);
    
                    Log::info('✅ Notifikasi recipient berhasil dibuat & di-queue', [
                        'notif_id' => $notif->id_notif,
                        'nomor_tujuan' => $user->whatsapp
                    ]);
                } else {
                    Log::info('ℹ️ Notifikasi sudah ada, tidak dibuat ulang', ['user_id' => $user->id]);
                }
            } catch (\Exception $e) {
                Log::error('❌ Gagal simpan notifikasi recipient', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    
        Log::info('🏁 sendWhatsappUpdateNotification selesai', ['id_cacat' => $data->id_cacat]);
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
                'status_verifikasi' => 'nullable|boolean',
            ]);
    
            $jenis = JenisCacat::find($request->id_jenis);
            $validated['jenis_cacat'] = $jenis ? $jenis->nama_jenis : 'Tidak Dikenal';
    
            if ($request->hasFile('foto_bukti')) {
                $validated['foto_bukti'] = $request->file('foto_bukti')->store('bukti', 'public');
            }
    
            $validated['id_user'] = Auth::id();
            $validated['status_verifikasi'] = 0;
    
            Log::info('📝 Store: validated payload (before create)', $validated);
            $newData = DataCacat::create($validated);

            try {
                $this->sendWhatsappUpdateNotification($newData, 'Menambahkan');
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA notification di store', ['error' => $e->getMessage()]);
            }
    
            return redirect()->back()->with('success', 'Data cacat berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('❌ Terjadi kesalahan saat store DataCacat', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
        $data = DataCacat::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    
        try {
            $role = Auth::user()->role;
            $sendWhatsapp = false;
    
            if ($role === 'petugas_qc') {
                $validated = $request->validate([
                    'status_verifikasi' => 'required|boolean',
                ]);
    
                $data->update(['status_verifikasi' => $validated['status_verifikasi']]);
    
                $verifikasi = Verifikasi::firstOrNew(['id_cacat' => $data->id_cacat]);
                $verifikasi->qc_id = Auth::id();
                $verifikasi->tanggal_verifikasi = Carbon::now()->format('Y-m-d H:i:s');
                $verifikasi->valid = 1;
                $verifikasi->save();
    
                $sendWhatsapp = true;
            } else {
                $validated = $request->validate([
                    'tanggal' => 'required|date',
                    'shift' => 'required|string',
                    'jenis_kain' => 'nullable|string',
                    'lokasi_mesin' => 'required|string',
                    'foto_bukti' => 'nullable|image|max:2048',
                    'id_jenis' => 'required|exists:jenis_cacat,id_jenis',
                    'status_verifikasi' => 'nullable|boolean',
                ]);
    
                $jenis = JenisCacat::find($request->id_jenis);
                $validated['jenis_cacat'] = $jenis ? $jenis->nama_jenis : 'Tidak Dikenal';
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d');
    
                if ($request->hasFile('foto_bukti')) {
                    if ($data->foto_bukti) {
                        Storage::disk('public')->delete($data->foto_bukti);
                    }
                    $validated['foto_bukti'] = $request->file('foto_bukti')->store('bukti', 'public');
                }
    
                if (in_array($role, ['manager_produksi', 'super_admin']) && isset($validated['status_verifikasi'])) {
                    $data->status_verifikasi = $validated['status_verifikasi'];
    
                    $verifikasi = Verifikasi::firstOrNew(['id_cacat' => $data->id_cacat]);
                    $verifikasi->qc_id = Auth::id();
                    $verifikasi->tanggal_verifikasi = Carbon::now()->format('Y-m-d H:i:s');
                    $verifikasi->valid = 1;
                    $verifikasi->save();
                }
    
                $data->update($validated);
                $sendWhatsapp = true;
            }
    
            if ($sendWhatsapp) {
                try {
                    $this->sendWhatsappUpdateNotification($data, 'Memverifikasi');
                } catch (\Exception $e) {
                    Log::error('Gagal kirim WA notification di update', ['error' => $e->getMessage()]);
                }
            }
    
            return redirect()->back()->with('success', 'Data cacat berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update data', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function destroy($id)
    {
        try {
            $data = DataCacat::findOrFail($id);
    
            if ($data->foto_bukti) {
                Storage::disk('public')->delete($data->foto_bukti);
            }
    
            Verifikasi::where('id_cacat', $data->id_cacat)->delete();
            Laporan::where('periode', $data->tanggal)->delete();
            $data->delete();
    
            return redirect()->back()->with('success', 'Data cacat beserta verifikasi dan laporan terkait berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal hapus data', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $dataCacat = DataCacat::with('jenisCacat', 'user', 'verifikasi')->latest()->get();
        $jenisCacat = JenisCacat::all();

        return view('data_cacat.index', compact('dataCacat', 'jenisCacat'));
    }
}
