<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            $table->string('numero_dossier')->nullable()->unique()->after('id');
            $table->enum('statut', ['preinscrit', 'en_attente_paiement', 'inscrit'])
                ->default('preinscrit')->after('numero_dossier');

            $table->string('pere_nom')->nullable();
            $table->string('pere_prenom')->nullable();
            $table->string('pere_contact')->nullable();
            $table->string('mere_nom')->nullable();
            $table->string('mere_prenom')->nullable();
            $table->string('mere_contact')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            $table->dropColumn([
                'numero_dossier',
                'statut',
                'pere_nom',
                'pere_prenom',
                'pere_contact',
                'mere_nom',
                'mere_prenom',
                'mere_contact',
            ]);
        });
    }
};
