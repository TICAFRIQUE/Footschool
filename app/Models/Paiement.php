<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'candidat_id',
        'reference',
        'montant',
        'moyen',
        'statut', // 'en_attente', 'reussi', 'echoue'
        'transaction_id',
        'payload',
    ];

    protected $casts = [
        'montant' => 'integer',
        'payload' => 'array',
    ];

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }
}
