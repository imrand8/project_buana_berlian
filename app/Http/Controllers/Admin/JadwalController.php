<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Jadwal;
use App\Models\Armada;
use App\Models\Rute;
use App\Models\Driver;
use App\Models\PesananTravel;
use App\Models\PesananKargo;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta')->toDateString();
        // Filter Tanggal (default: hari ini)
        $filterTanggal = $request->tanggal ?? $today;

        // --- FIX: Filter Jadwal Aktif HANYA untuk tanggal yang dipilih ---
        $jadwalAktif = Jadwal::with(['rute', 'armada', 'driver'])
            ->where('tanggal_berangkat', $filterTanggal)  // Terapkan filter tanggal
            ->where('tanggal_berangkat', '>=', $today)    // Pastikan tidak nampilin jadwal basi di tab ini
            ->orderBy('jam_berangkat', 'asc')->get();     // Urutkan dari jam paling pagi

        // --- FIX: Filter Jadwal Selesai/Riwayat HANYA untuk tanggal yang dipilih ---
        $jadwalSelesai = Jadwal::with(['rute', 'armada', 'driver'])
            ->where('tanggal_berangkat', $filterTanggal)  // Terapkan filter tanggal
            ->where('tanggal_berangkat', '<', $today)     // Pastikan HANYA nampilin jadwal basi
            ->orderBy('jam_berangkat', 'desc')->get();

        $travels = PesananTravel::where('status_pesanan', 'lunas')->get();
        $kargos = PesananKargo::where('status_pesanan', 'lunas')->get();
        $rutes = Rute::all();
        $armadas = Armada::all();
        $drivers = Driver::all();

        return view('admin.jadwal.index', compact(
            'jadwalAktif', 'jadwalSelesai', 'travels', 'kargos', 
            'rutes', 'armadas', 'drivers', 'filterTanggal', 'today'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat' => 'required|string',
            'armada_id' => 'required|exists:armadas,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // --- TAMBAHAN REVISI: Validasi Waktu Masa Depan ---
        $waktuBerangkat = Carbon::parse($request->tanggal_berangkat . ' ' . $request->jam_berangkat, 'Asia/Jakarta');
        if (Carbon::now('Asia/Jakarta')->greaterThanOrEqualTo($waktuBerangkat)) {
            return redirect()->back()->with('error', 'GAGAL: Jadwal tidak bisa dibuat untuk waktu yang sudah terlewat!');
        }
        // ---------------------------------------------------

        // 🔒 GEMBOK 1: Cek Bentrok Armada
        $bentrokArmada = Jadwal::where('tanggal_berangkat', $request->tanggal_berangkat)
            ->where('jam_berangkat', $request->jam_berangkat)
            ->where('armada_id', $request->armada_id)
            ->first();
            
        if ($bentrokArmada) {
            return redirect()->back()->with('error', 'GAGAL: Armada tersebut sudah punya jadwal jalan di shift waktu yang sama!');
        }

        // 🔒 GEMBOK 2: Cek Bentrok Supir
        if ($request->driver_id) {
            $bentrokSupir = Jadwal::where('tanggal_berangkat', $request->tanggal_berangkat)
                ->where('jam_berangkat', $request->jam_berangkat)
                ->where('driver_id', $request->driver_id)
                ->first();
                
            if ($bentrokSupir) {
                return redirect()->back()->with('error', 'GAGAL: Supir tersebut sudah ditugaskan ke armada lain di shift waktu yang sama!');
            }
        }

        $armada = Armada::findOrFail($request->armada_id);

        Jadwal::create([
            'rute_id' => $request->rute_id,
            'armada_id' => $request->armada_id,
            'driver_id' => $request->driver_id,
            'tanggal_berangkat' => $request->tanggal_berangkat,
            'jam_berangkat' => $request->jam_berangkat,
            'kursi_tersedia' => $armada->kapasitas_kursi, 
            'status_jadwal' => 'tersedia'
        ]);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal Trayek Utama berhasil dibuka!');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $request->validate([
            'jam_berangkat' => 'required|string',
            'armada_id' => 'required|exists:armadas,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // --- TAMBAHAN REVISI: Validasi Waktu Masa Depan saat Update ---
        $waktuBerangkatBaru = Carbon::parse($jadwal->tanggal_berangkat . ' ' . $request->jam_berangkat, 'Asia/Jakarta');
        if (Carbon::now('Asia/Jakarta')->greaterThanOrEqualTo($waktuBerangkatBaru)) {
            return redirect()->back()->with('error', 'UPDATE GAGAL: Tidak bisa mengubah jam ke waktu yang sudah terlewat hari ini!');
        }

        // 🔒 GEMBOK UPDATE: Cek Bentrok Armada (Kecuali jadwal dia sendiri)
        $bentrokArmada = Jadwal::where('tanggal_berangkat', $jadwal->tanggal_berangkat)
            ->where('jam_berangkat', $request->jam_berangkat)
            ->where('armada_id', $request->armada_id)
            ->where('id', '!=', $id)
            ->first();
            
        if ($bentrokArmada) {
            return redirect()->back()->with('error', 'UPDATE GAGAL: Armada sudah terpakai di jadwal lain pada shift tersebut!');
        }

        // 🔒 GEMBOK UPDATE: Cek Bentrok Supir (Kecuali jadwal dia sendiri)
        if ($request->driver_id) {
            $bentrokSupir = Jadwal::where('tanggal_berangkat', $jadwal->tanggal_berangkat)
                ->where('jam_berangkat', $request->jam_berangkat)
                ->where('driver_id', $request->driver_id)
                ->where('id', '!=', $id)
                ->first();
                
            if ($bentrokSupir) {
                return redirect()->back()->with('error', 'UPDATE GAGAL: Supir sudah bertugas di mobil lain pada shift tersebut!');
            }
        }

        // 1. UPDATE DATA JADWAL UTAMA
        $jadwal->update([
            'jam_berangkat' => $request->jam_berangkat,
            'armada_id' => $request->armada_id,
            'driver_id' => $request->driver_id,
        ]);
        
        
        // 2. LOGIKA SINKRONISASI PENUMPANG (MANUAL & WEB)
        // Hapus HANYA yang manual (karena web tidak boleh dihapus dari sini, harus via batal di pesanan masuk)
        PesananTravel::where('jadwal_id', $id)->where('kode_booking', 'LIKE', 'MNL-%')->delete();
        
        $passengers = json_decode($request->manual_passengers, true);
        if ($passengers) {
            foreach ($passengers as $seat => $data) {
                if ($data['status'] === 'manual') {
                    // CREATE: Insert data manual baru
                    PesananTravel::create([
                        'user_id' => auth()->id(),
                        'kode_booking' => 'MNL-' . strtoupper(Str::random(5)),
                        'jadwal_id' => $id,
                        'nama_penumpang' => $data['name'],
                        'nomor_wa' => $data['hp'],
                        'nomor_kursi' => (string)$seat,
                        'jumlah_kursi' => 1,
                        'titik_jemput' => $data['pick'],
                        'titik_antar' => $data['drop'],
                        'keterangan_barang' => $data['notes'],
                        'total_harga' => $data['price'] ?: 0,
                        'metode_bayar' => 'cash',
                        'status_pesanan' => 'lunas',
                    ]);
                } elseif ($data['status'] === 'web' && !empty($data['id'])) {
                    // UPDATE: Edit data penumpang web yang sudah ada
                    $pesananWeb = PesananTravel::find($data['id']);
                    if ($pesananWeb) {
                        $pesananWeb->update([
                            'nama_penumpang' => $data['name'],
                            'nomor_wa' => $data['hp'],
                            'nomor_kursi' => (string)$seat,
                            'titik_jemput' => $data['pick'],
                            'titik_antar' => $data['drop'],
                            'keterangan_barang' => $data['notes'],
                        ]);
                    }
                }
            }
        }

        // 3. LOGIKA SINKRONISASI KARGO (MANUAL & WEB)
        PesananKargo::where('jadwal_id', $id)->where('kode_resi', 'LIKE', 'MNL-%')->delete();

        $kargos = json_decode($request->manual_kargos, true);
        if ($kargos) {
            foreach ($kargos as $resi => $data) {
                if ($data['status'] === 'manual') {
                    // CREATE: Insert kargo manual baru
                    PesananKargo::create([
                        'user_id' => auth()->id(),
                        'kode_resi' => 'MNL-' . strtoupper(Str::random(5)),
                        'jadwal_id' => $id,
                        'tanggal_berangkat' => $jadwal->tanggal_berangkat,
                        'jam_berangkat' => $jadwal->jam_berangkat,
                        'kota_asal' => $data['pick'],
                        'kota_tujuan' => $data['drop'],
                        'berat_barang' => 1,
                        'nama_pengirim' => $data['name'],
                        'nomor_wa_pengirim' => $data['hp'],
                        'nama_penerima' => $data['penerima'] ?? '-',
                        'nomor_wa_penerima' => $data['hp_penerima'] ?? '-',
                        'keterangan_barang' => $data['notes'],
                        'total_harga' => $data['price'] ?: 0,
                        'metode_bayar' => 'cash',
                        'status_pesanan' => 'lunas',
                    ]);
                } elseif ($data['status'] === 'web' && !empty($data['id'])) {
                    // UPDATE: Edit data kargo web yang sudah ada
                    $pesananWeb = PesananKargo::find($data['id']);
                    if ($pesananWeb) {
                        $pesananWeb->update([
                            'nama_pengirim' => $data['name'],
                            'nomor_wa_pengirim' => $data['hp'],
                            'nama_penerima' => $data['penerima'] ?? '-',
                            'nomor_wa_penerima' => $data['hp_penerima'] ?? '-',
                            'kota_asal' => $data['pick'],
                            'kota_tujuan' => $data['drop'],
                            'keterangan_barang' => $data['notes'],
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Data armada dan penumpang manual berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // 1. Amankan Pesanan Travel: Putus hubungan dengan jadwal & otomatis batalkan tiketnya
        \App\Models\PesananTravel::where('jadwal_id', $id)->update([
            'jadwal_id' => null, 
            'status_pesanan' => 'batal',
            'alasan_tolak' => 'Jadwal keberangkatan dibatalkan oleh Admin.'
        ]);

        // 2. Amankan Pesanan Kargo: Putus hubungan agar kargo kembali ke antrean (Belum ada armada)
        \App\Models\PesananKargo::where('jadwal_id', $id)->update([
            'jadwal_id' => null
        ]);

        // 3. Baru eksekusi hapus jadwal fisiknya
        \App\Models\Jadwal::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Jadwal ditarik! Penumpang dibatalkan & Kargo dikembalikan ke antrean.');
    }
}