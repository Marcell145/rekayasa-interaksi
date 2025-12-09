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
    Schema::create('jadwals', function (Blueprint $table) {
        $table->id();
        $table->string('NIM');
        $table->string('id_matkul_lama');
        $table->string('id_matkul_baru');

        $table->string('nama_dosen');
        $table->string('ruang');
        $table->string('jam');
        $table->timestamps();

        $table->foreign('NIM')
            ->references('NIM')
            ->on('users')
            ->onDelete('cascade');

        // Relasi id_matkul
        $table->foreign('id_matkul_lama')
            ->references('id_matkul')
            ->on('k_r_s')
            ->onDelete('cascade');

        // Relasi id_matkul_baru
        $table->foreign('id_matkul_baru')
            ->references('id_matkul')
            ->on('k_r_s')
            ->onDelete('cascade');
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
