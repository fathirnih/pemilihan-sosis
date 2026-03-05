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
            $table->string('token')->unique()->after('pemilih_id');
            $table->enum('status', ['aktif', 'digunakan', 'kadaluarsa'])->default('aktif')->after('token');
            $table->boolean('sudah_memilih')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('token_pemilih', function (Blueprint $table) {
            $table->dropColumn(['token', 'status', 'sudah_memilih']);
        });
    }
};
