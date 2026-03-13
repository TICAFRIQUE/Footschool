<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $data = config('ville_commune');

        return view('frontend.index', compact('data'));
    }

    public function store(Request $request)
    {

        // ── 1. VALIDATION ──────────────────────────────────────────────
        $validated = $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:150'],
            'jour'          => ['required', 'integer', 'between:1,31'],
            'mois'          => ['required', 'integer', 'between:1,12'],
            'annee'         => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'lieuNaissance' => ['required', 'string', 'max:150'],
            'candidatTel'   => ['required', 'string', 'min:8', 'max:20'],
            'ville'         => ['required', 'string', 'max:150'],
            'niveau'        => ['required', 'string'],
            'langues'       => ['required', 'array', 'min:1'],
            'langues.*'     => ['in:Français,Anglais,Espagnol'],
            'niveau_fr'     => ['nullable', 'in:Débutant,Intermédiaire,Avancé'],
            'niveau_en'     => ['nullable', 'in:Débutant,Intermédiaire,Avancé'],
            'niveau_es'     => ['nullable', 'in:Débutant,Intermédiaire,Avancé'],
            'urgenceNom'    => ['required', 'string', 'max:200'],
            'urgenceTel'    => ['required', 'string', 'min:8', 'max:20'],
        ], [
            'nom.required'           => 'Veuillez entrer votre nom.',
            'prenom.required'        => 'Veuillez entrer vos prénoms.',
            'jour.required'          => 'Le jour de naissance est requis.',
            'jour.between'           => 'Le jour doit être compris entre 1 et 31.',
            'mois.required'          => 'Le mois de naissance est requis.',
            'mois.between'           => 'Le mois doit être compris entre 1 et 12.',
            'annee.required'         => 'L\'année de naissance est requise.',
            'annee.min'              => 'L\'année semble invalide.',
            'lieuNaissance.required' => 'Veuillez entrer votre lieu de naissance.',
            'candidatTel.required'   => 'Veuillez entrer votre numéro de téléphone.',
            'candidatTel.min'        => 'Le numéro doit contenir au moins 8 chiffres.',
            'ville.required'         => 'Veuillez sélectionner votre ville.',
            'niveau.required'        => 'Veuillez sélectionner votre niveau d\'études.',
            'langues.required'       => 'Cochez au moins une langue.',
            'langues.min'            => 'Cochez au moins une langue.',
            'urgenceNom.required'    => 'Veuillez entrer le nom du contact d\'urgence.',
            'urgenceTel.required'    => 'Entrez un numéro valide pour le contact d\'urgence.',
            'urgenceTel.min'         => 'Le numéro doit contenir au moins 8 chiffres.',
        ]);

        // ── 2. CONSTRUCTION DATE ───────────────────────────────────────
        try {
            $dateNaissance = Carbon::createFromDate(
                (int) $validated['annee'],
                (int) $validated['mois'],
                (int) $validated['jour']
            );

            // Vérifie que la date construite correspond bien aux valeurs saisies
            // (ex: évite le 31 février qui serait silencieusement converti)
            if (
                $dateNaissance->day   != (int) $validated['jour'] ||
                $dateNaissance->month != (int) $validated['mois'] ||
                $dateNaissance->year  != (int) $validated['annee']
            ) {
                return back()
                    ->withInput()
                    ->withErrors(['jour' => 'La date de naissance est invalide (ex: 31 février n\'existe pas).']);
            }
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['jour' => 'La date de naissance est invalide.']);
        }

        // ── 3. CALCUL ÂGE ──────────────────────────────────────────────
        $age = $dateNaissance->age;

        if ($age < 17 || $age > 22) {
            return back()
                ->withInput()
                ->withErrors(['jour' => 'Tu dois avoir entre 17 et 22 ans pour participer.']);
        }

        // ── 4. ENREGISTREMENT ──────────────────────────────────────────
        Candidat::create([
            'nom'            => strtoupper(trim($validated['nom'])),
            'prenom'         => trim($validated['prenom']),
            'date_naissance' => $dateNaissance->toDateString(),  // format YYYY-MM-DD
            'age'            => $age,
            'lieu_naissance' => trim($validated['lieuNaissance']),
            'telephone'      => trim($validated['candidatTel']),
            'ville'          => $validated['ville'],
            'niveau_etudes'  => $validated['niveau'],
            'langues'        => $validated['langues'],           // cast array → JSON auto
            'niveau_fr'      => $validated['niveau_fr'] ?? null,
            'niveau_en'      => $validated['niveau_en'] ?? null,
            'niveau_es'      => $validated['niveau_es'] ?? null,
            'urgence_nom'    => trim($validated['urgenceNom']),
            'urgence_tel'    => trim($validated['urgenceTel']),
        ]);

        // ── 5. REDIRECTION ─────────────────────────────────────────────
        // ── 5. RÉPONSE JSON ────────────────────────────────────────────
        return response()->json([
            'message' => '✅ Ta préinscription a bien été envoyée ! Notre équipe te contactera sous 72h.',
        ], 200);
    }
}
