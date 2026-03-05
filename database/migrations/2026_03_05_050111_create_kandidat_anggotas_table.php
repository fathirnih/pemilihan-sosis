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
        Schema::create('kandidat_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandidat_id')->constrained('kandidat')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('peran', ['ketua', 'wakil']);
            $table->timestamps();

            $table->unique(['kandidat_id', 'peran']);
            $table->unique(['kandidat_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandidat_anggota');
    }
};
