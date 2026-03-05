<?php

namespace App\Http\Middleware;

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

        return $next($request);
    }
}
