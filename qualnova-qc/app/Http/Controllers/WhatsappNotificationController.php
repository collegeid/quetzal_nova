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
     * Mengirim notifikasi WhatsApp berdasarkan status terbaru data.
     */
    public function sendUpdateNotification(DataCacat $data, string $actionBy)
    {
        $authUser = Auth::user();
        if (!$authUser) return;

        // Persiapan Waktu
        $tanggal = Carbon::parse($data->tanggal);
        $now = Carbon::now();
        $tanggalWaktu = $tanggal->setTime($now->hour, $now->minute, $now->second)->format('d/m/Y H:i:s');

        // Logika Status & Note Dinamis
        $statusInfo = $this->getStatusDetail($data->status_verifikasi);
        $statusLabel = $statusInfo['label'];
        $noteStatus = $statusInfo['note'];

        // 1. Notifikasi untuk Pengirim (Self)
        if ($authUser->whatsapp) {
            $pesanSelf = "Halo *{$authUser->name}*,\n\n" .
                "Kamu telah *{$actionBy}* data cacat dengan detail berikut:\n\n" .
                "🆔 ID Data: #{$data->id_cacat}\n" .
                "📅 Waktu: {$tanggalWaktu}\n" .
                "🧵 Kain: {$data->jenis_kain}\n" .
                "⚠️ Cacat: {$data->jenis_cacat}\n" .
                "⚙️ Mesin: {$data->lokasi_mesin}\n\n" .
                "--------------------------------\n" .
                "📌 *STATUS TERBARU: {$statusLabel}*\n" .
                "📝 *Note:* {$noteStatus}\n" .
                "--------------------------------\n\n" .
                "_Sistem Qual Nova Automation_";

            $this->dispatchJob($data->id_cacat, $authUser->whatsapp, $pesanSelf);
        }

        // 2. Notifikasi untuk Recipients (QC/Manager/Admin)
        $recipients = User::whereIn('role', ['manager_produksi', 'super_admin', 'petugas_qc'])
            ->where('id', '!=', $authUser->id)
            ->get();

        foreach ($recipients as $user) {
            if (!$user->whatsapp) continue;

            $pesanRecipient = "Halo *{$user->name}*,\n\n" .
                "*{$authUser->name}* telah *{$actionBy}* data cacat dengan rincian:\n\n" .
                "🆔 ID Data: #{$data->id_cacat}\n" .
                "📅 Waktu: {$tanggalWaktu}\n" .
                "🧵 Kain: {$data->jenis_kain}\n" .
                "⚠️ Cacat: {$data->jenis_cacat}\n" .
                "⚙️ Mesin: {$data->lokasi_mesin}\n\n" .
                "--------------------------------\n" .
                "📌 *STATUS SAAT INI: {$statusLabel}*\n" .
                "📝 *Note:* {$noteStatus}\n" .
                "--------------------------------\n\n" .
                "_Silakan tinjau kembali di dashboard Qual Nova._";

            // Cek duplikasi kecuali untuk aksi verifikasi agar notifikasi tetap masuk
            $existing = WhatsappNotification::where('nomor_tujuan', $user->whatsapp)
                ->where('id_cacat', $data->id_cacat)
                ->latest()->first();

            if (!$existing || $actionBy === 'Memverifikasi') {
                $this->dispatchJob($data->id_cacat, $user->whatsapp, $pesanRecipient);
            }
        }
    }

    /**
     * Fungsi pembantu untuk menentukan label dan pesan tambahan berdasarkan kode status.
     */
    private function getStatusDetail($statusCode)
    {
        switch ((int)$statusCode) {
            case 1: // Verified
                return [
                    'label' => '✅ TERVERIFIKASI (VALID)',
                    'note'  => 'Data telah sah dan divalidasi oleh sistem. Terima kasih atas laporannya.'
                ];
            case 2: // Revision
                return [
                    'label' => '🔵 PERLU REVISI (NEED FIX)',
                    'note'  => 'Terdapat ketidaksesuaian data. Mohon operator segera memeriksa dan memperbaiki input laporan.'
                ];
            case 3: // Rejected
                return [
                    'label' => '❌ DITOLAK (REJECTED)',
                    'note'  => 'Data dinyatakan tidak valid dan dibatalkan oleh pihak verifikator QC.'
                ];
            case 0: // Waiting / Pending
            default:
                return [
                    'label' => '⏳ MENUNGGU VERIFIKASI',
                    'note'  => 'Laporan telah diterima. Mohon menunggu peninjauan dari petugas QC.'
                ];
        }
    }

    /**
     * Memasukkan data ke database dan memicu Job antrean WhatsApp.
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
            SendWhatsappMessageJob::dispatch($notif);
        } catch (\Exception $e) {
            Log::error('WA Notif Error: ' . $e->getMessage());
        }
    }
}