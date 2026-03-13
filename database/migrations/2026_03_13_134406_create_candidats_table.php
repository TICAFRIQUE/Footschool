// database/migrations/xxxx_create_candidats_table.php

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
            $table->string('telephone');
            $table->string('ville');

            // Éducation
            $table->string('niveau_etudes');

            // Langues parlées (stockées en JSON)
            $table->json('langues')->nullable();

            // Niveaux de maîtrise
            $table->enum('niveau_fr', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();
            $table->enum('niveau_en', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();
            $table->enum('niveau_es', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();

            // Contact d'urgence
            $table->string('urgence_nom');
            $table->string('urgence_tel');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
