<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Si "both" est passé, on autorise gestionnaire et enseignant
        if (in_array('both', $roles)) {
            if (in_array($userRole, ['gestionnaire', 'enseignant'])) {
                return $next($request);
            }
        }

        // Sinon on vérifie les rôles spécifiques
        foreach ($roles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        abort(403, 'Accès refusé. Vous n\'avez pas les droits nécessaires.');
    }
}