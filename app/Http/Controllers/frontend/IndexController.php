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
    public function index2()
    {
        $data = config('ville_commune');
        return view('frontend.index2', compact('data'));
    }

    // ✅ Normalisation téléphone
    private function normalizePhone($phone)
    {
        return preg_replace('/\D/', '', $phone);
    }

    // ✅ Normalisation texte
    private function normalizeText($text)
    {
        return preg_replace('/\s+/', ' ', trim($text));
    }

    public function store(Request $request)
    {
        // ── 1. VALIDATION ─────────────────────────
        $validated = $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:150'],
            'jour'  => ['required', 'numeric', 'between:1,31'],
            'mois'  => ['required', 'numeric', 'between:1,12'],
            'annee' => ['required', 'numeric', 'between:1900,' . now()->year],
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
        ]);

        // ── 2. NORMALISATION ──────────────────────
        $nom = strtoupper($this->normalizeText($validated['nom']));
        $prenom = ucfirst(strtolower($this->normalizeText($validated['prenom'])));
        $telephone = $this->normalizePhone($validated['candidatTel']);
        $urgenceTel = $this->normalizePhone($validated['urgenceTel']);

        // ── 3. DATE ───────────────────────────────
        try {
            $dateNaissance = Carbon::createFromDate(
                (int) $validated['annee'],
                (int) $validated['mois'],
                (int) $validated['jour']
            );

            if (
                $dateNaissance->day != (int) $validated['jour'] ||
                $dateNaissance->month != (int) $validated['mois'] ||
                $dateNaissance->year != (int) $validated['annee']
            ) {
                return response()->json([
                    'message' => 'Date invalide',
                    'errors'  => ['jour' => ['Date incorrecte']],
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Date invalide',
                'errors'  => ['jour' => ['Date incorrecte']],
            ], 422);
        }

        // ── 4. AGE ────────────────────────────────
        $age = $dateNaissance->age;

        if ($age < 17 || $age > 22) {
            return response()->json([
                'message' => 'Âge non valide',
                'errors'  => ['annee' => ['17 à 22 ans requis']],
            ], 422);
        }

        // ── 5. DOUBLONS ───────────────────────────
        if (Candidat::where('telephone', $telephone)->exists()) {
            return response()->json([
                'message' => 'Numéro déjà utilisé',
                'errors'  => ['candidatTel' => ['Numéro déjà utilisé']],
            ], 422);
        }

        if (
            Candidat::where('nom', $nom)
            ->where('prenom', $prenom)
            ->where('date_naissance', $dateNaissance->toDateString())
            ->exists()
        ) {
            return response()->json([
                'message' => 'Déjà inscrit',
                'errors'  => ['nom' => ['Candidat déjà existant']],
            ], 422);
        }

        // ── 6. SAVE ───────────────────────────────
        Candidat::create([
            'nom'            => $nom,
            'prenom'         => $prenom,
            'date_naissance' => $dateNaissance->toDateString(),
            'age'            => $age,
            'lieu_naissance' => $this->normalizeText($validated['lieuNaissance']),
            'telephone'      => $telephone,
            'ville'          => $validated['ville'],
            'niveau_etudes'  => $validated['niveau'],
            'langues'        => $validated['langues'],
            'niveau_fr'      => $validated['niveau_fr'] ?? null,
            'niveau_en'      => $validated['niveau_en'] ?? null,
            'niveau_es'      => $validated['niveau_es'] ?? null,
            'urgence_nom'    => $this->normalizeText($validated['urgenceNom']),
            'urgence_tel'    => $urgenceTel,
        ]);

        return response()->json([
            'message' => 'Inscription réussie',
        ], 200);
    }
}
