<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('siswa')->orderBy('tingkat')->orderBy('nama_kelas')->paginate(10);
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat' => 'required|integer|min:1|max:12',
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi',
            'nama_kelas.unique' => 'Nama kelas sudah ada',
            'tingkat.required' => 'Tingkat kelas harus diisi',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $kelas->id,
            'tingkat' => 'required|integer|min:1|max:12',
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi',
            'nama_kelas.unique' => 'Nama kelas sudah ada',
            'tingkat.required' => 'Tingkat kelas harus diisi',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kelas = Kelas::withCount('siswa')->findOrFail($id);

        if ($kelas->siswa_count > 0) {
            return back()->withErrors(['kelas' => 'Kelas tidak bisa dihapus karena masih dipakai data siswa']);
        }

        $kelas->delete();

        return back()->with('success', 'Data kelas berhasil dihapus');
    }
}
