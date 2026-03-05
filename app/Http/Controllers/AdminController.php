<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\PeriodePemilihan;
use App\Models\Kandidat;
use App\Models\TokenPemilih;
use App\Models\Suara;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Process admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::findByUsername($request->username);

        if ($admin && $admin->matchesPassword($request->password)) {
            Session::put('admin_id', $admin->id);
            Session::put('admin_username', $admin->username);
            Session::put('admin_nama', $admin->nama);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['credentials' => 'Username atau password salah']);
    }

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

        $kandidats = [];
        if ($periode) {
            $kandidats = $periode->kandidat()->with(['anggota.siswa', 'suara'])->get();
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

        $tokens = [];
        for ($i = 0; $i < $request->jumlah; $i++) {
            $token = 'VOTE-' . Str::random(16);
            TokenPemilih::create([
                'periode_id' => $periode->id,
                'tipe_pemilih' => $request->tipe_pemilih,
                'pemilih_id' => 0, // Will be assigned per voter
                'kelas_id' => $request->tipe_pemilih === 'siswa' ? $request->kelas_id : null,
                'token_hash' => Hash::make($token),
                'token' => $token,
                'status' => 'aktif',
                'sudah_memilih' => false,
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
