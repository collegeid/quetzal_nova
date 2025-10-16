<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCacat extends Model
{
    use HasFactory;

    protected $table = 'data_cacat';
    protected $primaryKey = 'id_cacat';

    protected $fillable = [
        'tanggal',
        'shift',
        'jenis_kain',
        'lokasi_mesin',
        'jenis_cacat',
        'foto_bukti',
        'status_verifikasi',
        'id_user',
        'id_jenis',
    ];

    // 🔗 Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisCacat::class, 'id_jenis');
    }

    public function verifikasi()
    {
        return $this->hasOne(Verifikasi::class, 'id_cacat');
    }
}
