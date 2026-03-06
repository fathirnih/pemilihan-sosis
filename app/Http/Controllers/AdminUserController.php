<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('nama')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:admins,username',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'aktif' => 'nullable|boolean',
        ]);

        Admin::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:admins,username,' . $admin->id,
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'aktif' => 'nullable|boolean',
        ]);

        $payload = [
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'aktif' => $request->boolean('aktif'),
        ];

        if ($request->filled('password')) {
            $payload['password'] = $request->password;
        }

        $admin->update($payload);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $currentId = Session::get('admin_id');
        if ($currentId && (int) $currentId === (int) $admin->id) {
            return back()->withErrors(['admin' => 'Akun yang sedang login tidak bisa dihapus.']);
        }

        $admin->delete();
        return back()->with('success', 'Admin berhasil dihapus.');
    }
}
