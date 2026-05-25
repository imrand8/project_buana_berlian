<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['nama_supir', 'no_hp', 'status'];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}