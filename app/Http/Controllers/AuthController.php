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
            'token' => 'required|string',
            'nis' => 'required|string',
        ], [
            'token.required' => 'Token wajib diisi',
            'nis.required' => 'NIS/NIP wajib diisi',
        ]);

        // NIS/NIP dan token harus cocok pada record token yang sama
        $tokenRecord = TokenPemilih::where('status', 'aktif')
            ->where('token', $request->token)
            ->where('nis_pemilih', $request->nis)
            ->first();

        if (!$tokenRecord) {
            return back()->withErrors(['login' => 'NIS/NIP atau token tidak valid, atau token sudah digunakan']);
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
