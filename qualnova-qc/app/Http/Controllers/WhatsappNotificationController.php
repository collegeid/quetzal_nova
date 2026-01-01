<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataCacat;
use App\Models\WhatsappNotification;
use App\Jobs\SendWhatsappMessageJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsappNotificationController extends Controller
{
    /**
     * Konsep: Mengirim notifikasi ke SELURUH user yang memiliki nomor WhatsApp,
     * dengan deteksi otomatis template pesan (Diri Sendiri vs Orang Lain).
     */
    public function sendUpdateNotification(DataCacat $data, string $actionBy)
    {
        $actor = Auth::user(); // User yang sedang melakukan aksi
        if (!$actor) return;

        // Persiapan Data Waktu
        $tanggal = Carbon::parse($data->tanggal);
        $now = Carbon::now();
        $tanggalWaktu = $tanggal->setTime($now->hour, $now->minute, $now->second)->format('d/m/Y H:i:s');

        // Ambil detail status (Label & Note)
        $statusInfo = $this->getStatusDetail($data->status_verifikasi);
        
        // 1. Ambil SEMUA user yang terdaftar dan punya nomor WhatsApp
        // Anda bisa memfilter role di sini jika tidak ingin dikirim ke semua user
        $allUsers = User::whereNotNull('whatsapp')
                        ->where('whatsapp', '!=', '')
                        ->get();

        foreach ($allUsers as $recipient) {
            
            // LOGIKA DETEKSI OTOMATIS:
            // Jika ID recipient sama dengan ID Aktor, gunakan kata "Kamu"
            if ($recipient->id === $actor->id) {
                $pesan = "Halo *{$recipient->name}*,\n\n" .
                    "Kamu telah *{$actionBy}* data cacat dengan detail berikut:\n\n" .
                    "🆔 ID Data: #{$data->id_cacat}\n" .
                    "📅 Waktu: {$tanggalWaktu}\n" .
                    "🧵 Kain: {$data->jenis_kain}\n" .
                    "⚠️ Cacat: {$data->jenis_cacat}\n" .
                    "⚙️ Mesin: {$data->lokasi_mesin}\n\n" .
                    "--------------------------------\n" .
                    "📌 *STATUS TERBARU: {$statusInfo['label']}*\n" .
                    "📝 *Note:* {$statusInfo['note']}\n" .
                    "--------------------------------\n\n" .
                    "_Sistem Qual Nova Automation_";
            } 
            // Jika ID recipient berbeda, sebutkan nama aktor yang melakukan update
            else {
                $pesan = "Halo *{$recipient->name}*,\n\n" .
                    "*{$actor->name}* telah *{$actionBy}* data cacat dengan rincian:\n\n" .
                    "🆔 ID Data: #{$data->id_cacat}\n" .
                    "📅 Waktu: {$tanggalWaktu}\n" .
                    "🧵 Kain: {$data->jenis_kain}\n" .
                    "⚠️ Cacat: {$data->jenis_cacat}\n" .
                    "⚙️ Mesin: {$data->lokasi_mesin}\n\n" .
                    "--------------------------------\n" .
                    "📌 *STATUS SAAT INI: {$statusInfo['label']}*\n" .
                    "📝 *Note:* {$statusInfo['note']}\n" .
                    "--------------------------------\n\n" .
                    "_Silakan tinjau kembali di dashboard Qual Nova._";
            }

            // Cek duplikasi untuk menghindari spam (kecuali untuk verifikasi penting)
            $existing = WhatsappNotification::where('nomor_tujuan', $recipient->whatsapp)
                ->where('id_cacat', $data->id_cacat)
                ->where('created_at', '>=', now()->subMinutes(1)) // Cegah spam dalam 1 menit
                ->first();

            if (!$existing || $actionBy === 'Memverifikasi') {
                $this->dispatchJob($data->id_cacat, $recipient->whatsapp, $pesan);
            }
        }
    }

    /**
     * Mapping Status, Label, dan Note khusus.
     */
    private function getStatusDetail($statusCode)
    {
        switch ((int)$statusCode) {
            case 1:
                return [
                    'label' => '✅ TERVERIFIKASI (VALID)',
                    'note'  => 'Data telah sah dan divalidasi. Laporan masuk ke rekap produksi.'
                ];
            case 2:
                return [
                    'label' => '🔵 PERLU REVISI (NEED FIX)',
                    'note'  => 'Data tidak sesuai. Mohon operator segera perbaiki detail laporan.'
                ];
            case 3:
                return [
                    'label' => '❌ DITOLAK (REJECTED)',
                    'note'  => 'Laporan dibatalkan/tidak valid menurut standar QC.'
                ];
            case 0:
            default:
                return [
                    'label' => '⏳ MENUNGGU VERIFIKASI',
                    'note'  => 'Laporan telah diterima sistem. Menunggu peninjauan petugas QC.'
                ];
        }
    }

    /**
     * Memasukkan ke database antrean notifikasi.
     */
    private function dispatchJob($idCacat, $nomor, $pesan)
    {
        try {
            $notif = WhatsappNotification::create([
                'id_cacat' => $idCacat,
                'nomor_tujuan' => $nomor,
                'pesan' => $pesan,
                'status' => 'pending',
            ]);
            
            // Memicu Job Background agar aplikasi tetap cepat
            SendWhatsappMessageJob::dispatch($notif);
            
        } catch (\Exception $e) {
            Log::error('WA Notification Loop Error: ' . $e->getMessage());
        }
    }
}
