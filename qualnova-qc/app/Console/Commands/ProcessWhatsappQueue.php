<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WhatsappNotification;
use App\Jobs\SendWhatsappMessageJob;

class ProcessWhatsappQueue extends Command
{
    protected $signature = 'whatsapp:send';
    protected $description = 'Proses antrian notifikasi WhatsApp satu per satu';

    public function handle()
    {
        // Ambil notifikasi pending atau failed yang bisa dicoba lagi
        $notif = WhatsappNotification::whereIn('status', ['pending', 'failed'])
            ->orderBy('updated_at', 'asc')
            ->first();

        if (!$notif) {
            $this->info('Tidak ada antrian WhatsApp pending atau failed.');
            return;
        }

        $this->info("Mengirim pesan ke {$notif->nomor_tujuan}...");

        try {
            dispatch(new SendWhatsappMessageJob($notif));
            $this->info('Pesan sedang dikirim via queue... ✅');

            // Jika berhasil, set status tetap pending atau bisa langsung closed tergantung logic job
            $notif->status = 'pending'; 
            $notif->save();
        } catch (\Exception $e) {
            // Jika gagal
            if ($notif->status === 'failed') {
                // Kalau sebelumnya sudah failed sekali, jadikan closed
                $notif->status = 'closed';
            } else {
                // Jika sebelumnya pending, ubah ke failed
                $notif->status = 'failed';
            }

            $notif->save();

            $this->error("Gagal mengirim pesan ke {$notif->nomor_tujuan}. Status sekarang: {$notif->status}");
        }
    }
}




