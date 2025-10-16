<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';
    protected $primaryKey = 'id_verifikasi';

    protected $fillable = [
        'id_cacat',
        'qc_id',
        'tanggal_verifikasi',
        'valid',
        'catatan',
    ];

    // 🔗 Relasi
    public function qc()
    {
        return $this->belongsTo(User::class, 'qc_id', 'id');
    }
    
    public function dataCacat()
    {
        return $this->belongsTo(DataCacat::class, 'id_cacat', 'id_cacat');
    }
    
}
