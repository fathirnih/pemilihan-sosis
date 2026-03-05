<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use App\Models\Suara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResultsController extends Controller
{
    public function index()
    {
        $periodeId = Session::get('pemilih_periode_id');
        $periode = PeriodePemilihan::findOrFail($periodeId);
        $kandidats = $periode->kandidat()->with(['anggota.siswa', 'suara'])->get();
        $totalSuara = $periode->suara()->count();

        return view('results.index', compact('periode', 'kandidats', 'totalSuara'));
    }
}
