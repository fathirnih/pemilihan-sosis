<?php

namespace App\Http\Controllers;

use App\Models\Suara;
use App\Models\PeriodePemilihan;
use App\Models\Kandidat;
use App\Models\Pemilih;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuaraController extends Controller
{
    public function index(Request $request)
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $periodeId = $request->has('periode_id') ? ($request->get('periode_id') ?: null) : null;
        $search    = $request->get('search');
        $tingkat   = $request->get('tingkat');
        $kelasId   = $request->get('kelas_id');
        $kandidatId = $request->get('kandidat_id');

        $suara = Suara::with(['kandidat.anggota.pemilih', 'periode', 'pemilih.kelas'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->when($search, fn($q) => $q->whereHas('pemilih', fn($pq) =>
                $pq->where('nama', 'like', "%{$search}%")
                   ->orWhere('nisn', 'like', "%{$search}%")
            ))
            ->when($tingkat === 'none', fn($q) => $q->whereHas('pemilih', fn($pq) =>
                $pq->whereNull('kelas_id')
            ))
            ->when($tingkat && $tingkat !== 'none', fn($q) => $q->whereHas('pemilih.kelas', fn($kq) =>
                $kq->where('tingkat', $tingkat)
            ))
            ->when($kelasId, fn($q) => $q->whereHas('pemilih', fn($pq) =>
                $pq->where('kelas_id', $kelasId)
            ))
            ->when($kandidatId, fn($q) => $q->where('kandidat_id', $kandidatId))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $tingkats  = Kelas::select('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');
        $kelasList = ($tingkat && $tingkat !== 'none')
            ? Kelas::where('tingkat', $tingkat)->orderBy('nama_kelas')->get()
            : ($tingkat === 'none' ? collect() : Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get());
        $kandidats = $periodeId
            ? Kandidat::with('anggota.pemilih')->where('periode_id', $periodeId)->orderBy('nomor_urut')->get()
            : Kandidat::with('anggota.pemilih')->orderBy('nomor_urut')->get();

        return view('admin.suara.index', compact(
            'suara', 'periodes', 'periodeId',
            'search', 'tingkat', 'kelasId', 'kandidatId',
            'tingkats', 'kelasList', 'kandidats'
        ));
    }

    public function create(Request $request)
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $periodeId = $request->get('periode_id') ?: $periodes->first()?->id;

        $pemilih = $periodeId
            ? Pemilih::where('periode_pemilihan_id', $periodeId)->orderBy('nama')->get()
            : collect();

        $kandidats = $periodeId
            ? Kandidat::with('anggota.pemilih')->where('periode_id', $periodeId)->orderBy('nomor_urut')->get()
            : collect();

        return view('admin.suara.create', compact('periodes', 'pemilih', 'kandidats', 'periodeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_pemilihan,id',
            'kandidat_id' => [
                'required',
                Rule::exists('kandidat', 'id')->where('periode_id', $request->periode_id),
            ],
            'pemilih_id' => [
                'required',
                Rule::exists('pemilih', 'id')->where('periode_pemilihan_id', $request->periode_id),
                Rule::unique('suara')->where('periode_id', $request->periode_id),
            ],
        ]);

        Suara::create($request->only(['periode_id', 'kandidat_id', 'pemilih_id']));

        return redirect()->route('admin.suara.index')->with('success', 'Suara berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $suara = Suara::findOrFail($id);
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        $periodeId = $request->get('periode_id') ?: $suara->periode_id;

        $pemilih = $periodeId
            ? Pemilih::where('periode_pemilihan_id', $periodeId)->orderBy('nama')->get()
            : collect();

        $kandidats = $periodeId
            ? Kandidat::with('anggota.pemilih')->where('periode_id', $periodeId)->orderBy('nomor_urut')->get()
            : collect();

        return view('admin.suara.edit', compact('suara', 'periodes', 'pemilih', 'kandidats', 'periodeId'));
    }

    public function update(Request $request, $id)
    {
        $suara = Suara::findOrFail($id);

        $request->validate([
            'periode_id' => 'required|exists:periode_pemilihan,id',
            'kandidat_id' => [
                'required',
                Rule::exists('kandidat', 'id')->where('periode_id', $request->periode_id),
            ],
            'pemilih_id' => [
                'required',
                Rule::exists('pemilih', 'id')->where('periode_pemilihan_id', $request->periode_id),
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
