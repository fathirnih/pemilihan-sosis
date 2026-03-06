<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use Illuminate\Http\Request;

class PanitiaUserController extends Controller
{
    public function index()
    {
        $panitia = Panitia::orderBy('nama')->paginate(10);
        return view('admin.panitia.index', compact('panitia'));
    }

    public function create()
    {
        return view('admin.panitia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:panitias,username',
            'email' => 'required|email|unique:panitias,email',
            'jabatan' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'aktif' => 'nullable|boolean',
        ]);

        Panitia::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'password' => $request->password,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('admin.panitia.index')->with('success', 'Panitia berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $panitia = Panitia::findOrFail($id);
        return view('admin.panitia.edit', compact('panitia'));
    }

    public function update(Request $request, $id)
    {
        $panitia = Panitia::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:panitias,username,' . $panitia->id,
            'email' => 'required|email|unique:panitias,email,' . $panitia->id,
            'jabatan' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
            'aktif' => 'nullable|boolean',
        ]);

        $payload = [
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'aktif' => $request->boolean('aktif'),
        ];

        if ($request->filled('password')) {
            $payload['password'] = $request->password;
        }

        $panitia->update($payload);

        return redirect()->route('admin.panitia.index')->with('success', 'Panitia berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panitia = Panitia::findOrFail($id);
        $panitia->delete();
        return back()->with('success', 'Panitia berhasil dihapus.');
    }
}
