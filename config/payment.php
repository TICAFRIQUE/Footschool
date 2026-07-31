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

    /*
    |--------------------------------------------------------------------------
    | Paiement manuel Wave (en attendant l'API)
    |--------------------------------------------------------------------------
    |
    | Lien de paiement Wave statique + numéro WhatsApp où le candidat envoie
    | sa preuve de paiement. L'admin valide ensuite manuellement dans
    | /admin/paiements-attente.
    |
    */
    'wave_payment_link' => env('WAVE_PAYMENT_LINK', 'https://pay.wave.com/m/M_ci_OlrALfwaE3L9/c/ci/'),
    'whatsapp_number'   => env('WHATSAPP_PROOF_NUMBER', '2250715094421'),

];
