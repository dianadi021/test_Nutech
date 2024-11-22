<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): RedirectResponse
    {
        // return view('auth.login');
        return redirect('/');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        DB::table('users')->where('username', $request->username)->update([
            'ip_address' => $request->ip(),
            'last_login' => now('Asia/Jakarta')
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        $qry = "SELECT usr.username, usr.email, usr.status, usr.id_role, pdd.fullname FROM users usr
        JOIN pegawai pgw ON pgw.id_user = usr.id JOIN penduduk pdd on pdd.id = pgw.id_penduduk WHERE usr.email = ?";
        $user = DB::selectOne("$qry", [$request->email]);
        session(['user_login' => (array)$user]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flush();

        return redirect('/');
    }
}
