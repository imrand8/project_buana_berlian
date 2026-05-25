<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Pastikan Model TarifKargo dipanggil
use App\Models\TarifKargo; 

class PageController extends Controller
{
    public function beranda()
    {
        // Tarik data Tarif Kargo untuk kalkulator Cek Ongkir di Beranda
        $tarifKargo = \App\Models\TarifKargo::first();
        
        return view('user.dashboard', compact('tarifKargo'));
    }

    public function layanan()
    {
        // Tarik data rute untuk dropdown
        $rutes = \App\Models\Rute::all();
        
        // Tarik jadwal yang tanggalnya hari ini atau ke depan
        $jadwals = \App\Models\Jadwal::with(['rute', 'armada'])
            ->where('tanggal_berangkat', '>=', date('Y-m-d'))
            ->get();
            
        // Tarik pesanan yang udah Lunas atau Menunggu (biar kursinya dikunci)
        $travels = \App\Models\PesananTravel::whereIn('status_pesanan', ['menunggu_verifikasi', 'lunas'])->get();

        // 🚀 TARIK DATA TARIF KARGO DARI DATABASE
        $tarifKargo = \App\Models\TarifKargo::first();

        // Lempar variabel $tarifKargo ke view layanan
        return view('user.layanan', compact('rutes', 'jadwals', 'travels', 'tarifKargo'));
    }

    public function tentangKami()
    {
        return view('user.tentang-kami');
    }
}