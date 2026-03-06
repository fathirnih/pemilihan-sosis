<?php

namespace Database\Seeders;

use App\Models\Kandidat;
use App\Models\Pemilih;
use App\Models\PeriodePemilihan;
use App\Models\Suara;
use Illuminate\Database\Seeder;

class SuaraSeeder extends Seeder
{
    /**
     * Seed suara untuk periode aktif.
     */
    public function run(): void
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first() ?? PeriodePemilihan::first();
        if (!$periode) {
            return;
        }

        $kandidats = Kandidat::where('periode_id', $periode->id)->orderBy('nomor_urut')->get();
        if ($kandidats->isEmpty()) {
            return;
        }

        $pemilih = Pemilih::where('aktif', true)
            ->whereNotIn('id', Suara::where('periode_id', $periode->id)->pluck('pemilih_id'))
            ->orderBy('id')
            ->take(15)
            ->get()
            ->values();

        if ($pemilih->isEmpty()) {
            return;
        }

        foreach ($pemilih as $index => $p) {
            $kandidat = $kandidats[$index % $kandidats->count()];
            Suara::create([
                'periode_id' => $periode->id,
                'kandidat_id' => $kandidat->id,
                'pemilih_id' => $p->id,
            ]);
        }
    }
}
