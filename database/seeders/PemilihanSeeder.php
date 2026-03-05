<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodePemilihan;
use App\Models\Kandidat;
use App\Models\KandidatAnggota;
use App\Models\TokenPemilih;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Str;

class PemilihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create class
        $kelas = Kelas::updateOrCreate(
            ['nama_kelas' => '12 IPA 1'],
            ['nama_kelas' => '12 IPA 1', 'tingkat' => 12]
        );

        // Create students
        $siswaNames = ['Ahmad Rahman', 'Budi Santoso', 'Citra Dewi', 'Dina Kusuma', 'Eka Putri', 'Farhan Aziz', 'Gita Sari'];
        $siswaList = [];
        foreach ($siswaNames as $index => $name) {
            $siswa = Siswa::updateOrCreate(
                ['nis' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'nama' => $name,
                    'kelas_id' => $kelas->id,
                    'aktif' => true,
                ]
            );
            $siswaList[] = $siswa;
        }

        // Create voting period
        $periode = PeriodePemilihan::updateOrCreate(
            ['nama_periode' => 'Pemilihan OSIS 2026'],
            [
                'nama_periode' => 'Pemilihan OSIS 2026',
                'mulai_pada' => now(),
                'selesai_pada' => now()->addDays(7),
                'status' => 'aktif',
                'mode_pasangan' => 'ketua_wakil',
            ]
        );

        // Create candidates
        $candidateData = [
            [
                'nomor_urut' => 1,
                'visi' => 'Membangun OSIS yang responsif terhadap kebutuhan siswa',
                'misi' => 'Meningkatkan program kegiatan yang bermanfaat, transparansi kegiatan, dan komunikasi dengan siswa',
                'anggota' => [
                    ['siswa_id' => $siswaList[0]->id, 'peran' => 'ketua'],
                    ['siswa_id' => $siswaList[1]->id, 'peran' => 'wakil'],
                ]
            ],
            [
                'nomor_urut' => 2,
                'visi' => 'OSIS yang inovatif dan progresif untuk kemajuan bersama',
                'misi' => 'Mendorong kreativitas siswa, pengembangan skill, dan kepemimpinan yang berkualitas',
                'anggota' => [
                    ['siswa_id' => $siswaList[2]->id, 'peran' => 'ketua'],
                    ['siswa_id' => $siswaList[3]->id, 'peran' => 'wakil'],
                ]
            ],
            [
                'nomor_urut' => 3,
                'visi' => 'OSIS yang peduli dan dekat dengan semua siswa',
                'misi' => 'Meningkatkan kesejahteraan siswa melalui program sosial dan komunikasi yang baik',
                'anggota' => [
                    ['siswa_id' => $siswaList[4]->id, 'peran' => 'ketua'],
                    ['siswa_id' => $siswaList[5]->id, 'peran' => 'wakil'],
                ]
            ],
        ];

        foreach ($candidateData as $data) {
            $kandidat = Kandidat::updateOrCreate(
                ['periode_id' => $periode->id, 'nomor_urut' => $data['nomor_urut']],
                [
                    'periode_id' => $periode->id,
                    'nomor_urut' => $data['nomor_urut'],
                    'visi' => $data['visi'],
                    'misi' => $data['misi'],
                    'foto' => null,
                ]
            );

            // Delete existing anggota
            KandidatAnggota::where('kandidat_id', $kandidat->id)->delete();

            // Create anggota
            foreach ($data['anggota'] as $anggota) {
                KandidatAnggota::create([
                    'kandidat_id' => $kandidat->id,
                    'siswa_id' => $anggota['siswa_id'],
                    'peran' => $anggota['peran'],
                ]);
            }
        }

        // Create voter tokens
        $lastSiswa = $siswaList[6];
        $testToken = 'TEST-' . Str::random(12);
        TokenPemilih::updateOrCreate(
            ['periode_id' => $periode->id, 'tipe_pemilih' => 'siswa', 'pemilih_id' => $lastSiswa->id],
            [
                'periode_id' => $periode->id,
                'tipe_pemilih' => 'siswa',
                'pemilih_id' => $lastSiswa->id,
                'token_hash' => hash('sha256', $testToken),
                'token' => $testToken,
                'status' => 'aktif',
                'sudah_memilih' => false,
                'digunakan_pada' => null,
                'kadaluarsa_pada' => now()->addDays(7),
            ]
        );

        $this->command->info('Pemilihan OSIS seeder completed successfully!');
        $this->command->info('Test token: ' . $testToken);
    }
}
