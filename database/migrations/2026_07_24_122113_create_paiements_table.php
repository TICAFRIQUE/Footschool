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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidats')->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->unsignedInteger('montant');
            $table->string('moyen')->default('simulation');
            $table->enum('statut', ['en_attente', 'reussi', 'echoue'])->default('en_attente');
            $table->string('transaction_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
