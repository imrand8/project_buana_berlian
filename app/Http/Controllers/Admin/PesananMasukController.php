<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesananTravel;
use App\Models\PesananKargo;
use App\Models\Jadwal;
use Carbon\Carbon;

class PesananMasukController extends Controller
{
public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? \Carbon\Carbon::today('Asia/Jakarta')->toDateString();

        $travels = PesananTravel::with(['jadwal.armada', 'jadwal.rute', 'jadwal.driver'])
            ->whereHas('jadwal', function($q) use ($tanggal) {
                $q->where('tanggal_berangkat', $tanggal);
            })->orderBy('created_at', 'desc')->get();

        $kargos = PesananKargo::with('jadwal.armada')
            ->where('tanggal_berangkat', $tanggal)
            ->orderBy('created_at', 'desc')->get();

        // TAMBAHKAN RELASI 'rute' DI SINI AGAR BISA DICEK OLEH MODAL NANTI
        $jadwals = Jadwal::with(['armada', 'driver', 'rute'])->where('tanggal_berangkat', $tanggal)->get();

        $sekarang = \Carbon\Carbon::now('Asia/Jakarta');
        $jadwalsTersedia = $jadwals->filter(function ($j) use ($sekarang) {
            $waktuBerangkat = \Carbon\Carbon::parse($j->tanggal_berangkat . ' ' . $j->jam_berangkat, 'Asia/Jakarta');
            return $waktuBerangkat->greaterThan($sekarang);
        });

        return view('admin.pesanan.index', compact('travels', 'kargos', 'tanggal', 'jadwals', 'jadwalsTersedia'));
    }

    public function assignKargo(Request $request, $id)
    {
        $request->validate(['jadwal_id' => 'required|exists:jadwals,id']);
        $kargo = PesananKargo::findOrFail($id);

        if ($kargo->status_pesanan !== 'lunas') {
            // Ubah menjadi withErrors agar notifikasi SweetAlert muncul di layar admin
            return redirect()->back()->withErrors('GAGAL: Kargo belum di-ACC/Lunas. Tidak bisa ditugaskan ke mobil!');
        }

        // Ambil relasi rute
        $jadwalTujuan = Jadwal::with('rute')->findOrFail($request->jadwal_id);
        $waktuBerangkat = \Carbon\Carbon::parse($jadwalTujuan->tanggal_berangkat . ' ' . $jadwalTujuan->jam_berangkat, 'Asia/Jakarta');
        
        if (\Carbon\Carbon::now('Asia/Jakarta')->greaterThanOrEqualTo($waktuBerangkat)) {
            return redirect()->back()->withErrors('GAGAL: Mobil tersebut sudah berangkat/melewati jam operasional!');
        }

        // --- BLOKIR KARGO NYASAR (VALIDASI RUTE BACKEND) ---
        // Potong embel-embel seperti "(alun)" agar backend bisa mencocokkan kota utamanya saja
        $kargoAsalClean = trim(explode('(', strtolower($kargo->kota_asal))[0]);
        $kargoTujuanClean = trim(explode('(', strtolower($kargo->kota_tujuan))[0]);
        $ruteAsalClean = trim(explode('(', strtolower($jadwalTujuan->rute->kota_asal))[0]);
        $ruteTujuanClean = trim(explode('(', strtolower($jadwalTujuan->rute->kota_tujuan))[0]);

        if ($kargoAsalClean !== $ruteAsalClean || $kargoTujuanClean !== $ruteTujuanClean) {
            return redirect()->back()->withErrors('GAGAL: Rute kargo beda arah dengan mobil ini!');
        }
        // ------------------------------------------

        $kargo->update(['jadwal_id' => $request->jadwal_id]);
        return redirect()->back()->with('success', 'Kargo berhasil ditugaskan ke armada tersebut!');
    }
}