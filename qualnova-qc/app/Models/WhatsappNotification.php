<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappNotification extends Model
{
    protected $table = 'whatsapp_notifications';
    protected $primaryKey = 'id_notif';
    protected $fillable = [
        'id_cacat',
        'nomor_tujuan',
        'pesan',
        'status',
        'sent_at',
    ];
}
