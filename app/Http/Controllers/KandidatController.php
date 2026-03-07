<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\KandidatAnggota;
use App\Models\PeriodePemilihan;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class KandidatController extends Controller
{
    public function index(Request $request)
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $periodeId = $request->get('periode_id') ?: $periodes->first()?->id;

        $kandidats = Kandidat::with(['periode', 'anggota.pemilih'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->orderBy('nomor_urut')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kandidat.index', compact('kandidats', 'periodes', 'periodeId'));
    }

    public function create()
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $pemilih = Pemilih::where('jenis', 'siswa')->orderBy('nama')->get();
        return view('admin.kandidat.create', compact('periodes', 'pemilih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_pemilihan,id',
            'nomor_urut' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kandidat', 'nomor_urut')->where('periode_id', $request->periode_id),
            ],
            'visi' => 'required|string|max:500',
            'misi' => 'required|string|max:1500',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_ketua' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_wakil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ketua_id' => 'required|exists:pemilih,id',
            'wakil_id' => 'nullable|exists:pemilih,id|different:ketua_id',
        ]);

        $payload = $request->only([
            'periode_id',
            'nomor_urut',
            'visi',
            'misi',
        ]);

        if ($request->hasFile('foto')) {
            $payload['foto'] = $request->file('foto')->store('kandidat', 'public');
        }
        if ($request->hasFile('foto_ketua')) {
            $payload['foto_ketua'] = $request->file('foto_ketua')->store('kandidat', 'public');
        }
        if ($request->hasFile('foto_wakil')) {
            $payload['foto_wakil'] = $request->file('foto_wakil')->store('kandidat', 'public');
        }

        $kandidat = Kandidat::create($payload);

        KandidatAnggota::create([
            'kandidat_id' => $kandidat->id,
            'pemilih_id' => $request->ketua_id,
            'peran' => 'ketua',
        ]);

        if ($request->filled('wakil_id')) {
            KandidatAnggota::create([
                'kandidat_id' => $kandidat->id,
                'pemilih_id' => $request->wakil_id,
                'peran' => 'wakil',
            ]);
        }

        return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kandidat = Kandidat::with('anggota')->findOrFail($id);
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $pemilih = Pemilih::where('jenis', 'siswa')->orderBy('nama')->get();

        $ketuaId = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih_id;
        $wakilId = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih_id;

        return view('admin.kandidat.edit', compact('kandidat', 'periodes', 'pemilih', 'ketuaId', 'wakilId'));
    }

    public function update(Request $request, $id)
    {
        $kandidat = Kandidat::findOrFail($id);

        $request->validate([
            'periode_id' => 'required|exists:periode_pemilihan,id',
            'nomor_urut' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kandidat', 'nomor_urut')
                    ->where('periode_id', $request->periode_id)
                    ->ignore($kandidat->id),
            ],
            'visi' => 'required|string|max:500',
            'misi' => 'required|string|max:1500',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_ketua' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_wakil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ketua_id' => 'required|exists:pemilih,id',
            'wakil_id' => 'nullable|exists:pemilih,id|different:ketua_id',
        ]);

        $payload = $request->only([
            'periode_id',
            'nomor_urut',
            'visi',
            'misi',
        ]);

        if ($request->hasFile('foto')) {
            if ($kandidat->foto) {
                Storage::disk('public')->delete($kandidat->foto);
            }
            $payload['foto'] = $request->file('foto')->store('kandidat', 'public');
        }
        if ($request->hasFile('foto_ketua')) {
            if ($kandidat->foto_ketua) {
                Storage::disk('public')->delete($kandidat->foto_ketua);
            }
            $payload['foto_ketua'] = $request->file('foto_ketua')->store('kandidat', 'public');
        }
        if ($request->hasFile('foto_wakil')) {
            if ($kandidat->foto_wakil) {
                Storage::disk('public')->delete($kandidat->foto_wakil);
            }
            $payload['foto_wakil'] = $request->file('foto_wakil')->store('kandidat', 'public');
        }

        $kandidat->update($payload);

        KandidatAnggota::where('kandidat_id', $kandidat->id)->delete();
        KandidatAnggota::create([
            'kandidat_id' => $kandidat->id,
            'pemilih_id' => $request->ketua_id,
            'peran' => 'ketua',
        ]);

        if ($request->filled('wakil_id')) {
            KandidatAnggota::create([
                'kandidat_id' => $kandidat->id,
                'pemilih_id' => $request->wakil_id,
                'peran' => 'wakil',
            ]);
        }

        return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kandidat = Kandidat::findOrFail($id);
        if ($kandidat->foto) {
            Storage::disk('public')->delete($kandidat->foto);
        }
        if ($kandidat->foto_ketua) {
            Storage::disk('public')->delete($kandidat->foto_ketua);
        }
        if ($kandidat->foto_wakil) {
            Storage::disk('public')->delete($kandidat->foto_wakil);
        }
        KandidatAnggota::where('kandidat_id', $kandidat->id)->delete();
        $kandidat->delete();

        return back()->with('success', 'Kandidat berhasil dihapus.');
    }
}
