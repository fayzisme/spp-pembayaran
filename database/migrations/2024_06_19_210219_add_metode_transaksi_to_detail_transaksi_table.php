<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // $table->enum('metode_transaksi', ['online', 'manual']);
            // $table->enum('metode_transaksi', ['tunai', 'manual']);
            $table->enum('metode_transaksi', ['Online', 'Tunai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropColumn('metode_transaksi');
        });
    }
};
