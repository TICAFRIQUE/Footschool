<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();

            // Identité
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->integer('age');
            $table->string('lieu_naissance');
            $table->string('telephone')->unique();
            $table->string('ville')->index();         // ← GROUP BY ville, WHERE ville = ?

            // Éducation
            $table->string('niveau_etudes')->index(); // ← GROUP BY niveau_etudes

            // Langues parlées (stockées en JSON)
            $table->json('langues')->nullable();

            // Niveaux de maîtrise
            $table->enum('niveau_fr', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();
            $table->enum('niveau_en', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();
            $table->enum('niveau_es', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();

            // Contact d'urgence
            $table->string('urgence_nom');
            $table->string('urgence_tel');

            $table->timestamps(); // created_at utilisé pour les stats journalières/hebdo
        });

        // Index composite pour les requêtes de stats temporelles fréquentes :
        // WHERE DATE(created_at) = today  →  ORDER BY created_at DESC  →  COUNT par jour
        Schema::table('candidats', function (Blueprint $table) {
            $table->index('created_at', 'idx_candidats_created_at');

            // Index sur age pour les BETWEEN des tranches d'âge
            $table->index('age', 'idx_candidats_age');

            // Index composite (ville + created_at) pour les futures requêtes
            // du type : "candidats par ville sur les 30 derniers jours"
            $table->index(['ville', 'created_at'], 'idx_candidats_ville_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};