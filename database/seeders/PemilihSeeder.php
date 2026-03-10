<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\PeriodePemilihan;

class PemilihSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $periodeList = PeriodePemilihan::orderBy('id')->limit(2)->get();

        if ($periodeList->count() < 2) {
            $this->command->warn('Minimal harus ada 2 periode. Jalankan PeriodePemilihanSeeder terlebih dahulu.');
            return;
        }

        $siswaNames = [
            'Ahmad Rahman',
            'Budi Santoso',
            'Citra Dewi',
            'Dina Kusuma',
            'Eka Putri',
            'Farhan Aziz',
            'Gita Sari',
            'Hana Putri',
        ];

        $guruNames = ['Bu Rini', 'Pak Damar'];

        $now = now();

        // Buat pemilih untuk setiap periode dengan NISN yang sama
        foreach ($periodeList as $periode) {
            $pemilihRows = [];

            // Siswa
            foreach ($siswaNames as $index => $name) {
                $kelas = $kelasList[$index % max(1, $kelasList->count())] ?? null;
                $pemilihRows[] = [
                    'nisn' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'nama' => $name,
                    'jenis' => 'siswa',
                    'kelas_id' => $kelas?->id,
                    'periode_pemilihan_id' => $periode->id,
                    'aktif' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Guru
            foreach ($guruNames as $index => $name) {
                $pemilihRows[] = [
                    'nisn' => str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    'nama' => $name,
                    'jenis' => 'guru',
                    'kelas_id' => null,
                    'periode_pemilihan_id' => $periode->id,
                    'aktif' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert dengan composite unique key
            foreach ($pemilihRows as $row) {
                DB::table('pemilih')->updateOrInsert(
                    [
                        'nisn' => $row['nisn'],
                        'periode_pemilihan_id' => $row['periode_pemilihan_id']
                    ],
                    $row
                );
            }
        }
    }
}

