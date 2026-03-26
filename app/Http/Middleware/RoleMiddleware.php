<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user && $user->role, Response::HTTP_FORBIDDEN, 'No autorizado.');

        if ($roles !== [] && ! $user->hasRole(...$roles)) {
            abort(Response::HTTP_FORBIDDEN, 'No cuenta con permisos para acceder a este recurso.');
        }

        return $next($request);
    }
}
