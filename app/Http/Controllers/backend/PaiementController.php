<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class PaiementController extends Controller
{
    public function index()
    {
        $candidats = Candidat::where('statut', 'en_attente_paiement')
            ->orderByDesc('updated_at')
            ->get();

        return view('backend.paiements.index', compact('candidats'));
    }

    public function valider(Request $request, Candidat $candidat)
    {
        abort_if($candidat->statut !== 'en_attente_paiement', 422, "Ce candidat n'est pas en attente de paiement.");

        $validated = $request->validate([
            'reference_wave' => ['nullable', 'string', 'max:100'],
        ]);

        Paiement::create([
            'candidat_id'    => $candidat->id,
            'reference'      => 'WAVE-' . strtoupper(Str::random(8)),
            'montant'        => config('payment.montant_inscription'),
            'moyen'          => 'wave',
            'statut'         => 'reussi',
            'transaction_id' => $validated['reference_wave'] ?? null,
            'payload'        => ['methode' => 'manuel_whatsapp', 'valide_par' => $request->user()?->id],
        ]);

        $candidat->update([
            'statut'     => 'inscrit',
            'inscrit_at' => $candidat->inscrit_at ?? now(),
        ]);

        $candidat->genererNumeroDossier();

        Alert::success('Paiement validé pour ' . $candidat->prenom . ' ' . strtoupper($candidat->nom) . '.', 'Inscription confirmée');

        return back();
    }
}
