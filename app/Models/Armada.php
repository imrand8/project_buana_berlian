<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    use HasFactory;

    // Ini wajib ada biar Laravel gak nolak saat nyimpen data
    protected $fillable = [
        'nama_armada', 
        'plat_nomor', 
        'image',
        'kapasitas_kursi', 
        'status_operasional'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}