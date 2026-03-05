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
        Schema::create('suara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_pemilihan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('kandidat_id')->constrained('kandidat')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('tipe_pemilih');
            $table->unsignedBigInteger('pemilih_id');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['periode_id', 'tipe_pemilih', 'pemilih_id']);
            $table->index(['tipe_pemilih', 'pemilih_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suara');
    }
};
