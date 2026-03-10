<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;

class LandingController extends Controller
{
    public function index()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first()
            ?? PeriodePemilihan::orderByDesc('mulai_pada')->first();

        $landingKandidats = collect();
        $landingTotalSuara = 0;
        $landingTotalKandidat = 0;

        if ($periode) {
            $landingTotalSuara = $periode->suara()->count();
            $landingTotalKandidat = $periode->kandidat()->count();

            $landingKandidats = $periode->kandidat()
                ->with('anggota.pemilih')
                ->where('tampil_di_landing', true)
                ->orderByRaw('CASE WHEN landing_urutan IS NULL THEN 1 ELSE 0 END')
                ->orderBy('landing_urutan')
                ->orderBy('nomor_urut')
                ->limit(6)
                ->get();

            if ($landingKandidats->isEmpty()) {
                $landingKandidats = $periode->kandidat()
                    ->with('anggota.pemilih')
                    ->orderBy('nomor_urut')
                    ->limit(6)
                    ->get();
            }
        }

        return view('welcome-new', [
            'landingPeriode' => $periode,
            'landingKandidats' => $landingKandidats,
            'landingTotalSuara' => $landingTotalSuara,
            'landingTotalKandidat' => $landingTotalKandidat,
        ]);
    }
}
