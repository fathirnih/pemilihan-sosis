<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use App\Models\PeriodePemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PanitiaController extends Controller
{
    /**
     * Show panitia login form
     */
    public function showLogin()
    {
        return view('panitia.login');
    }

    /**
     * Process panitia login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $panitia = Panitia::findByUsername($request->username);

        if ($panitia && $panitia->matchesPassword($request->password)) {
            Session::put('panitia_id', $panitia->id);
            Session::put('panitia_username', $panitia->username);
            Session::put('panitia_nama', $panitia->nama);
            return redirect()->route('panitia.results');
        }

        return back()->withErrors(['credentials' => 'Username atau password salah']);
    }

    /**
     * Panitia view results (read-only)
     */
    public function viewResults()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return view('panitia.no-periode');
        }

        $kandidats = $periode->kandidat()->with(['anggota.siswa', 'suara'])->get();
        $totalSuara = $periode->suara()->count();

        return view('panitia.results', compact('periode', 'kandidats', 'totalSuara'));
    }

    /**
     * Panitia logout
     */
    public function logout()
    {
        Session::forget(['panitia_id', 'panitia_username', 'panitia_nama']);
        return redirect()->route('panitia.login')->with('success', 'Logout berhasil');
    }
}
