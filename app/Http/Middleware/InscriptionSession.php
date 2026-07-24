<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InscriptionSession
{
    /**
     * Vérifie qu'un candidat a bien confirmé son identité avant d'accéder
     * aux étapes suivantes de la finalisation de l'inscription.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('finalisation_candidat_id')) {
            return redirect()->route('finalisation.connexion')
                ->withErrors(['telephone' => 'Veuillez vous identifier pour continuer.']);
        }

        return $next($request);
    }
}
