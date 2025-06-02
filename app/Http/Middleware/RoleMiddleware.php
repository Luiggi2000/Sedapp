<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
   public function handle($request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    // Obtener el nombre del rol correctamente
    $nombreRol = $user->role->name ?? 'Sin Rol';

    if (!in_array($nombreRol, $roles)) {
        abort(403, "No tienes permiso para acceder a esta ruta. Tu rol es: {$nombreRol}");
    }

    return $next($request);
}



}
