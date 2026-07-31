<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Format : FS + 3 chiffres (ex. FS001), 5 caractères au total.
     *
     * Le "max" est calculé uniquement parmi les numéros déjà au bon format
     * (^FS[0-9]{3}$) pour ignorer les anciens numéros (ex. FS2026000001)
     * qui fausseraient sinon le calcul.
     */
    public function genererNumeroDossier(): string
    {
        if ($this->numero_dossier) {
            return $this->numero_dossier;
        }

        $dernier = static::where('numero_dossier', 'REGEXP', '^FS[0-9]{3}$')
            ->orderByDesc('numero_dossier')
            ->value('numero_dossier');

        $prochain = $dernier ? ((int) substr($dernier, 2)) + 1 : 1;

        $this->numero_dossier = 'FS' . str_pad((string) $prochain, 3, '0', STR_PAD_LEFT);
        $this->save();

        return $this->numero_dossier;
    }

    /**
     * Lien WhatsApp pré-rempli pour envoyer la preuve de paiement, avec
     * nom complet et numéro de dossier pour faciliter la vérification admin.
     */
    public function lienPreuveWhatsapp(): string
    {
        $message = "Bonjour, voici la preuve de paiement de mon inscription SchoolFoot.\n"
            . "Nom complet : {$this->prenom} " . strtoupper($this->nom) . "\n"
            . "Numéro de dossier : {$this->numero_dossier}\n"
            . "Téléphone : {$this->telephone}";

        return 'https://wa.me/' . config('payment.whatsapp_number') . '?text=' . rawurlencode($message);
    }

    /**
     * Annule l'inscription (phase 2) : remet le candidat à l'état préinscrit
     * (numéro de dossier, date d'inscription et infos parents effacés).
     * La préinscription elle-même (nom, téléphone, etc.) n'est jamais touchée.
     */
    public function reinitialiserInscription(): void
    {
        $this->update([
            'statut'        => 'preinscrit',
            'numero_dossier' => null,
            'inscrit_at'    => null,
            'pere_nom'      => null,
            'pere_prenom'   => null,
            'pere_contact'  => null,
            'mere_nom'      => null,
            'mere_prenom'   => null,
            'mere_contact'  => null,
        ]);
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
