<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // --- UPDATE PROFIL USER (PELANGGAN) ---
    public function updateUser(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'pekerjaan' => 'nullable|string|in:umum,mahasiswa', // Tambahkan validasi ini
            'ktm' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', 
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        // --- LOGIKA PERUBAHAN PEKERJAAN ---
        if ($request->input('pekerjaan') === 'umum') {
            // Jika beralih ke umum, hapus KTM lama (jika ada) dan reset status
            if ($user->ktm_path && Storage::disk('public')->exists($user->ktm_path)) {
                Storage::disk('public')->delete($user->ktm_path);
            }
            $user->ktm_path = null;
            $user->status_mahasiswa = 'reguler';
            $user->alasan_tolak_ktm = null;
        }

        // LOGIKA HAPUS FOTO DEFAULT (Sudah benar)
        if ($request->input('remove_avatar') == '1') {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar); 
            }
            $user->avatar = null; 
        }

        // Jika Pelanggan ganti foto profil
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Jika Pelanggan upload KTM baru (Hanya diproses jika pekerjaan masih "mahasiswa")
        if ($request->hasFile('ktm') && $request->input('pekerjaan') !== 'umum') {
            if ($user->ktm_path) Storage::disk('public')->delete($user->ktm_path);
            $user->ktm_path = $request->file('ktm')->store('ktm_uploads', 'public');
            $user->status_mahasiswa = 'menunggu_verifikasi';
            $user->alasan_tolak_ktm = null;
        }

        $user->save();
        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    // --- UPDATE PROFIL ADMIN ---
    public function updateAdmin(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            // SINKRONISASI: Tambah webp dan ubah max jadi 1024 (1MB)
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        // --- TAMBAHAN: LOGIKA HAPUS FOTO DEFAULT UNTUK ADMIN ---
        if ($request->input('remove_avatar') == '1') {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar); 
            }
            $user->avatar = null; 
        }

        // Jika admin ganti foto profil
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();
        return back()->with('success', 'Profil Admin berhasil diperbarui!');
    }

    // --- UPDATE PASSWORD (Bisa untuk Admin & User) ---
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 8 karakter.'
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password saat ini salah.'])->with('tab', 'keamanan');
        }

        // Simpan password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success_password', 'Password berhasil diganti!')->with('tab', 'keamanan');
    }

    // --- HAPUS AKUN USER ---
    public function destroyUser(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        
        // Hapus foto jika ada
        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        if ($user->ktm_path) Storage::disk('public')->delete($user->ktm_path);
        
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus selamanya.');
    }
}