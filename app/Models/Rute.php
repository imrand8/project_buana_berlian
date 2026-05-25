<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'kota_asal', 'kota_tujuan', 'harga_reguler', 'harga_mahasiswa'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}