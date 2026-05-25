<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void 
    {
        Schema::create('rutes', function (Blueprint $table) {
            $table->id();
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->integer('harga_reguler');
            $table->integer('harga_mahasiswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutes');
    }
};
