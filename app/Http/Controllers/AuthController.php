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
        return redirect('/');
    }

    public function register()
    {

        return view('auth.register');
    }

    public function postMasuk(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'password' => 'required|string|max:255',
            ], [
                'username.required' => 'Harap isikan nama Anda!',
                'password.required' => 'Isi kata sandi terlebih dahulu!',
            ]);

            // $user = DB::table('users')->where('username', $request->username)->first();
            $user = User::where('username', $request->username)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::loginUsingId($user->id_user, true);
                $request->session()->regenerate();
            }

            return back()->withErrors(['error' => 'Nama pengguna atau kata sandi salah.'])->withInput($request->except('password'));
        } catch (ValidationException $validation) {
            return back()->withErrors($validation->errors())->withInput($request->except('password'));
        } catch (Exception $exception) {
            return back()->withErrors(['error' => 'Terjadi kesalahan pada sistem.'])->withInput($request->except('password'));
        }
    }
}
