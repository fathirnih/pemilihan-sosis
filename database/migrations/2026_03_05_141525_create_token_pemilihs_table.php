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
            $table->foreignId('pemilih_id')->nullable()->constrained('pemilih')->cascadeOnUpdate()->nullOnDelete();
            $table->string('token_hash', 255)->unique();
            $table->timestamp('digunakan_pada')->nullable();
            $table->timestamp('kadaluarsa_pada')->nullable();
            $table->timestamps();

            $table->unique(['periode_id', 'pemilih_id']);
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
