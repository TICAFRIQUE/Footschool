<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


/**
 * Ajoute des index sur les colonnes fréquemment filtrées/groupées.
 * Gains estimés : x5 à x20 sur les requêtes du dashboard.
 *
 * php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            // Index simple sur ville (GROUP BY, WHERE ville = ...)
            if (!$this->hasIndex('candidats', 'candidats_ville_index')) {
                $table->index('ville', 'candidats_ville_index');
            }

            // Index simple sur niveau_etudes (GROUP BY)
            if (!$this->hasIndex('candidats', 'candidats_niveau_etudes_index')) {
                $table->index('niveau_etudes', 'candidats_niveau_etudes_index');
            }

            // Index sur created_at (WHERE DATE(created_at) = today, range queries)
            if (!$this->hasIndex('candidats', 'candidats_created_at_index')) {
                $table->index('created_at', 'candidats_created_at_index');
            }

            // Index sur age (BETWEEN pour tranches d'âge)
            if (!$this->hasIndex('candidats', 'candidats_age_index')) {
                $table->index('age', 'candidats_age_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            $table->dropIndexIfExists('candidats_ville_index');
            $table->dropIndexIfExists('candidats_niveau_etudes_index');
            $table->dropIndexIfExists('candidats_created_at_index');
            $table->dropIndexIfExists('candidats_age_index');
        });
    }

    /**
     * Vérifie si un index existe déjà (évite les doublons en migration).
     */
    protected function hasIndex(string $table, string $indexName): bool
    {
        return collect(\DB::select("SHOW INDEX FROM `{$table}`"))
            ->contains('Key_name', $indexName);
    }
};
