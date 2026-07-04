<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Gère une requête entrante.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Non authentifié.'], 401);
            }
            return redirect('/login')->with('error', 'Veuillez vous connecter.');
        }

        // 2. Récupérer le rôle de l'utilisateur connecté
        $userRole = auth()->user()->role;

        // 3. Vérifier si l'utilisateur possède l'un des rôles autorisés
        if (empty($roles) || !in_array($userRole, $roles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Accès interdit.'], 403);
            }
            return redirect('/dashboard')->with('error', 'Vous n\'avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}