<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EspaceCandidatController extends Controller
{
    public function connexion()
    {
        return view('frontend.espace.connexion');
    }

    public function verifier(Request $request)
    {
        $validated = $request->validate([
            'telephone' => ['required', 'digits:10'],
        ]);

        $telephone = Candidat::normalizePhone($validated['telephone']);

        $candidat = Candidat::where('telephone', $telephone)->first();

        if (! $candidat) {
            return back()
                ->withErrors(['candidat' => "Aucun dossier trouvé pour ce numéro. Tu dois d'abord faire ta préinscription."])
                ->withInput();
        }

        $request->session()->put('espace_candidat_id', $candidat->id);

        return redirect()->route('espace.dashboard');
    }

    public function dashboard(Request $request)
    {
        $candidat = $this->candidatConnecte($request);
        $paiements = $candidat->paiements()->latest()->get();

        return view('frontend.espace.dashboard', compact('candidat', 'paiements'));
    }

    public function recu(Request $request, Paiement $paiement)
    {
        $candidat = $this->candidatConnecte($request);

        abort_unless($paiement->candidat_id === $candidat->id && $paiement->statut === 'reussi', 404);

        $pdf = Pdf::loadView('frontend.espace.recu-pdf', compact('candidat', 'paiement'));

        return $pdf->download('recu-inscription-' . $candidat->numero_dossier . '.pdf');
    }

    public function fiche(Request $request)
    {
        $candidat = $this->candidatConnecte($request);

        $pdf = Pdf::loadView('frontend.espace.fiche-pdf', compact('candidat'));

        return $pdf->download('fiche-candidat-' . ($candidat->numero_dossier ?? $candidat->id) . '.pdf');
    }

    public function deconnexion(Request $request)
    {
        $request->session()->forget('espace_candidat_id');

        return redirect()->route('espace.connexion');
    }

    private function candidatConnecte(Request $request): Candidat
    {
        return Candidat::findOrFail($request->session()->get('espace_candidat_id'));
    }
}
