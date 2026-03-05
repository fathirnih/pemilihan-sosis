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
        Schema::create('token_pemilih', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_pemilihan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('tipe_pemilih', ['siswa', 'guru']);
            $table->unsignedBigInteger('pemilih_id');
            $table->string('token_hash', 255)->unique();
            $table->timestamp('digunakan_pada')->nullable();
            $table->timestamp('kadaluarsa_pada')->nullable();
            $table->timestamps();

            $table->unique(['periode_id', 'tipe_pemilih', 'pemilih_id']);
            $table->index(['tipe_pemilih', 'pemilih_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_pemilih');
    }
};
