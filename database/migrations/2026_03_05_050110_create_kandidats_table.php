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
        Schema::create('kandidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_pemilihan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('nomor_urut');
            $table->string('visi', 500);
            $table->string('misi', 1500);
            $table->string('foto', 255)->nullable();
            $table->timestamps();

            $table->unique(['periode_id', 'nomor_urut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandidat');
    }
};
