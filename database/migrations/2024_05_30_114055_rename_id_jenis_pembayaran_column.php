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
        Schema::table('jenis_transaksi', function(Blueprint $table) {
            $table->renameColumn('id', 'id_jenis_transaksi');
            $table->renameColumn('id_thnajaran', 'id_thn_ajaran');
            $table->string('nama_pembayaran',50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_transaksi', function(Blueprint $table) {
            $table->renameColumn('id_jenis_transaksi', 'id');
            $table->renameColumn('id_thn_ajaran', 'id_thnajaran');
            $table->dropColumn('nama_pembayaran');
        });
    }
};
