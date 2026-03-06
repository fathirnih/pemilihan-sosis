<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use Illuminate\Support\Facades\Session;

class PanitiaController extends Controller
{
    /**
     * Panitia view results (read-only)
     */
    public function viewResults()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return view('panitia.no-periode');
        }

        $kandidats = $periode->kandidat()->with(['anggota.pemilih', 'suara'])->get();
        $totalSuara = $periode->suara()->count();

        return view('panitia.results', compact('periode', 'kandidats', 'totalSuara'));
    }

    /**
     * Panitia logout
     */
    public function logout()
    {
        Session::forget(['panitia_id', 'panitia_username', 'panitia_nama']);
        return redirect()->route('admin.login')->with('success', 'Logout berhasil');
    }
}
