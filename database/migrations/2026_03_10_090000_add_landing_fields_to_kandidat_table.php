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
        Schema::table('kandidat', function (Blueprint $table) {
            $table->boolean('tampil_di_landing')->default(true)->after('foto_wakil');
            $table->unsignedInteger('landing_urutan')->nullable()->after('tampil_di_landing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kandidat', function (Blueprint $table) {
            $table->dropColumn(['tampil_di_landing', 'landing_urutan']);
        });
    }
};

