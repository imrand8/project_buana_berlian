<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesananTravel;
use App\Models\PesananKargo;
use Illuminate\Support\Facades\Auth;

class CekTiketController extends Controller
{
    public function index()
    {
        $travels = collect();
        $kargos = collect();

        // 1. Privasi Terjamin: Hanya tarik tiket milik User ID yang sedang login
        if (Auth::check()) {
            // Eager load jadwal, rute, armada, dan DRIVER!
            $travels = PesananTravel::with(['jadwal.rute', 'jadwal.armada', 'jadwal.driver'])->where('user_id', Auth::id())->get();
            $kargos = PesananKargo::with(['jadwal.armada', 'jadwal.driver'])->where('user_id', Auth::id())->get();
        }

        return view('user.cek-tiket', compact('travels', 'kargos'));
    }

    public function uploadBukti(Request $request)
    {
        // 1. Validasi input request di awal biar aman
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
            'pesanan_id'     => 'required',
            'tipe_pesanan'   => 'required|in:travel,kargo',
            'metode_bayar'   => 'required'
        ]);

        // 2. Tangkap ID dan Tipe dari request form
        $id = $request->pesanan_id;
        $tipe = $request->tipe_pesanan;

        // 3. Cari data pesanan berdasarkan tipe yang dikirim (Travel / Kargo)
        if ($tipe == 'travel') {
            $pesanan = PesananTravel::with('jadwal')->findOrFail($id);
        } else {
            $pesanan = PesananKargo::with('jadwal')->findOrFail($id);
        }

        // 4. Logika Validasi 30 Menit (Gembok Waktu)
        if ($pesanan->jadwal) {
            $waktuBerangkat = \Carbon\Carbon::parse($pesanan->jadwal->tanggal_berangkat . ' ' . $pesanan->jadwal->jam_berangkat, 'Asia/Jakarta');
            
            if (\Carbon\Carbon::now('Asia/Jakarta')->addMinutes(30)->greaterThanOrEqualTo($waktuBerangkat)) {
                return redirect()->back()->with('error', 'Waktu keberangkatan kurang dari 30 menit. Fitur upload ditutup. Silakan bayar secara Tunai (Cash) ke supir atau hubungi WA Admin.');
            }
        }

        // 5. Proses simpan gambar
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        // 6. Update data ke database sesuai tipe
        if ($tipe == 'travel') {
            PesananTravel::where('id', $id)->where('user_id', Auth::id())->update([
                'bukti_transfer' => $path,
                'metode_bayar'   => $request->metode_bayar,
                'status_pesanan' => 'menunggu_verifikasi',
                'alasan_tolak'   => null
            ]);
        } else {
            PesananKargo::where('id', $id)->where('user_id', Auth::id())->update([
                'bukti_transfer' => $path,
                'metode_bayar'   => $request->metode_bayar,
                'status_pesanan' => 'menunggu_verifikasi', 
                'alasan_tolak'   => null
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi admin.');
    }

    public function batalkan($type, $id)
    {
        if ($type == 'travel') {
            PesananTravel::where('id', $id)->where('user_id', Auth::id())->update(['status_pesanan' => 'batal']);
        } else {
            PesananKargo::where('id', $id)->where('user_id', Auth::id())->update(['status_pesanan' => 'batal']);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}