<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCacat extends Model
{
    use HasFactory;

    protected $table = 'jenis_cacat';
    protected $primaryKey = 'id_jenis';

    protected $fillable = [
        'nama_jenis',
    ];

    // 🔗 Relasi
    public function dataCacat()
    {
        return $this->hasMany(DataCacat::class, 'id_jenis');
    }
    
}
