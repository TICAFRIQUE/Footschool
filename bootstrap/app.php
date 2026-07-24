<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //ajouter le middleware admin
        $middleware->alias([
            'admin'              => App\Http\Middleware\Admin::class,
            'inscription.session' => App\Http\Middleware\InscriptionSession::class,
            'espace.candidat'     => App\Http\Middleware\EspaceCandidat::class,
        ]);

        // Le callback de paiement (webhook) est appelé par le prestataire,
        // sans session/jeton CSRF Laravel.
        $middleware->validateCsrfTokens(except: [
            'inscription-officielle/paiement/callback',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();



