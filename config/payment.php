<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gateway de paiement actif
    |--------------------------------------------------------------------------
    |
    | 'simulated' en attendant les identifiants API Wave. Basculer sur 'wave'
    | une fois l'intégration Wave prête, sans changer le reste du code.
    |
    */
    'default' => env('PAYMENT_GATEWAY', 'simulated'),

    /*
    |--------------------------------------------------------------------------
    | Montant de l'inscription (en FCFA)
    |--------------------------------------------------------------------------
    */
    'montant_inscription' => (int) env('INSCRIPTION_MONTANT', 65000),

    'wave' => [
        'api_key'    => env('WAVE_API_KEY'),
        'api_secret' => env('WAVE_API_SECRET'),
    ],

];
