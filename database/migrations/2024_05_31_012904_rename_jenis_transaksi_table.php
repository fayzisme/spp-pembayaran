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
        Schema::rename('jenis_transaksi', 'jenis_pembayaran');
        Schema::table('jenis_pembayaran', function(Blueprint $table) {
            $table->renameColumn('id_jenis_transaksi', 'id_jenis_pembayaran');
        });

        Schema::table('tarif', function(Blueprint $table) {
            $table->renameColumn('id_jenis_transaksi', 'id_jenis_pembayaran');
        });

        Schema::table('transaksi', function(Blueprint $table) {
            $table->renameColumn('id_jenis_transaksi', 'id_jenis_pembayaran');
        });

        Schema::table('detail_transaksi', function(Blueprint $table) {
            $table->renameColumn('id_jenis_transaksi', 'id_jenis_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('jenis_pembayaran', 'jenis_transaksi');
        Schema::table('jenis_transaksi', function(Blueprint $table) {
            $table->renameColumn('id_jenis_pembayaran', 'id_jenis_transaksi',);
        });

        Schema::table('tarif', function(Blueprint $table) {
            $table->renameColumn('id_jenis_pembayaran', 'id_jenis_transaksi',);
        });

        Schema::table('transaksi', function(Blueprint $table) {
            $table->renameColumn('id_jenis_pembayaran', 'id_jenis_transaksi',);
        });

        Schema::table('detail_transaksi', function(Blueprint $table) {
            $table->renameColumn('id_jenis_pembayaran', 'id_jenis_transaksi',);
        });
    }
};
