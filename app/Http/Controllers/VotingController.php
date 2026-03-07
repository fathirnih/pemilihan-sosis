<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use App\Models\Suara;
use App\Models\TokenPemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

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
        $periodeId = Session::get('pemilih_periode_id');

        $request->validate([
            'kandidat_id' => [
                'required',
                Rule::exists('kandidat', 'id')->where('periode_id', $periodeId),
            ],
        ]);

        $tokenId = Session::get('pemilih_token_id');
        $periode = PeriodePemilihan::findOrFail($periodeId);

        if ($periode->status !== 'aktif') {
            Session::forget(['pemilih_token_id', 'pemilih_periode_id']);
            return redirect()->route('login')->withErrors(['login' => 'Periode pemilihan sudah ditutup']);
        }

        $response = DB::transaction(function () use ($tokenId, $periodeId, $request) {
            $tokenRecord = TokenPemilih::whereKey($tokenId)->lockForUpdate()->firstOrFail();

            if (
                $tokenRecord->periode_id !== (int) $periodeId ||
                !$tokenRecord->pemilih_id ||
                $tokenRecord->status !== 'aktif'
            ) {
                return redirect()->route('login')->withErrors(['login' => 'Sesi voting tidak valid']);
            }

            $existingVote = Suara::where('periode_id', $periodeId)
                ->where('pemilih_id', $tokenRecord->pemilih_id)
                ->first();

            if ($existingVote) {
                if (!$tokenRecord->sudah_memilih || $tokenRecord->status !== 'digunakan') {
                    $tokenRecord->update([
                        'sudah_memilih' => true,
                        'status' => 'digunakan',
                        'digunakan_pada' => $tokenRecord->digunakan_pada ?? now(),
                    ]);
                }

                return view('voting.confirmation', ['suara' => $existingVote]);
            }

            $suara = Suara::create([
                'periode_id' => $periodeId,
                'kandidat_id' => $request->kandidat_id,
                'pemilih_id' => $tokenRecord->pemilih_id,
            ]);

            $tokenRecord->update([
                'sudah_memilih' => true,
                'status' => 'digunakan',
                'digunakan_pada' => now(),
            ]);

            return view('voting.confirmation', compact('suara'));
        });

        Session::forget(['pemilih_token_id', 'pemilih_periode_id']);

        return $response;
    }
}
