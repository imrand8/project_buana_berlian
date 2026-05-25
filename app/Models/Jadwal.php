<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'rute_id', 'armada_id', 'driver_id', 'tanggal_berangkat', 'jam_berangkat', 'kursi_tersedia', 'status_jadwal'
    ];

    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }

public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function pesananTravel()
    {
        return $this->hasMany(PesananTravel::class);
    }
}