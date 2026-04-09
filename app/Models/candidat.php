<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'pieds_fort', // 'gauche' ou 'droit'
        'numero_poste'

    ];

    protected $casts = [
        'date_naissance' => 'date',
        'langues'        => 'array',
        'pieds_fort'     => 'string',
        'numero_poste'   => 'integer',
    ];

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
