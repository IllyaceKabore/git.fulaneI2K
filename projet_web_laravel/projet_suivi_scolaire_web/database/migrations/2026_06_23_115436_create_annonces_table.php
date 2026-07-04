<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->string('type')->default('generale'); // Ex: examen, reunion, paiement, generale
            $table->date('date_evenement')->nullable(); // Date de la réunion ou de l'échéance d'examen
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Auteur (Admin/Enseignant depuis l'espace Web)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};