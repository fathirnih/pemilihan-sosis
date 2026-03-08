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
        Schema::create('pemilih', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 30);
            $table->string('nama', 100);
            $table->enum('jenis', ['siswa', 'guru']);
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedBigInteger('periode_pemilihan_id')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            // Composite unique: NISN unik per periode
            $table->unique(['nisn', 'periode_pemilihan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilih');
    }
};
