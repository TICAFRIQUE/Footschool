<?php

namespace App\Services\Payment;

use App\Models\Paiement;
use Illuminate\Http\Request;

/**
 * Gateway factice utilisée en attendant les identifiants API Wave.
 * Redirige vers une page interne où l'on choisit "succès" ou "échec"
 * pour simuler le retour d'un vrai prestataire de paiement.
 */
class SimulatedPaymentGateway implements PaymentGatewayInterface
{
    public function initiate(Paiement $paiement): string
    {
        return route('finalisation.paiement.simuler', $paiement);
    }

    public function verify(Request $request): array
    {
        $succes = $request->input('resultat') === 'succes';

        return [
            'reference'      => $request->input('reference'),
            'statut'         => $succes ? 'reussi' : 'echoue',
            'transaction_id' => 'SIM-' . strtoupper(uniqid()),
            'payload'        => [
                'mode'     => 'simulation',
                'resultat' => $request->input('resultat'),
                'date'     => now()->toDateTimeString(),
            ],
        ];
    }
}
