<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesananTravel;
use App\Models\PesananKargo;

class ValidasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil filter tanggal (default: hari ini, format YYYY-MM-DD)
        $tanggal = $request->tanggal ?? \Carbon\Carbon::today()->toDateString();

        // 2. Tarik data berdasarkan tanggal yang dipilih
        $travels = PesananTravel::whereDate('created_at', $tanggal)
                                ->orderBy('created_at', 'desc')->get();
                                
        $kargos = PesananKargo::whereDate('created_at', $tanggal)
                            ->orderBy('created_at', 'desc')->get();

        return view('admin.validasi.index', compact('travels', 'kargos', 'tanggal'));
    }

    public function accPembayaran($type, $id)
    {
        if ($type === 'travel') {
            PesananTravel::findOrFail($id)->update(['status_pesanan' => 'lunas']);
        } else {
            PesananKargo::findOrFail($id)->update(['status_pesanan' => 'lunas']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil di-ACC! Status pesanan sekarang Lunas.');
    }

    public function tolakPembayaran(Request $request, $type, $id)
    {
        // 1. Gabungkan Alasan Penolakan
        $alasan = $request->alasan_penolakan;
        if ($request->filled('alasan_custom')) {
            $alasan = $alasan ? $alasan . ' - ' . $request->alasan_custom : $request->alasan_custom;
        }

        // 2. Ambil Data Pesanan
        $pesanan = ($type === 'travel') ? PesananTravel::findOrFail($id) : PesananKargo::findOrFail($id);

        // 3. LOGIKA PINTAR: Tentukan status dari "Pilihan Radio Button" Admin
        $alasanUploadUlang = ['Struk Buram / Tidak Valid', 'Nominal Kurang'];

        if (in_array($request->alasan_penolakan, $alasanUploadUlang)) {
            $statusFinal = 'ditolak'; // Pesanan hidup, butuh upload ulang
        } else {
            $statusFinal = 'batal';   // Pesanan hangus (Refund / Spam / COD fiktif)
            
            // Opsional: Jika dibatalkan, lepaskan kuncian nomor kursi (kalau ada field-nya)
            // if ($type === 'travel') { $pesanan->nomor_kursi = null; }
        }

        // 4. Update Database
        $pesanan->update([
            'status_pesanan' => $statusFinal,
            'alasan_tolak'   => $alasan
        ]);

        // 5. Pesan SweetAlert Dinamis
        $pesanNotif = ($statusFinal === 'batal') 
            ? 'Pesanan berhasil DIBATALKAN permanen dari sistem.' 
            : 'Pembayaran DITOLAK! Pelanggan diminta upload bukti baru.';

        return redirect()->back()->with('success', $pesanNotif);
    }
}