<?php

namespace App\Http\Controllers;

use App\Models\TokenPemilih;
use App\Models\PeriodePemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'token' => 'nullable|string',
            'nis' => 'nullable|string',
        ], [
            'token.required' => 'Token atau NIS harus diisi',
        ]);

        // Check if either token or NIS is provided
        if (!$request->token && !$request->nis) {
            return back()->withErrors(['login' => 'Token atau NIS harus diisi']);
        }

        // Find token by either token code or NIS
        $query = TokenPemilih::where('status', 'aktif');
        
        if ($request->token) {
            $query->where('token', $request->token);
        } else {
            $query->where('nis_pemilih', $request->nis);
        }
        
        $tokenRecord = $query->first();

        if (!$tokenRecord) {
            $fieldName = $request->token ? 'Token' : 'NIS';
            return back()->withErrors(['login' => "$fieldName tidak valid atau sudah digunakan"]);
        }

        // Check if token has already voted
        if ($tokenRecord->sudah_memilih) {
            return back()->withErrors(['login' => 'Anda sudah melakukan pemilihan']);
        }

        // Check if voting period is active
        $periode = $tokenRecord->periode;
        if (!$periode || $periode->status !== 'aktif') {
            return back()->withErrors(['login' => 'Periode pemilihan tidak aktif']);
        }

        // Store session
        Session::put('pemilih_token_id', $tokenRecord->id);
        Session::put('pemilih_periode_id', $periode->id);

        return redirect()->route('voting.index');
    }

    public function logout(Request $request)
    {
        Session::forget(['pemilih_token_id', 'pemilih_periode_id']);
        return redirect()->route('login');
    }
}
