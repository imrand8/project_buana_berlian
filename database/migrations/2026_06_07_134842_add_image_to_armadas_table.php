<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('armadas', function (Blueprint $table) {
        // Menambahkan kolom image, nullable karena opsional
        $table->string('image')->nullable()->after('plat_nomor'); 
    });
}

public function down()
{
    Schema::table('armadas', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
