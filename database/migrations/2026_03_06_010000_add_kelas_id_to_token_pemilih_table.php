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
        Schema::table('token_pemilih', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable()
                ->after('pemilih_id')
                ->constrained('kelas')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('token_pemilih', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kelas_id');
        });
    }
};
