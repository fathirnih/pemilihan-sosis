<?php

namespace Database\Seeders;

use App\Models\Kandidat;
use App\Models\KandidatAnggota;
use App\Models\Pemilih;
use App\Models\PeriodePemilihan;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class KandidatSeeder extends Seeder
{
    /**
     * Seed kandidat (total 3).
     */
    public function run(): void
    {
        $periode = PeriodePemilihan::first();
        if (!$periode) {
            $periode = PeriodePemilihan::create([
                'nama_periode' => 'Periode 2026',
                'mulai_pada' => Carbon::now()->subDay(),
                'selesai_pada' => Carbon::now()->addDays(7),
                'status' => 'aktif',
                'mode_pasangan' => 'ketua_wakil',
            ]);
        }

        $pemilih = Pemilih::where('jenis', 'siswa')
            ->orderBy('id')
            ->take(6)
            ->get()
            ->values();

        $kelasId = Kelas::orderBy('id')->value('id');

        if ($pemilih->count() < 6) {
            $needed = 6 - $pemilih->count();
            for ($i = 0; $i < $needed; $i++) {
                $pemilih->push(Pemilih::create([
                    'nisn' => 'AUTO' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'nama' => 'Pemilih Auto ' . ($i + 1),
                    'jenis' => 'siswa',
                    'kelas_id' => $kelasId,
                    'aktif' => true,
                ]));
            }
        }

        $pairs = [
            ['ketua' => $pemilih[0], 'wakil' => $pemilih[1]],
            ['ketua' => $pemilih[2], 'wakil' => $pemilih[3]],
            ['ketua' => $pemilih[4], 'wakil' => $pemilih[5]],
        ];

        foreach ([1, 2, 3] as $index => $nomor) {
            $kandidat = Kandidat::create([
                'periode_id' => $periode->id,
                'nomor_urut' => $nomor,
                'visi' => 'Visi kandidat ' . $nomor,
                'misi' => 'Misi kandidat ' . $nomor,
                'foto' => null,
            ]);

            KandidatAnggota::create([
                'kandidat_id' => $kandidat->id,
                'pemilih_id' => $pairs[$index]['ketua']->id,
                'peran' => 'ketua',
            ]);

            KandidatAnggota::create([
                'kandidat_id' => $kandidat->id,
                'pemilih_id' => $pairs[$index]['wakil']->id,
                'peran' => 'wakil',
            ]);
        }
    }
}
