<?php

namespace App\Jobs;

use App\Models\WhatsappNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;

    public function __construct(WhatsappNotification $notification)
    {
        $this->notification = $notification;
    }

    public function handle(): void
    {
        $notif = $this->notification;

        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->asMultipart()->post('https://api.fonnte.com/send', [
                [
                    'name' => 'target',
                    'contents' => $notif->nomor_tujuan,
                ],
                [
                    'name' => 'message',
                    'contents' => $notif->pesan,
                ],
                [
                    'name' => 'delay',
                    'contents' => '2',
                ],
                [
                    'name' => 'countryCode',
                    'contents' => '62',
                ],
            ]);

            if ($response->successful()) {
                $notif->update([
                    'status' => 'sent',
                    'sent_at' => Carbon::now(),
                ]);
            } else {
                $notif->update(['status' => 'failed']);
            }

        } catch (\Throwable $th) {
            $notif->update(['status' => 'failed']);
        }
    }
}
