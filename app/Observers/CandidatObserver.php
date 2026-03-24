<?php

namespace App\Observers;

use App\Models\Candidat;
use App\Services\StatsService;

/**
 * Invalide le cache du dashboard à chaque modification du modèle Candidat.
 *
 * Enregistrement dans App\Providers\AppServiceProvider :
 *
 *   public function boot(): void
 *   {
 *       Candidat::observe(CandidatObserver::class);
 *   }
 */
class CandidatObserver
{
    public function created(Candidat $candidat): void
    {
        StatsService::flushCache();
    }

    public function updated(Candidat $candidat): void
    {
        StatsService::flushCache();
    }

    public function deleted(Candidat $candidat): void
    {
        StatsService::flushCache();
    }
}