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
        Schema::create('tarif_kargos', function (Blueprint $table) {
            $table->id();
            $table->integer('harga_dasar')->default(50000); // 1 Kg pertama
            $table->integer('harga_selanjutnya')->default(25000); // Per Kg berikutnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_kargos');
    }
};
