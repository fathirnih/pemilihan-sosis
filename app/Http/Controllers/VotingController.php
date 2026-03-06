<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use App\Models\Kandidat;
use App\Models\Suara;
use App\Models\TokenPemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VotingController extends Controller
{
    public function index()
    {
        $periodeId = Session::get('pemilih_periode_id');
        $periode = PeriodePemilihan::findOrFail($periodeId);
        $kandidats = $periode->kandidat()->with('anggota.pemilih')->get();

        return view('voting.index', compact('periode', 'kandidats'));
    }

    public function status()
    {
        return response()->json(['active' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kandidat_id' => 'required|exists:kandidat,id',
        ]);

        $tokenId = Session::get('pemilih_token_id');
        $periodeId = Session::get('pemilih_periode_id');
        $tokenRecord = TokenPemilih::findOrFail($tokenId);
        $periode = PeriodePemilihan::findOrFail($periodeId);

        if ($periode->status !== 'aktif') {
            Session::forget(['pemilih_token_id', 'pemilih_periode_id']);
            return redirect()->route('login')->withErrors(['login' => 'Periode pemilihan sudah ditutup']);
        }

        if ($tokenRecord->sudah_memilih || $tokenRecord->status !== 'aktif') {
            Session::forget(['pemilih_token_id', 'pemilih_periode_id']);
            return redirect()->route('login')->withErrors(['login' => 'Token sudah digunakan']);
        }

        // Create vote record
        $suara = Suara::create([
            'periode_id' => $periodeId,
            'kandidat_id' => $request->kandidat_id,
            'pemilih_id' => $tokenRecord->pemilih_id,
        ]);

        // Mark token as used
        $tokenRecord->update([
            'sudah_memilih' => true,
            'status' => 'digunakan',
            'digunakan_pada' => now(),
        ]);

        Session::forget(['pemilih_token_id', 'pemilih_periode_id']);

        return view('voting.confirmation', compact('suara'));
    }
}
