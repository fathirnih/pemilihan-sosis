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
        Schema::create('periode_pemilihan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode');
            $table->dateTime('mulai_pada');
            $table->dateTime('selesai_pada');
            $table->string('status')->default('draf');
            $table->string('mode_pasangan')->default('ketua_wakil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_pemilihan');
    }
};
