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
        Schema::create('jenis_transaksi', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger("id_namabayar");
            // $table->foreign('id_namabayar')->references('id')->on('nama_transaksi');
            $table->unsignedBigInteger("id_thnajaran");
            $table->foreign('id_thnajaran')->references('id')->on('thn_ajaran')->onDelete('cascade');
            $table->enum('tipe_bayar', ['Bulanan', 'Bebas']);
            $table->timestamps();
        });
    }

    // berarti jika data di tabel thn_ajaran yang dirujuk dihapus, maka data terkait di tabel jenis_transaksi juga akan dihapus.
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_transaksi');
    }
};
