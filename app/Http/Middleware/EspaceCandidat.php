<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EspaceCandidat
{
    /**
     * Vérifie qu'un candidat est connecté à son espace (numéro de dossier + téléphone).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('espace_candidat_id')) {
            return redirect()->route('espace.connexion')
                ->withErrors(['numero_dossier' => 'Veuillez vous connecter pour accéder à votre espace.']);
        }

        return $next($request);
    }
}
