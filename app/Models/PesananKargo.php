<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananKargo extends Model
{
    use HasFactory;

    protected $table = 'pesanan_kargos';

    protected $fillable = [
        'kode_resi', 'user_id', 'jadwal_id', 'tanggal_berangkat', 'jam_berangkat', 
        'kota_asal', 'kota_tujuan', 'berat_barang', 
        'nama_pengirim', 'nomor_wa_pengirim', 
        'nama_penerima', 'nomor_wa_penerima', 
        'keterangan_barang', 'total_harga', 
        'metode_bayar', 'bukti_transfer', 'status_pesanan', 'alasan_tolak' // <-- TAMBAH INI
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function rute() { return $this->belongsTo(Rute::class); }
    public function jadwal() { return $this->belongsTo(Jadwal::class); }
}