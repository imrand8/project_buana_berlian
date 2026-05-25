<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesananTravel;
use App\Models\PesananKargo;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * PROSES 1: SIMPAN PESANAN TRAVEL (DENGAN LOGIKA TRAYEK INDUK)
     */
    public function storeTravel(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kota_asal'         => 'required|string',
            'kota_tujuan'       => 'required|string',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat'     => 'required|string',
            'nama_penumpang'    => 'required|string|max:255',
            'nomor_wa'          => 'required|string|max:20',
            'titik_jemput'      => 'required|string',
            'titik_antar'       => 'required|string',
            'nomor_kursi'       => 'required|string',
            'jumlah_kursi'      => 'required|integer|min:1',
            'total_harga'       => 'required|integer',
            'metode_bayar'      => 'required|in:BSI,DANA,CASH',
            'bukti_transfer'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. LOGIKA TRAYEK INDUK (Menentukan arah mobil)
        $urutanKota = ['Pacitan', 'Trenggalek', 'Tulungagung', 'Blitar', 'Malang'];
        $indexAsal = array_search($request->kota_asal, $urutanKota);
        $indexTujuan = array_search($request->kota_tujuan, $urutanKota);

        if ($indexAsal === false || $indexTujuan === false || $indexAsal === $indexTujuan) {
            return redirect()->back()->withErrors('Rute perjalanan tidak valid.');
        }

        // Tentukan Nama Trayek Utama di Database (Pacitan-Malang atau sebaliknya)
        if ($indexAsal < $indexTujuan) {
            $mainAsal = 'Pacitan';
            $mainTujuan = 'Malang';
        } else {
            $mainAsal = 'Malang';
            $mainTujuan = 'Pacitan';
        }

        // Ambil 2 angka pertama dari jam (contoh: "08" atau "20") biar presisi nyarinya
        $jamKeyword = substr($request->jam_berangkat, 0, 2);

        // 3. CARI JADWAL BERDASARKAN TRAYEK INDUK
        $jadwal = Jadwal::whereHas('rute', function($query) use ($mainAsal, $mainTujuan) {
            $query->where('kota_asal', $mainAsal)->where('kota_tujuan', $mainTujuan);
        })
        ->where('tanggal_berangkat', $request->tanggal_berangkat)
        ->where('jam_berangkat', 'like', $jamKeyword . '%') // Pakai LIKE biar kebal dari perbedaan format detik di MySQL
        ->first();

        if (!$jadwal) {
            return redirect()->back()->withErrors("Jadwal untuk rute {$request->kota_asal} ke {$request->kota_tujuan} belum tersedia. Pastikan Admin sudah membuka jadwal utama.");
        }

        // --- TAMBAHAN REVISI: VALIDASI 30 MENIT SEBELUM BERANGKAT ---
        $waktuBerangkat = Carbon::parse($jadwal->tanggal_berangkat . ' ' . $jadwal->jam_berangkat, 'Asia/Jakarta');
        $batasPesan = $waktuBerangkat->copy()->subMinutes(30);

        if (Carbon::now('Asia/Jakarta')->greaterThan($batasPesan)) {
            return redirect()->back()->withErrors('Mohon maaf, batas waktu pemesanan untuk jadwal ini sudah habis (maksimal 30 menit sebelum keberangkatan). Silakan pilih jadwal lain.');
        }
        // -----------------------------------------------------------

        // 4. Proses Upload Bukti Transfer (BSI / DANA)
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = time() . '_travel_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti_transfer', $fileName, 'public');
        }

        // 5. Generate Kode Booking & Simpan ke Database
        $kodeBooking = 'TRV-' . date('dmy') . '-' . strtoupper(Str::random(4));

        PesananTravel::create([
            'kode_booking'      => $kodeBooking,
            'user_id'           => Auth::id(),
            'jadwal_id'         => $jadwal->id, // Mengunci ke ID Jadwal Utama
            'nama_penumpang'    => $request->nama_penumpang,
            'nomor_wa'          => $request->nomor_wa,
            'titik_jemput'      => "{$request->kota_asal} ({$request->titik_jemput})",
            'titik_antar'       => "{$request->kota_tujuan} ({$request->titik_antar})",
            'keterangan_barang' => $request->keterangan_barang,
            'nomor_kursi'       => $request->nomor_kursi,
            'jumlah_kursi'      => $request->jumlah_kursi,
            'total_harga'       => $request->total_harga,
            'metode_bayar'      => $request->metode_bayar,
            'bukti_transfer'    => $buktiPath,
            'status_pesanan'    => 'menunggu_verifikasi',
        ]);

        return redirect()->route('cek-tiket.index', ['code' => $kodeBooking])
                         ->with('success', 'Booking Travel Berhasil! Silakan tunggu verifikasi admin.');
    }

    /**
     * PROSES 2: SIMPAN PESANAN KARGO
     */
    public function storeKargo(Request $request)
    {
        // 1. Validasi Input Kargo
        $request->validate([
            'kota_asal'         => 'required|string',
            'kota_tujuan'       => 'required|string',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat'     => 'required|string',
            'berat_barang'      => 'required|integer|min:1',
            'nama_pengirim'     => 'required|string|max:255',
            'nomor_wa_pengirim' => 'required|string|max:20',
            'nama_penerima'     => 'required|string|max:255',
            'nomor_wa_penerima' => 'required|string|max:20',
            'keterangan_barang' => 'required|string',
            'total_harga'       => 'required|integer',
            'metode_bayar'      => 'required|in:BSI,DANA,CASH',
            'bukti_transfer'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // --- TAMBAHAN REVISI: VALIDASI 30 MENIT SEBELUM BERANGKAT KARGO ---
        $waktuBerangkatKargo = Carbon::parse($request->tanggal_berangkat . ' ' . $request->jam_berangkat, 'Asia/Jakarta');
        $batasPesanKargo = $waktuBerangkatKargo->copy()->subMinutes(30);

        if (Carbon::now('Asia/Jakarta')->greaterThan($batasPesanKargo)) {
            return redirect()->back()->withErrors('Mohon maaf, batas pengiriman kargo untuk jam tersebut sudah tutup (maksimal 30 menit sebelum jam berangkat).');
        }
        // -----------------------------------------------------------------

        // 2. Upload Bukti Transfer Kargo
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = time() . '_kargo_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti_transfer', $fileName, 'public');
        }

        // 3. Generate Kode Resi & Simpan ke Database
        $kodeResi = 'KRG-' . date('dmy') . '-' . strtoupper(Str::random(4));

        PesananKargo::create([
            'kode_resi'         => $kodeResi,
            'user_id'           => Auth::id(),
            'tanggal_berangkat' => $request->tanggal_berangkat,
            'jam_berangkat'     => $request->jam_berangkat,
            'kota_asal'         => "{$request->kota_asal} ({$request->titik_jemput})",
            'kota_tujuan'       => "{$request->kota_tujuan} ({$request->titik_antar})",
            'berat_barang'      => $request->berat_barang,
            'nama_pengirim'     => $request->nama_pengirim,
            'nomor_wa_pengirim' => $request->nomor_wa_pengirim,
            'nama_penerima'     => $request->nama_penerima,
            'nomor_wa_penerima' => $request->nomor_wa_penerima,
            'keterangan_barang' => $request->keterangan_barang,
            'total_harga'       => $request->total_harga,
            'metode_bayar'      => $request->metode_bayar,
            'bukti_transfer'    => $buktiPath,
            'status_pesanan'    => 'menunggu_verifikasi',
        ]);

        return redirect()->route('cek-tiket.index', ['code' => $kodeResi])
                         ->with('success', 'Pemesanan Kargo Berhasil! Admin akan segera memproses paket Anda.');
    }
}