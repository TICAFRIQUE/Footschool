<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Paiement;

class InscriptionController extends Controller
{
    public function index()
    {
        $candidats = Candidat::where('statut', 'inscrit')
            ->with(['paiements' => fn ($q) => $q->where('statut', 'reussi')->latest()])
            ->orderByDesc('inscrit_at')
            ->get();

        $kpis = [
            'total_inscrits'    => $candidats->count(),
            'total_collecte'    => Paiement::where('statut', 'reussi')->sum('montant'),
            'paiements_attente' => Paiement::where('statut', 'en_attente')->count(),
            'paiements_echoues' => Paiement::where('statut', 'echoue')->count(),
            'taux_conversion'   => $this->tauxConversion(),
        ];

        return view('backend.inscriptions.index', compact('candidats', 'kpis'));
    }

    /**
     * Supprime l'inscription (phase 2) d'un candidat : efface ses paiements
     * et remet son dossier à l'état préinscrit. La préinscription elle-même
     * n'est jamais supprimée.
     */
    public function destroy($id)
    {
        try {
            $candidat = Candidat::findOrFail($id);
            $candidat->paiements()->delete();
            $candidat->reinitialiserInscription();

            return response()->json([
                'status'  => 'success',
                'message' => 'Inscription supprimée, le candidat est repassé en préinscrit.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage(),
            ], 500);
        }
    }

    private function tauxConversion(): float
    {
        $total = Candidat::count();

        if ($total === 0) {
            return 0.0;
        }

        $inscrits = Candidat::where('statut', 'inscrit')->count();

        return round(($inscrits / $total) * 100, 1);
    }
}
