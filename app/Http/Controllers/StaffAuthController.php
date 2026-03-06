<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Panitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffAuthController extends Controller
{
    /**
     * Show unified staff login form.
     */
    public function showLogin()
    {
        return view('staff.login');
    }

    /**
     * Authenticate admin or panitia using email and password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::findByEmail($request->email);
        if ($admin && $admin->matchesPassword($request->password)) {
            Session::put('admin_id', $admin->id);
            Session::put('admin_username', $admin->username);
            Session::put('admin_nama', $admin->nama);

            return redirect()->route('admin.dashboard');
        }

        $panitia = Panitia::findByEmail($request->email);
        if ($panitia && $panitia->matchesPassword($request->password)) {
            Session::put('panitia_id', $panitia->id);
            Session::put('panitia_username', $panitia->username);
            Session::put('panitia_nama', $panitia->nama);

            return redirect()->route('panitia.results');
        }

        return back()
            ->withErrors(['credentials' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }
}
