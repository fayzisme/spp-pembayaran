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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_kelas');
            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
            $table->unsignedBigInteger('id_tarif');
            $table->foreign('id_tarif')->references('id')->on('tarif')->onDelete('cascade');
            $table->unsignedBigInteger('id_siswa');
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');
            $table->unsignedBigInteger('id_jenistransaksi');
            $table->foreign('id_jenistransaksi')->references('id')->on('jenis_transaksi')->onDelete('cascade');
            $table->unsignedBigInteger('id_thn_ajaran');
            $table->foreign('id_thn_ajaran')->references('id')->on('thn_ajaran')->onDelete('cascade');
            // $table->unsignedBigInteger('id_namabayar');
            // $table->foreign('id_namabayar')->references('id')->on('nama_transaksi')->onDelete('cascade');
            $table->string('invoice',100);
            $table->string('total_bayar',100)->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
