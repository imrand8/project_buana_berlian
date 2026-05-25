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

        // Jadwal untuk tombol "Cetak Manifest" (tampil semua)
        $jadwals = Jadwal::with(['armada', 'driver'])->where('tanggal_berangkat', $tanggal)->get();

        // --- FIX: Filter khusus untuk Dropdown Assign Kargo ---
        $sekarang = \Carbon\Carbon::now('Asia/Jakarta');
        $jadwalsTersedia = $jadwals->filter(function ($j) use ($sekarang) {
            $waktuBerangkat = \Carbon\Carbon::parse($j->tanggal_berangkat . ' ' . $j->jam_berangkat, 'Asia/Jakarta');
            return $waktuBerangkat->greaterThan($sekarang);
        });

        // Tambahkan 'jadwalsTersedia' ke dalam compact()
        return view('admin.pesanan.index', compact('travels', 'kargos', 'tanggal', 'jadwals', 'jadwalsTersedia'));
    }

    public function assignKargo(Request $request, $id)
    {
        $request->validate(['jadwal_id' => 'required|exists:jadwals,id']);
        $kargo = PesananKargo::findOrFail($id);

        if ($kargo->status_pesanan !== 'lunas') {
            return redirect()->back()->with('error', 'GAGAL: Kargo belum di-ACC/Lunas. Tidak bisa ditugaskan ke mobil!');
        }

        // --- FIX: Cegah Admin maksa assign ke mobil yang udah jalan ---
        $jadwalTujuan = Jadwal::findOrFail($request->jadwal_id);
        $waktuBerangkat = \Carbon\Carbon::parse($jadwalTujuan->tanggal_berangkat . ' ' . $jadwalTujuan->jam_berangkat, 'Asia/Jakarta');
        
        if (\Carbon\Carbon::now('Asia/Jakarta')->greaterThanOrEqualTo($waktuBerangkat)) {
            return redirect()->back()->with('error', 'GAGAL: Mobil tersebut sudah berangkat/melewati jam operasional!');
        }
        // ----------------------------------------------------------------

        $kargo->update(['jadwal_id' => $request->jadwal_id]);
        return redirect()->back()->with('success', 'Kargo berhasil ditugaskan ke armada tersebut!');
    }

    public function destroyTravel($id)
    {
        PesananTravel::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pesanan Travel dibatalkan/dihapus.');
    }

    public function destroyKargo($id)
    {
        PesananKargo::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pesanan Kargo dibatalkan/dihapus.');
    }
}