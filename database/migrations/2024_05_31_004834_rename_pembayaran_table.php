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
        // Schema::rename('transaksi', 'transaksi');
        Schema::table('transaksi', function(Blueprint $table) {
            $table->renameColumn('id_transaksi', 'id_transaksi');
            $table->renameColumn('id_jenistransaksi', 'id_jenis_transaksi');
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
