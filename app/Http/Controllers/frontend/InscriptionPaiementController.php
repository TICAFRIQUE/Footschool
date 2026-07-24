<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Paiement;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InscriptionPaiementController extends Controller
{
    public function accueil()
    {
        return view('frontend.finalisation.accueil');
    }

    public function connexion()
    {
        return view('frontend.finalisation.connexion');
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
                ->withErrors(['telephone' => "Aucune préinscription trouvée pour ce numéro."])
                ->withInput();
        }

        $request->session()->put('finalisation_candidat_id', $candidat->id);

        return $this->bloquerSiDejaInscrit($candidat) ?? redirect()->route('finalisation.confirmation');
    }

    public function confirmation(Request $request)
    {
        $candidat = $this->candidatConnecte($request);

        return view('frontend.finalisation.confirmation', compact('candidat'));
    }

    public function confirmer(Request $request)
    {
        $candidat = $this->candidatConnecte($request);
        if ($redirect = $this->bloquerSiDejaInscrit($candidat)) {
            return $redirect;
        }

        $request->validate([
            'confirme' => ['accepted'],
        ], [
            'confirme.accepted' => 'Vous devez confirmer que ces informations sont les vôtres.',
        ]);

        $request->session()->put('finalisation_confirme', true);

        return redirect()->route('finalisation.informations');
    }

    public function informations(Request $request)
    {
        $this->assertConfirme($request);
        $candidat = $this->candidatConnecte($request);
        if ($redirect = $this->bloquerSiDejaInscrit($candidat)) {
            return $redirect;
        }

        return view('frontend.finalisation.informations', compact('candidat'));
    }

    public function enregistrerInformations(Request $request)
    {
        $this->assertConfirme($request);
        $candidat = $this->candidatConnecte($request);
        if ($redirect = $this->bloquerSiDejaInscrit($candidat)) {
            return $redirect;
        }

        $validated = $request->validate([
            'pere_nom'     => ['required', 'string', 'max:150'],
            'pere_prenom'  => ['required', 'string', 'max:150'],
            'pere_contact' => ['required', 'digits:10'],
            'mere_nom'     => ['required', 'string', 'max:150'],
            'mere_prenom'  => ['required', 'string', 'max:150'],
            'mere_contact' => ['required', 'digits:10'],
        ]);

        $candidat->update([
            'pere_nom'     => Candidat::normalizeText($validated['pere_nom']),
            'pere_prenom'  => Candidat::normalizeText($validated['pere_prenom']),
            'pere_contact' => Candidat::normalizePhone($validated['pere_contact']),
            'mere_nom'     => Candidat::normalizeText($validated['mere_nom']),
            'mere_prenom'  => Candidat::normalizeText($validated['mere_prenom']),
            'mere_contact' => Candidat::normalizePhone($validated['mere_contact']),
            'statut'       => 'en_attente_paiement',
        ]);

        $candidat->genererNumeroDossier();

        return redirect()->route('finalisation.paiement');
    }

    public function paiement(Request $request)
    {
        $candidat = $this->candidatConnecte($request);
        if ($redirect = $this->bloquerSiDejaInscrit($candidat)) {
            return $redirect;
        }
        $this->assertInformationsCompletes($candidat);
        $montant = config('payment.montant_inscription');

        return view('frontend.finalisation.paiement', compact('candidat', 'montant'));
    }

    public function initierPaiement(Request $request)
    {
        $candidat = $this->candidatConnecte($request);
        if ($redirect = $this->bloquerSiDejaInscrit($candidat)) {
            return $redirect;
        }
        $this->assertInformationsCompletes($candidat);

        $paiement = Paiement::create([
            'candidat_id' => $candidat->id,
            'reference'   => 'PAY-' . strtoupper(Str::random(10)),
            'montant'     => config('payment.montant_inscription'),
            'moyen'       => config('payment.default'),
            'statut'      => 'en_attente',
        ]);

        $url = PaymentGatewayFactory::make()->initiate($paiement);

        return redirect()->away($url);
    }

    public function simulerFormulaire(Paiement $paiement)
    {
        abort_unless(config('payment.default') === 'simulated', 404);

        return view('frontend.finalisation.simulation', compact('paiement'));
    }

    public function callback(Request $request)
    {
        $resultat = PaymentGatewayFactory::make()->verify($request);

        $paiement = Paiement::where('reference', $resultat['reference'])->firstOrFail();

        $paiement->update([
            'statut'         => $resultat['statut'],
            'transaction_id' => $resultat['transaction_id'],
            'payload'        => $resultat['payload'],
        ]);

        if ($resultat['statut'] === 'reussi') {
            $candidat = $paiement->candidat;
            $candidat->update([
                'statut'     => 'inscrit',
                'inscrit_at' => $candidat->inscrit_at ?? now(),
            ]);
        }

        return redirect()->route('finalisation.retour', $paiement);
    }

    public function retour(Paiement $paiement)
    {
        $paiement->load('candidat');

        return view('frontend.finalisation.retour', compact('paiement'));
    }

    private function candidatConnecte(Request $request): Candidat
    {
        return Candidat::findOrFail($request->session()->get('finalisation_candidat_id'));
    }

    private function assertConfirme(Request $request): void
    {
        abort_unless($request->session()->get('finalisation_confirme'), 403, 'Confirmation requise.');
    }

    private function assertInformationsCompletes(Candidat $candidat): void
    {
        abort_unless($candidat->pere_contact && $candidat->mere_contact, 403, 'Informations incomplètes.');
    }

    /**
     * Empêche de repasser par le paiement une fois l'inscription déjà finalisée.
     */
    private function bloquerSiDejaInscrit(Candidat $candidat)
    {
        if ($candidat->statut === 'inscrit') {
            return redirect()->route('finalisation.confirmation')
                ->with('info', 'Ce candidat est déjà inscrit. Consultez votre espace candidat.');
        }

        return null;
    }
}
