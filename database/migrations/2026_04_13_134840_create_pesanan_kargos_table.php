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
        Schema::create('pesanan_kargos', function (Blueprint $table) {
            $table->id();
            $table->string('kode_resi')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('tanggal_berangkat');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->onDelete('set null');
            $table->string('jam_berangkat');
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->integer('berat_barang');
            $table->string('nama_pengirim');
            $table->string('nomor_wa_pengirim');
            $table->string('nama_penerima');
            $table->string('nomor_wa_penerima');
            $table->text('keterangan_barang');
            $table->integer('total_harga');
            $table->string('metode_bayar');
            $table->string('bukti_transfer')->nullable();
            $table->string('status_pesanan')->default('menunggu_verifikasi');
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_kargos');
    }
};
