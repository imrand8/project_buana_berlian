<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_travels', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Akun yang login/pesan
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->nullOnDelete();
            
            // --- DATA PERJALANAN & IDENTITAS (Sesuai Form UI) ---
            $table->string('nama_penumpang'); // Sesuai KTP
            $table->string('nomor_wa'); // WA Aktif
            $table->text('titik_jemput');
            $table->text('titik_antar');
            $table->text('keterangan_barang')->nullable(); // Opsional (Contoh: 1 Koper besar)
            $table->string('nomor_kursi'); // Menyimpan kursi yang dipilih, contoh: "5", atau "2, 3"
            $table->integer('jumlah_kursi'); 
            $table->integer('total_harga');

            // --- METODE PEMBAYARAN & STATUS ---
            $table->enum('metode_bayar', ['BSI', 'DANA', 'CASH'])->nullable();
            $table->string('bukti_transfer')->nullable(); // Foto upload bukti TF
            $table->string('status_pesanan')->default('menunggu_pembayaran');
            
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete(); // Admin yang nge-klik ACC
            $table->text('alasan_tolak')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_travel');
    }
};
