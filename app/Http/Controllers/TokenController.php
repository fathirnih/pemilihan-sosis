<?php

namespace App\Http\Controllers;

use App\Models\TokenPemilih;
use App\Models\Siswa;
use App\Models\PeriodePemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    /**
     * Show token list (index)
     */
    public function index()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        $tokens = TokenPemilih::with(['periode', 'siswa'])
            ->where('tipe_pemilih', 'siswa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.tokens.index', compact('tokens', 'periode'));
    }

    /**
     * Show create token form
     */
    public function create()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return back()->withErrors(['periode' => 'Tidak ada periode pemilihan yang aktif']);
        }
        return view('admin.tokens.create', compact('periode'));
    }

    /**
     * Store token (create new token)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama siswa harus diisi',
            'nis.required' => 'NIS harus diisi',
        ]);

        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return back()->withErrors(['periode' => 'Tidak ada periode pemilihan yang aktif']);
        }

        // Check if siswa exists, if not create
        $siswa = Siswa::where('nis', $request->nis)->first();
        if (!$siswa) {
            $siswa = Siswa::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'kelas_id' => 1, // Default class
                'aktif' => true,
            ]);
        }

        // Generate unique token
        $token = 'VOTE-' . Str::random(16);
        
        // Create token record
        TokenPemilih::create([
            'periode_id' => $periode->id,
            'tipe_pemilih' => 'siswa',
            'pemilih_id' => $siswa->id,
            'token_hash' => Hash::make($token),
            'token' => $token,
            'status' => 'aktif',
            'sudah_memilih' => false,
            'nama_pemilih' => $request->nama,
            'nis_pemilih' => $request->nis,
        ]);

        return redirect()->route('admin.tokens.index')->with('success', "Token berhasil dibuat untuk {$request->nama}");
    }

    /**
     * Show edit token form
     */
    public function edit($id)
    {
        $token = TokenPemilih::with('siswa')->findOrFail($id);
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        
        return view('admin.tokens.edit', compact('token', 'periode'));
    }

    /**
     * Update token
     */
    public function update(Request $request, $id)
    {
        $token = TokenPemilih::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama siswa harus diisi',
            'nis.required' => 'NIS harus diisi',
        ]);

        // Update token record
        $token->update([
            'nama_pemilih' => $request->nama,
            'nis_pemilih' => $request->nis,
        ]);

        return redirect()->route('admin.tokens.index')->with('success', "Token untuk {$request->nama} berhasil diperbarui");
    }

    /**
     * Show token details
     */
    public function show($id)
    {
        $token = TokenPemilih::with('siswa')->find($id);
        if (!$token) {
            return back()->withErrors(['token' => 'Token tidak ditemukan']);
        }
        return view('admin.tokens.show', compact('token'));
    }

    /**
     * Download token as PDF
     */
    public function downloadPdf($id)
    {
        $token = TokenPemilih::with('siswa')->findOrFail($id);
        
        $pdf = view('admin.tokens.pdf', ['token' => $token])->render();
        
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"token-{$token->nis_pemilih}.pdf\"");
    }

    /**
     * Print token (HTML print)
     */
    public function print($id)
    {
        $token = TokenPemilih::with('siswa')->findOrFail($id);
        return view('admin.tokens.print', compact('token'));
    }

    /**
     * Delete token
     */
    public function destroy($id)
    {
        $token = TokenPemilih::with('siswa')->findOrFail($id);
        $nama = $token->nama_pemilih;
        $token->delete();

        return back()->with('success', "Token untuk {$nama} berhasil dihapus");
    }
}
