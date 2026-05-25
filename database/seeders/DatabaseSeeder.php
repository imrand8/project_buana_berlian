<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Armada;
use App\Models\Rute;
use App\Models\Driver;
use App\Models\Jadwal; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'name' => 'Admin Buana', 'email' => 'admin@buanaberlian.com',
            'phone' => '081234567890', 'password' => Hash::make('admin123'), 'role' => 'admin',
        ]);

        // 2. Akun Pelanggan Dummy
        User::create([
            'name' => 'Mas Budi', 'email' => 'budi@gmail.com',
            'phone' => '089876543210', 'password' => Hash::make('123456'), 'role' => 'pelanggan',
        ]);

        // 3. DATA SUPIR 
        $namaSupir = ['Dika', 'Kaka', 'Rama', 'Shandy', 'Eko'];
        foreach ($namaSupir as $index => $nama) {
            Driver::create([
                'nama_supir' => $nama,
                'no_hp' => '08520000000' . $index,
            ]);
        }

        // 4. Data Armada (Dihapus status_operasional-nya biar gak crash)
        $armada1 = Armada::create(['nama_armada' => 'Toyota Innova 1', 'plat_nomor' => 'AE 1111 BB', 'kapasitas_kursi' => 7]);
        $armada2 = Armada::create(['nama_armada' => 'Toyota Innova 2', 'plat_nomor' => 'AE 2222 BB', 'kapasitas_kursi' => 7]);
        $armada3 = Armada::create(['nama_armada' => 'Daihatsu Luxio', 'plat_nomor' => 'AE 3333 BB', 'kapasitas_kursi' => 7]);

        // 5. Data Rute (Diskon Mahasiswa Rp 10.000 sudah diaplikasikan)
        $rute1 = Rute::create(['kota_asal' => 'Malang', 'kota_tujuan' => 'Pacitan', 'harga_reguler' => 180000, 'harga_mahasiswa' => 170000]);
        Rute::create(['kota_asal' => 'Pacitan', 'kota_tujuan' => 'Malang', 'harga_reguler' => 180000, 'harga_mahasiswa' => 170000]);
        
        // 6. Jadwal Dummy
        Jadwal::create([
            'rute_id' => $rute1->id,
            'armada_id' => $armada1->id,
            'driver_id' => 1, // Dika (ID 1)
            'tanggal_berangkat' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'jam_berangkat' => '08:30:00',
            'kursi_tersedia' => 7,
            'status_jadwal' => 'tersedia'
        ]);
    }
}