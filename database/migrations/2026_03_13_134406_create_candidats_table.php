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
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->integer('age');
            $table->string('lieu_naissance');
            $table->string('telephone')->unique();
            $table->string('ville');
            $table->string('niveau_etudes');
            $table->json('langues')->nullable();
            $table->enum('niveau_fr', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();
            $table->enum('niveau_en', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();
            $table->enum('niveau_es', ['Débutant', 'Intermédiaire', 'Avancé'])->nullable();

            //pieds fort & numeros de poste
            $table->enum('pieds_fort', ['gauche', 'droit' , 'les deux'])->nullable();
            $table->integer('numero_poste')->nullable(); // Plusieurs candidats peuvent préférer le même poste

            $table->string('urgence_nom');
            $table->string('urgence_tel');
            $table->timestamps();

            // Tous les index dans le même bloc
            $table->index('ville');
            $table->index('niveau_etudes');
            $table->index('created_at', 'idx_candidats_created_at');
            $table->index('age', 'idx_candidats_age');
            $table->index(['ville', 'created_at'], 'idx_candidats_ville_date');
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
