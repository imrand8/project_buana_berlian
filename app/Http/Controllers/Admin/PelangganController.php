<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;    // <--- TAMBAHAN UNTUK ENKRIPSI PASSWORD
use Illuminate\Support\Facades\Storage; // <--- TAMBAHAN UNTUK MENGHAPUS FOTO

class PelangganController extends Controller
{
    // 1. Tampilkan Halaman Data Pelanggan
    public function index(Request $request)
    {
        $pelanggans = User::where('role', 'pelanggan')
                          ->withCount(['pesananTravel', 'pesananKargo'])
                          ->latest()
                          ->get();

        $menungguCount = User::where('role', 'pelanggan')->where('status_mahasiswa', 'menunggu_verifikasi')->count();

        return view('admin.pelanggan.index', compact('pelanggans', 'menungguCount'));
    }

    // 2. Fungsi ACC KTM
    public function terimaKtm($id)
    {
        $user = User::findOrFail($id);
        $user->status_mahasiswa = 'terverifikasi';
        $user->save();

        return response()->json(['success' => true, 'message' => 'Status mahasiswa berhasil diverifikasi!']);
    }

    // 3. Fungsi Tolak KTM
    public function tolakKtm(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->ktm_path) {
            Storage::disk('public')->delete($user->ktm_path);
        }

        $user->status_mahasiswa = 'reguler';
        $user->ktm_path = null; 
        $user->alasan_tolak_ktm = $request->alasan;
        $user->save();

        return response()->json(['success' => true, 'message' => 'KTM berhasil ditolak dan dikembalikan ke user.']);
    }

    // 4. FUNGSI BARU: Reset Password ke Default (12345678)
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('12345678');
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password berhasil direset menjadi: 12345678']);
    }

    // 5. FUNGSI BARU: Hapus Akun Pelanggan Permanen
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Bersihkan foto agar memori server tidak penuh
        if ($user->avatar) { Storage::disk('public')->delete($user->avatar); }
        if ($user->ktm_path) { Storage::disk('public')->delete($user->ktm_path); }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Akun pelanggan berhasil dihapus permanen!']);
    }
}