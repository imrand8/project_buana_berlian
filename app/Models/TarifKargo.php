<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifKargo extends Model
{
    use HasFactory;
    protected $fillable = ['harga_dasar', 'harga_selanjutnya'];
}