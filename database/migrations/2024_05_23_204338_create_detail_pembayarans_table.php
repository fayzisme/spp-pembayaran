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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksifk');
            $table->foreign('id_transaksifk')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
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
            $table->enum('status_transaksi', ['Sukses', 'Pending', 'Gagal']);
            $table->string('jumlah_transaksi', 20);
            $table->date('tgl_transaksi');
            $table->enum('bulan', ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'])->nullable();
            $table->string('snap_url', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
