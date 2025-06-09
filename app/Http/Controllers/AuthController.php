<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
    {

        return view('auth.register');
    }


public function postMasuk(Request $request)
{
    $request->validate([
        'username' => 'required|string|max:255',
        'password' => 'required|string|max:255',
    ]);

    if ($request->ajax() || $request->wantsJson()) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // pastikan relasi dimuat
            $role = strtoupper($user->role->nama_role ?? '');

            $redirectUrl = url('/');
            if ($role == 'ADMIN') {
                $redirectUrl = url('/admin');
            }  elseif($role == 'SARPRAS'){
                $redirectUrl = url('/sarpras');
            }  elseif($role == 'TEKNISI'){
                $redirectUrl = url('/teknisi');
            } elseif (in_array($role, ['MAHASISWA', 'DOSEN', 'TENDIK'])) {
                $redirectUrl = url('/pelapor');
            }elseif ($role == 'TEKNISI') {
                $redirectUrl = url('/teknisi');
            }elseif ($role == 'SARPRAS') {
                $redirectUrl = url('/sarpras');
            }

            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'redirect' => $redirectUrl
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Username atau password salah'
        ]);
    }

    return redirect('login');
}



}
