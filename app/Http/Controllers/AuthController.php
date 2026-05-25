<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

// Memproses data login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            // Pesan Error Bahasa Indonesia untuk Login
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses data pendaftaran (Registrasi)
    public function register(Request $request)
    {
        // 1. Validasi Input + Kamus Terjemahan Error
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
            'ktm' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ], [
            // Pesan Error Bahasa Indonesia untuk Register
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'ktm.image' => 'File KTM harus berupa gambar.',
            'ktm.mimes' => 'Format KTM harus JPG atau PNG.',
            'ktm.max' => 'Ukuran file KTM maksimal 2MB.',
        ]);

        // 2. Upload KTM (Jika ada)
        $ktmPath = null;
        $statusMahasiswa = 'reguler'; 

        if ($request->hasFile('ktm')) {
            $ktmPath = $request->file('ktm')->store('ktm_uploads', 'public');
            $statusMahasiswa = 'menunggu_verifikasi';
        }

        // 3. Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), 
            'role' => 'pelanggan', 
            'status_mahasiswa' => $statusMahasiswa, 
            'ktm_path' => $ktmPath,
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan masuk menggunakan Email Anda.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}