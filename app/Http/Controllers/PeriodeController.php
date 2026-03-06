<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = PeriodePemilihan::orderByDesc('mulai_pada')->paginate(10);
        return view('admin.periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:120',
            'mulai_pada' => 'required|date',
            'selesai_pada' => 'required|date|after:mulai_pada',
            'status' => 'required|in:draf,aktif,ditutup',
            'mode_pasangan' => 'required|in:ketua_saja,ketua_wakil',
        ]);

        if ($request->status === 'aktif') {
            PeriodePemilihan::where('status', 'aktif')->update(['status' => 'ditutup']);
        }

        PeriodePemilihan::create($request->only([
            'nama_periode',
            'mulai_pada',
            'selesai_pada',
            'status',
            'mode_pasangan',
        ]));

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $periode = PeriodePemilihan::findOrFail($id);
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, $id)
    {
        $periode = PeriodePemilihan::findOrFail($id);

        $request->validate([
            'nama_periode' => 'required|string|max:120',
            'mulai_pada' => 'required|date',
            'selesai_pada' => 'required|date|after:mulai_pada',
            'status' => 'required|in:draf,aktif,ditutup',
            'mode_pasangan' => 'required|in:ketua_saja,ketua_wakil',
        ]);

        if ($request->status === 'aktif') {
            PeriodePemilihan::where('id', '!=', $periode->id)
                ->where('status', 'aktif')
                ->update(['status' => 'ditutup']);
        }

        $periode->update($request->only([
            'nama_periode',
            'mulai_pada',
            'selesai_pada',
            'status',
            'mode_pasangan',
        ]));

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $periode = PeriodePemilihan::findOrFail($id);
        $periode->delete();

        return back()->with('success', 'Periode berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $periode = PeriodePemilihan::findOrFail($id);
        $newStatus = $periode->status === 'aktif' ? 'ditutup' : 'aktif';

        if ($newStatus === 'aktif') {
            PeriodePemilihan::where('id', '!=', $periode->id)
                ->where('status', 'aktif')
                ->update(['status' => 'ditutup']);
        }

        $periode->update(['status' => $newStatus]);

        return back()->with('success', 'Status periode berhasil diubah menjadi ' . $newStatus);
    }
}
