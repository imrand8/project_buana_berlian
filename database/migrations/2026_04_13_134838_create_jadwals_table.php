<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rutes')->cascadeOnDelete();
            $table->foreignId('armada_id')->constrained('armadas')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            
            $table->date('tanggal_berangkat');
            $table->time('jam_berangkat'); // Isinya nanti: 08:30:00 atau 20:00:00
            
            $table->integer('kursi_tersedia');
            $table->enum('status_jadwal', ['tersedia', 'penuh', 'berangkat', 'selesai', 'dibatalkan'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
