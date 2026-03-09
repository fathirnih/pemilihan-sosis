<?php

namespace App\Http\Controllers;

use App\Models\TokenPemilih;
use App\Models\Pemilih;
use App\Models\Kelas;
use App\Models\PeriodePemilihan;
use App\Models\Suara;
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
        $periodeId = $periode?->id ?? -1;
        $pemilih = Pemilih::with([
            'kelas',
            'tokens' => function ($query) use ($periodeId) {
                $query->where('periode_id', $periodeId);
            }
        ])
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.tokens.index', compact('pemilih', 'periode'));
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
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        if ($kelasList->isEmpty()) {
            return redirect()->route('admin.kelas.index')
                ->withErrors(['kelas' => 'Data kelas belum ada. Tambahkan kelas terlebih dahulu']);
        }
        return view('admin.tokens.create', compact('periode', 'kelasList'));
    }

    /**
     * Store token (create new token)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:20',
            'kelas_id' => 'required|exists:kelas,id',
        ], [
            'nama.required' => 'Nama siswa harus diisi',
            'nis.required' => 'NIS harus diisi',
            'kelas_id.required' => 'Kelas harus dipilih',
        ]);

        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return back()->withErrors(['periode' => 'Tidak ada periode pemilihan yang aktif']);
        }

        // Check if pemilih (siswa) exists, if not create
        $pemilih = Pemilih::where('nisn', $request->nis)->where('jenis', 'siswa')->first();
        if (!$pemilih) {
            $pemilih = Pemilih::create([
                'nama' => $request->nama,
                'nisn' => $request->nis,
                'jenis' => 'siswa',
                'kelas_id' => $request->kelas_id,
                'aktif' => true,
            ]);
        } else {
            $pemilih->update([
                'nama' => $request->nama,
                'kelas_id' => $request->kelas_id,
                'aktif' => true,
            ]);
        }

        // Generate unique token
        $token = strtoupper(Str::random(8));
        
        // Create token record
        TokenPemilih::create([
            'periode_id' => $periode->id,
            'pemilih_id' => $pemilih->id,
            'kelas_id' => $request->kelas_id,
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
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        if ($kelasList->isEmpty()) {
            return redirect()->route('admin.kelas.index')
                ->withErrors(['kelas' => 'Data kelas belum ada. Tambahkan kelas terlebih dahulu']);
        }

        return view('admin.tokens.edit', compact('token', 'periode', 'kelasList'));
    }

    /**
     * Update token
     */
    public function update(Request $request, $id)
    {
        $token = TokenPemilih::findOrFail($id);

        $nisRule = 'required|string|max:20';
        if ($token->siswa) {
            $nisRule .= '|unique:pemilih,nisn,' . $token->siswa->id;
        } else {
            $nisRule .= '|unique:pemilih,nisn';
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => $nisRule,
            'kelas_id' => 'required|exists:kelas,id',
        ], [
            'nama.required' => 'Nama siswa harus diisi',
            'nis.required' => 'NIS harus diisi',
            'nis.unique' => 'NIS sudah digunakan siswa lain',
            'kelas_id.required' => 'Kelas harus dipilih',
        ]);

        if ($token->siswa) {
            $token->siswa->update([
                'nama' => $request->nama,
                'nisn' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'jenis' => 'siswa',
            ]);
        }

        // Update token record
        $token->update([
            'nama_pemilih' => $request->nama,
            'nis_pemilih' => $request->nis,
            'kelas_id' => $request->kelas_id,
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
        $token = TokenPemilih::with(['siswa.kelas', 'kelas'])->findOrFail($id);
        
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
        $token = TokenPemilih::with(['siswa.kelas', 'kelas'])->findOrFail($id);
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

    /**
     * Reset token and delete vote for a voter
     */
    public function reset($id)
    {
        $token = TokenPemilih::findOrFail($id);

        Suara::where('periode_id', $token->periode_id)
            ->where('pemilih_id', $token->pemilih_id)
            ->delete();

        $newToken = strtoupper(Str::random(6));

        $token->update([
            'token' => $newToken,
            'token_hash' => Hash::make($newToken),
            'status' => 'aktif',
            'sudah_memilih' => false,
            'digunakan_pada' => null,
            'kadaluarsa_pada' => null,
        ]);

        return back()->with('success', "Token untuk {$token->nama_pemilih} berhasil direset");
    }
}
