<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        try {
            $user = $request->user();
            $user->loadMissing('role');
            $user_role = $user->getRole();

            $allowedRoles = explode('|', $roles); // pisah string jadi array

            if (in_array($user_role, $allowedRoles)) {
                return $next($request);
            }

            abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
        } catch (\Throwable $th) {
            // abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini. Silakan masuk terlebih dahulu.');
            return redirect()->route('login');
        }
    }
}
