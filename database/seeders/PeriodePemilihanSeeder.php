<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodePemilihan;

class PeriodePemilihanSeeder extends Seeder
{
    public function run(): void
    {
        $periodes = [
            [
                'nama_periode' => 'Pemilihan Ketua OSIS 2026',
                'mulai_pada' => '2026-03-15 08:00:00',
                'selesai_pada' => '2026-03-15 16:00:00',
                'status' => 'draf',
                'mode_pasangan' => 'ketua_wakil',
            ],
            [
                'nama_periode' => 'Pemilihan Ketua OSIS 2027',
                'mulai_pada' => '2027-03-15 08:00:00',
                'selesai_pada' => '2027-03-15 16:00:00',
                'status' => 'draf',
                'mode_pasangan' => 'ketua_wakil',
            ],
        ];

        foreach ($periodes as $periode) {
            PeriodePemilihan::firstOrCreate(
                ['nama_periode' => $periode['nama_periode']],
                $periode
            );
        }
    }
}
