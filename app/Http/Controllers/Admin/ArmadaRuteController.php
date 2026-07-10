<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Armada;
use App\Models\Rute;
use App\Models\Driver;
use App\Models\TarifKargo;

class ArmadaRuteController extends Controller
{
    public function index()
    {
        // 1. Ambil semua data rute dari database
        $allRutes = Rute::all();
        
        // 2. Buat wadah (collection) kosong untuk menampung rute yang sudah disaring
        $rutes = collect();

        // 3. Looping untuk mengecek dan memfilter rute ganda (bolak-balik)
        foreach ($allRutes as $rute) {
            // Cek apakah rute kebalikannya sudah masuk ke dalam wadah $rutes
            $isDuplicate = $rutes->contains(function ($item) use ($rute) {
                return $item->kota_asal === $rute->kota_tujuan && 
                       $item->kota_tujuan === $rute->kota_asal;
            });

            // Jika rute kebalikannya (misal Pacitan-Malang) belum ada, masukkan rute saat ini
            if (!$isDuplicate) {
                $rutes->push($rute);
            }
        }

        $armadas = Armada::all();
        $drivers = Driver::all();
        
        // Tarik data Tarif Kargo. Kalau kosong, otomatis bikin default! (Biar nggak usah repot bikin Seeder)
        $tarif = TarifKargo::first();
        if (!$tarif) {
            $tarif = TarifKargo::create(['harga_dasar' => 50000, 'harga_selanjutnya' => 25000]);
        }
        
        return view('admin.data-master.index', compact('rutes', 'armadas', 'drivers', 'tarif'));
    }

    // --- KELOLA RUTE ---
    public function updateRute(Request $request, $id)
    {
        $request->validate(['harga' => 'required|numeric|min:0']);
        $rute = Rute::findOrFail($id);
        
        $rute->update(['harga_reguler' => $request->harga, 'harga_mahasiswa' => $request->harga]);

        $ruteSebaliknya = Rute::where('kota_asal', $rute->kota_tujuan)->where('kota_tujuan', $rute->kota_asal)->first();
        if ($ruteSebaliknya) $ruteSebaliknya->update(['harga_reguler' => $request->harga, 'harga_mahasiswa' => $request->harga]);

        return redirect()->back()->with('success', 'Harga rute PP berhasil diupdate!');
    }

    public function destroyRute($id)
    {
        $rute = Rute::findOrFail($id);
        $asal = $rute->kota_asal; $tujuan = $rute->kota_tujuan;
        $rute->delete();
        Rute::where('kota_asal', $tujuan)->where('kota_tujuan', $asal)->delete();

        return redirect()->back()->with('success', "Rute $asal ➔ $tujuan (PP) berhasil dihapus!");
    }

    public function storeRute(Request $request)
    {
        $request->validate(['kota_asal' => 'required|string', 'kota_tujuan' => 'required|string', 'harga' => 'required|numeric|min:0']);
        $asal = ucwords(strtolower($request->kota_asal)); 
        $tujuan = ucwords(strtolower($request->kota_tujuan));
        
        if ($asal === $tujuan) return redirect()->back()->withErrors('Kota asal dan tujuan tidak boleh sama!');

        // --- TAMBAHAN: CEK DUPLIKAT ---
        $exists = Rute::where('kota_asal', $asal)->where('kota_tujuan', $tujuan)->exists();
        if ($exists) {
            return redirect()->back()->withErrors("Rute $asal ➔ $tujuan sudah ada di sistem!");
        }
        // ------------------------------

        Rute::create(['kota_asal' => $asal, 'kota_tujuan' => $tujuan, 'harga_reguler' => $request->harga, 'harga_mahasiswa' => $request->harga]);
        Rute::create(['kota_asal' => $tujuan, 'kota_tujuan' => $asal, 'harga_reguler' => $request->harga, 'harga_mahasiswa' => $request->harga]);

        return redirect()->back()->with('success', "Rute $asal ➔ $tujuan (PP) berhasil ditambahkan!");
    }

    // --- KELOLA KARGO ---
    public function updateKargo(Request $request)
    {
        $request->validate([
            'harga_dasar' => 'required|integer|min:0',
            'harga_selanjutnya' => 'required|integer|min:0',
        ]);

        $tarif = TarifKargo::first();
        $tarif->update([
            'harga_dasar' => $request->harga_dasar,
            'harga_selanjutnya' => $request->harga_selanjutnya
        ]);

        return redirect()->back()->with('success', 'Tarif Kargo Global berhasil diperbarui!');
    }

    
    // --- KELOLA ARMADA ---
    public function storeArmada(Request $request)
    {
        $request->validate([
            'nama_armada' => 'required|string', 
            'plat_nomor' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Validasi file gambar
        ]);

        $plat = strtoupper($request->plat_nomor);

        if (Armada::where('plat_nomor', $plat)->exists()) {
            return redirect()->back()->withErrors("Gagal! Mobil dengan Plat Nomor $plat sudah terdaftar.");
        }

        // Proses Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('armada-images', 'public');
        }

        Armada::create([
            'nama_armada' => $request->nama_armada, 
            'plat_nomor' => $plat,
            'image' => $imagePath // Simpan ke database
        ]);

        return redirect()->back()->with('success', 'Data armada berhasil ditambahkan!');
    }

    public function updateArmada(Request $request, $id)
    {
        $request->validate([
            'nama_armada' => 'required|string', 
            'plat_nomor' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $armada = Armada::findOrFail($id);
        
        $dataUpdate = [
            'nama_armada' => $request->nama_armada, 
            'plat_nomor' => strtoupper($request->plat_nomor)
        ];

        // Proses Update Gambar (Hapus yang lama, simpan yang baru)
        if ($request->hasFile('image')) {
            if ($armada->image && Storage::disk('public')->exists($armada->image)) {
                Storage::disk('public')->delete($armada->image);
            }
            $dataUpdate['image'] = $request->file('image')->store('armada-images', 'public');
        }

        $armada->update($dataUpdate);
        
        return redirect()->back()->with('success', 'Data armada berhasil diperbarui!');
    }

    public function destroyArmada($id)
    {
        Armada::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Armada berhasil dihapus dari sistem!');
    }

    // --- KELOLA SUPIR (DRIVER) ---
    public function storeDriver(Request $request)
    {
        $request->validate([
            'nama_supir' => 'required|string', 
            'no_hp' => 'required|string'
        ]);

        // --- TAMBAHAN: CEK DUPLIKAT NO HP ---
        if (Driver::where('no_hp', $request->no_hp)->exists()) {
            return redirect()->back()->withErrors("Gagal! Supir dengan No. HP {$request->no_hp} sudah ada di sistem.");
        }
        // ------------------------------------

        Driver::create([
            'nama_supir' => $request->nama_supir, 
            'no_hp' => $request->no_hp
        ]);
        
        return redirect()->back()->with('success', 'Data supir berhasil ditambahkan!');
    }
    public function updateDriver(Request $request, $id)
    {
        $request->validate(['nama_supir' => 'required|string', 'no_hp' => 'required|string']);
        Driver::findOrFail($id)->update(['nama_supir' => $request->nama_supir, 'no_hp' => $request->no_hp]);
        return redirect()->back()->with('success', 'Data supir berhasil diperbarui!');
    }

    public function destroyDriver($id)
    {
        Driver::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data supir berhasil dihapus!');
    }
}