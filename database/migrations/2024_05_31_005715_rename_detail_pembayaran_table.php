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
        // Schema::rename('detail_transaksi', 'detail_transaksi');
        Schema::table('detail_transaksi', function(Blueprint $table) {
            $table->renameColumn('id_detail', 'id_detail_transaksi');
            $table->renameColumn('id_transaksifk', 'id_transaksi');
            $table->renameColumn('id_jenistransaksi', 'id_jenis_transaksi');
            // $table->renameColumn('status_transaksi', 'status_transaksi');
            $table->renameColumn('jumlah_transaksi', 'jumlah_transaksi');
            $table->renameColumn('tgl_transaksi', 'tgl_transaksi');
            $table->renameColumn('snap_url', 'snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
