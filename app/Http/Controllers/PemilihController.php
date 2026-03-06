<?php

namespace App\Http\Controllers;

use App\Models\Pemilih;
use App\Models\TokenPemilih;
use App\Models\PeriodePemilihan;
use App\Models\Kelas;
use App\Models\Suara;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PemilihController extends Controller
{
    public function index()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        $periodeId = $periode?->id ?? -1;

        $query = Pemilih::with([
            'kelas',
            'tokens' => function ($query) use ($periodeId) {
                $query->where('periode_id', $periodeId);
            }
        ]);

        if (request()->filled('q')) {
            $q = request('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('nisn', 'like', '%' . $q . '%');
            });
        }

        if (request()->filled('jenis')) {
            $query->where('jenis', request('jenis'));
        }

        if (request()->filled('tingkat')) {
            $query->whereHas('kelas', function ($sub) {
                $sub->where('tingkat', request('tingkat'));
            });
        }

        if (request()->filled('kelas_id')) {
            $query->where('kelas_id', request('kelas_id'));
        }

        $pemilih = $query->orderBy('nama')->paginate(10)->withQueryString();

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.tokens.index', compact('pemilih', 'periode', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.tokens.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:30|unique:pemilih,nisn',
            'jenis' => 'required|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
        ], [
            'nama.required' => 'Nama pemilih harus diisi',
            'nis.required' => 'NISN/NIP harus diisi',
            'nis.unique' => 'NISN/NIP sudah digunakan',
            'jenis.required' => 'Jenis pemilih harus dipilih',
        ]);

        if ($request->jenis === 'siswa' && !$request->filled('kelas_id')) {
            return back()->withErrors(['kelas_id' => 'Kelas wajib dipilih untuk siswa'])->withInput();
        }

        Pemilih::create([
            'nama' => $request->nama,
            'nisn' => $request->nis,
            'jenis' => $request->jenis,
            'kelas_id' => $request->jenis === 'siswa' ? $request->kelas_id : null,
            'aktif' => true,
        ]);

        return redirect()->route('admin.pemilih.index')->with('success', 'Pemilih berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pemilih = Pemilih::findOrFail($id);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.tokens.edit', compact('pemilih', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $pemilih = Pemilih::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:30|unique:pemilih,nisn,' . $pemilih->id,
            'jenis' => 'required|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
        ], [
            'nama.required' => 'Nama pemilih harus diisi',
            'nis.required' => 'NISN/NIP harus diisi',
            'nis.unique' => 'NISN/NIP sudah digunakan',
            'jenis.required' => 'Jenis pemilih harus dipilih',
        ]);

        if ($request->jenis === 'siswa' && !$request->filled('kelas_id')) {
            return back()->withErrors(['kelas_id' => 'Kelas wajib dipilih untuk siswa'])->withInput();
        }

        $pemilih->update([
            'nama' => $request->nama,
            'nisn' => $request->nis,
            'jenis' => $request->jenis,
            'kelas_id' => $request->jenis === 'siswa' ? $request->kelas_id : null,
            'aktif' => true,
        ]);

        return redirect()->route('admin.pemilih.index')->with('success', 'Pemilih berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pemilih = Pemilih::findOrFail($id);

        $suaraCount = Suara::where('pemilih_id', $pemilih->id)->count();
        if ($suaraCount > 0) {
            return back()->withErrors(['pemilih' => 'Pemilih tidak bisa dihapus karena sudah memiliki suara']);
        }

        TokenPemilih::where('pemilih_id', $pemilih->id)->delete();
        $pemilih->delete();

        return back()->with('success', 'Pemilih berhasil dihapus');
    }

    public function generateTokens(Request $request)
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $pemilihList = Pemilih::where('aktif', true)->get();
        if ($pemilihList->isEmpty()) {
            return back()->withErrors(['pemilih' => 'Data pemilih kosong']);
        }

        $existing = TokenPemilih::where('periode_id', $periode->id)->pluck('pemilih_id')->all();

        $created = 0;
        foreach ($pemilihList as $pemilih) {
            if (in_array($pemilih->id, $existing, true)) {
                continue;
            }
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
            $created++;
        }

        return back()->with('success', "Token berhasil dibuat untuk {$created} pemilih");
    }

    public function resetToken($id)
    {
        $pemilih = Pemilih::findOrFail($id);
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $token = TokenPemilih::where('periode_id', $periode->id)
            ->where('pemilih_id', $pemilih->id)
            ->first();

        if (!$token) {
            return back()->withErrors(['token' => 'Token belum dibuat untuk pemilih ini']);
        }

        Suara::where('periode_id', $periode->id)
            ->where('pemilih_id', $pemilih->id)
            ->delete();

        $newToken = 'VOTE-' . Str::random(16);
        $token->update([
            'token' => $newToken,
            'token_hash' => Hash::make($newToken),
            'status' => 'aktif',
            'sudah_memilih' => false,
            'digunakan_pada' => null,
            'kadaluarsa_pada' => null,
        ]);

        return back()->with('success', "Token untuk {$pemilih->nama} berhasil direset");
    }

    public function printTokens(Request $request)
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $query = Pemilih::with([
            'kelas',
            'tokens' => function ($q) use ($periode) {
                $q->where('periode_id', $periode->id);
            }
        ])->whereHas('tokens', function ($q) use ($periode) {
            $q->where('periode_id', $periode->id);
        });

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('tingkat')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('tingkat', $request->tingkat);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $pemilih = $query->orderBy('nama')->get();

        if ($pemilih->isEmpty()) {
            return back()->withErrors(['token' => 'Tidak ada token untuk dicetak dengan filter tersebut.']);
        }

        return view('admin.tokens.print-all', [
            'pemilih' => $pemilih,
            'periode' => $periode,
        ]);
    }

    public function deleteAllTokens()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $tokens = TokenPemilih::where('periode_id', $periode->id)
            ->whereNotNull('pemilih_id')
            ->get();

        if ($tokens->isEmpty()) {
            return back()->withErrors(['token' => 'Belum ada token untuk dihapus.']);
        }

        $pemilihIds = $tokens->pluck('pemilih_id')->all();
        Suara::where('periode_id', $periode->id)
            ->whereIn('pemilih_id', $pemilihIds)
            ->delete();

        TokenPemilih::where('periode_id', $periode->id)
            ->whereNotNull('pemilih_id')
            ->delete();

        return back()->with('success', 'Semua token berhasil dihapus.');
    }
}
