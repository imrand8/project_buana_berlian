<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananTravel extends Model
{
    use HasFactory;

    protected $table = 'pesanan_travels';

    protected $fillable = [
        'kode_booking', 'user_id', 'jadwal_id', 'nama_penumpang', 'nomor_wa',
        'titik_jemput', 'titik_antar', 'keterangan_barang', 'nomor_kursi',
        'jumlah_kursi', 'total_harga', 'metode_bayar', 'bukti_transfer',
        'status_pesanan', 'diverifikasi_oleh', 'alasan_tolak'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}