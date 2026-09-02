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
        Schema::create('thn_ajaran', function (Blueprint $table) {
            $table->id('id');
            $table->string('thn_ajaran', 20);
            $table->enum('semester', ['Genap', 'Ganjil']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thn_ajaran');
    }
};
