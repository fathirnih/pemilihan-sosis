<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;

class PemilihSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

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

        $now = now();
        $pemilihRows = [];

        foreach ($siswaNames as $index => $name) {
            $kelas = $kelasList[$index % max(1, $kelasList->count())] ?? null;
            $pemilihRows[] = [
                'nisn' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'nama' => $name,
                'jenis' => 'siswa',
                'kelas_id' => $kelas?->id,
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $guruNames = ['Bu Rini', 'Pak Damar'];
        foreach ($guruNames as $index => $name) {
            $pemilihRows[] = [
                'nisn' =>  str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'nama' => $name,
                'jenis' => 'guru',
                'kelas_id' => null,
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($pemilihRows as $row) {
            DB::table('pemilih')->updateOrInsert(
                ['nisn' => $row['nisn']],
                $row
            );
        }
    }
}
