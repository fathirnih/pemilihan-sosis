<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $programs = ['RPL', 'IPA'];
        $rombel = [1, 2];

        foreach ([1, 2, 3] as $tingkat) {
            foreach ($programs as $program) {
                foreach ($rombel as $no) {
                    $nama = $program . ' ' . $no;
                    Kelas::updateOrCreate(
                        ['nama_kelas' => $nama, 'tingkat' => $tingkat],
                        ['nama_kelas' => $nama, 'tingkat' => $tingkat]
                    );
                }
            }
        }
    }
}
