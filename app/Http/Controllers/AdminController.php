<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use App\Models\Kandidat;
use App\Models\TokenPemilih;
use App\Models\Suara;
use App\Models\Kelas;
use App\Models\Pemilih;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        $totalTokens = TokenPemilih::count();
        $tokensAktif = TokenPemilih::where('status', 'aktif')->count();
        $sudahMemilih = TokenPemilih::where('sudah_memilih', true)->count();
        $totalSuara = Suara::count();

        $kandidats = collect();
        if ($periode) {
            $kandidats = $periode->kandidat()->with(['anggota.pemilih', 'suara'])->get();
        }

        return view('admin.dashboard', compact('periode', 'totalTokens', 'tokensAktif', 'sudahMemilih', 'totalSuara', 'kandidats'));
    }

    /**
     * Show generate token form
     */
    public function showGenerateToken()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.generate-token', compact('periode', 'kelasList'));
    }

    /**
     * Generate new tokens
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:100',
            'tipe_pemilih' => 'required|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        if ($request->tipe_pemilih === 'siswa' && !$request->filled('kelas_id')) {
            return back()->withErrors(['kelas_id' => 'Kelas wajib dipilih untuk token siswa'])->withInput();
        }

        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return back()->withErrors(['periode' => 'Tidak ada periode pemilihan yang aktif']);
        }

        $pemilihQuery = Pemilih::where('jenis', $request->tipe_pemilih)
            ->where('aktif', true);
        if ($request->tipe_pemilih === 'siswa') {
            $pemilihQuery->where('kelas_id', $request->kelas_id);
        }

        $alreadyHasToken = TokenPemilih::where('periode_id', $periode->id)
            ->whereNotNull('pemilih_id')
            ->pluck('pemilih_id')
            ->all();

        $pemilihList = $pemilihQuery
            ->whereNotIn('id', $alreadyHasToken)
            ->limit($request->jumlah)
            ->get();

        if ($pemilihList->isEmpty()) {
            return back()->withErrors(['jumlah' => 'Tidak ada pemilih yang bisa dibuatkan token (sudah punya token atau data kosong).']);
        }

        $tokens = [];
        foreach ($pemilihList as $pemilih) {
            $token = 'VOTE-' . Str::random(16);
            TokenPemilih::create([
                'periode_id' => $periode->id,
                'pemilih_id' => $pemilih->id,
                'kelas_id' => $pemilih->kelas_id,
                'token_hash' => Hash::make($token),
                'token' => $token,
                'status' => 'aktif',
                'sudah_memilih' => false,
                'nama_pemilih' => $pemilih->nama,
                'nis_pemilih' => $pemilih->nisn,
            ]);
            $tokens[] = $token;
        }

        return view('admin.tokens-generated', ['tokens' => $tokens, 'jumlah' => $request->jumlah]);
    }

    /**
     * Manage voting period
     */
    public function managePeriode()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        $allPeriodes = PeriodePemilihan::all();
        return view('admin.manage-periode', compact('periode', 'allPeriodes'));
    }

    /**
     * Toggle period status
     */
    public function togglePeriode($id)
    {
        $periode = PeriodePemilihan::findOrFail($id);
        $newStatus = $periode->status === 'aktif' ? 'ditutup' : 'aktif';
        $periode->update(['status' => $newStatus]);

        return back()->with('success', 'Status periode berhasil diubah menjadi ' . $newStatus);
    }

    /**
     * Admin logout
     */
    public function logout()
    {
        Session::forget(['admin_id', 'admin_username', 'admin_nama']);
        return redirect()->route('admin.login')->with('success', 'Logout berhasil');
    }
}
