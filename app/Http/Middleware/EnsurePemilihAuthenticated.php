<?php

namespace App\Http\Middleware;

use App\Models\PeriodePemilihan;
use App\Models\TokenPemilih;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsurePemilihAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('pemilih_token_id') || !Session::has('pemilih_periode_id')) {
            return redirect()->route('login');
        }

        $periode = PeriodePemilihan::find(Session::get('pemilih_periode_id'));
        $token = TokenPemilih::find(Session::get('pemilih_token_id'));

        if (
            !$periode ||
            $periode->status !== 'aktif' ||
            !$token ||
            $token->sudah_memilih ||
            $token->status !== 'aktif'
        ) {
            Session::forget(['pemilih_token_id', 'pemilih_periode_id']);
            return redirect()->route('login')->withErrors(['login' => 'Sesi voting sudah tidak valid']);
        }

        return $next($request);
    }
}
