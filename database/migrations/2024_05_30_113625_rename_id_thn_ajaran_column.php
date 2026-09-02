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
        Schema::table('thn_ajaran', function(Blueprint $table) {
            $table->renameColumn('id', 'id_thn_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thn_ajaran', function(Blueprint $table) {
            $table->renameColumn('id_thn_ajaran', 'id');
        });
    }
};
