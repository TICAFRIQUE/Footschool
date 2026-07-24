<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class Candidat extends Model
{
    protected $table = 'candidats';

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'age',
        'lieu_naissance',
        'telephone',
        'ville',
        'niveau_etudes',
        'langues',
        'niveau_fr',
        'niveau_en',
        'niveau_es',
        'urgence_nom',
        'urgence_tel',
        'pieds_fort', // 'gauche' ou 'droit' ou 'les deux'
        'numero_poste',
        'numero_dossier',
        'statut', // 'preinscrit', 'en_attente_paiement', 'inscrit'
        'inscrit_at',
        'pere_nom',
        'pere_prenom',
        'pere_contact',
        'mere_nom',
        'mere_prenom',
        'mere_contact',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'langues'        => 'array',
        'pieds_fort'     => 'string',
        'numero_poste'   => 'integer',
        'inscrit_at'     => 'datetime',
    ];

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function dernierPaiementReussi(): ?Paiement
    {
        return $this->paiements()->where('statut', 'reussi')->latest()->first();
    }

    /**
     * Génère le numéro de dossier (une seule fois, à la phase inscription).
     */
    public function genererNumeroDossier(): string
    {
        if ($this->numero_dossier) {
            return $this->numero_dossier;
        }

        $this->numero_dossier = IdGenerator::generate([
            'table'  => 'candidats',
            'field'  => 'numero_dossier',
            'prefix' => 'FS' . date('Y'),
            'length' => 12,
        ]);
        $this->save();

        return $this->numero_dossier;
    }

    // ✅ Normalisation téléphone — partagée entre préinscription et phase inscription
    public static function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    // ✅ Normalisation texte — partagée entre préinscription et phase inscription
    public static function normalizeText(string $text): string
    {
        return preg_replace('/\s+/', ' ', trim($text));
    }

    /**
     * Calcule l'âge depuis la date de naissance.
     */
    public static function calculerAge(string $dateNaissance): int
    {
        return Carbon::parse($dateNaissance)->age;
    }

    /**
     * Accessor : âge calculé dynamiquement depuis la DB.
     */
    public function getAgeCalculeAttribute(): int
    {
        return Carbon::parse($this->date_naissance)->age;
    }

    /**
     * Nom complet du candidat.
     */
    public function getNomCompletAttribute(): string
    {
        return strtoupper($this->nom) . ' ' . $this->prenom;
    }
}
