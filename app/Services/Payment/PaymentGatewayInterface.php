<?php

namespace App\Services\Payment;

use App\Models\Paiement;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Démarre le paiement et retourne l'URL vers laquelle rediriger le candidat.
     */
    public function initiate(Paiement $paiement): string;

    /**
     * Interprète le retour/callback du fournisseur.
     *
     * @return array{reference: string, statut: string, transaction_id: ?string, payload: array}
     */
    public function verify(Request $request): array;
}
