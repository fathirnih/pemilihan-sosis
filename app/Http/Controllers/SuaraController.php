<?php

namespace App\Http\Controllers;

use App\Models\Suara;
use App\Models\PeriodePemilihan;
use App\Models\Kandidat;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuaraController extends Controller
{
    public function index(Request $request)
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $periodeId = $request->get('periode_id') ?: $periodes->first()?->id;

        $suara = Suara::with(['kandidat.anggota.pemilih', 'periode', 'pemilih'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.suara.index', compact('suara', 'periodes', 'periodeId'));
    }

    public function create()
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $pemilih = Pemilih::orderBy('nama')->get();
        $kandidats = Kandidat::with('anggota.pemilih')->orderBy('nomor_urut')->get();

        return view('admin.suara.create', compact('periodes', 'pemilih', 'kandidats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_pemilihan,id',
            'kandidat_id' => 'required|exists:kandidat,id',
            'pemilih_id' => [
                'required',
                'exists:pemilih,id',
                Rule::unique('suara')->where('periode_id', $request->periode_id),
            ],
        ]);

        Suara::create($request->only(['periode_id', 'kandidat_id', 'pemilih_id']));

        return redirect()->route('admin.suara.index')->with('success', 'Suara berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $suara = Suara::findOrFail($id);
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $pemilih = Pemilih::orderBy('nama')->get();
        $kandidats = Kandidat::with('anggota.pemilih')->orderBy('nomor_urut')->get();

        return view('admin.suara.edit', compact('suara', 'periodes', 'pemilih', 'kandidats'));
    }

    public function update(Request $request, $id)
    {
        $suara = Suara::findOrFail($id);

        $request->validate([
            'periode_id' => 'required|exists:periode_pemilihan,id',
            'kandidat_id' => 'required|exists:kandidat,id',
            'pemilih_id' => [
                'required',
                'exists:pemilih,id',
                Rule::unique('suara')
                    ->where('periode_id', $request->periode_id)
                    ->ignore($suara->id),
            ],
        ]);

        $suara->update($request->only(['periode_id', 'kandidat_id', 'pemilih_id']));

        return redirect()->route('admin.suara.index')->with('success', 'Suara berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suara = Suara::findOrFail($id);
        $suara->delete();

        return back()->with('success', 'Suara berhasil dihapus.');
    }
}
