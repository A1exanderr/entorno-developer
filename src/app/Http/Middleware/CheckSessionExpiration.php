<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckSessionExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->id_estacion !== null) {
            $lastActivity = $request->session()->get('last_activity');
            $sessionTimeout = config('session.lifetime') * 60; // en segundos

            if ($lastActivity === null || time() - $lastActivity > $sessionTimeout) {
                $userId = Auth::id(); // Obtén el ID del usuario autenticado
                $user = User::find($userId); // Busca el modelo User

                if ($user) {
                    $user->id_estacion = null;
                    $user->save(); // Guarda los cambios en el modelo User
                }

                Auth::logout();
                return redirect('/login')->with('message', 'Su sesión ha expirado.');
            }
        }

        // Actualiza `last_activity` después de la verificación
        $request->session()->put('last_activity', time());

        return $next($request);
    }
}
