<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F']);
            $table->text('adresse')->nullable();
            $table->string('nom_tuteur');
            $table->string('telephone_tuteur');
            $table->string('photo')->nullable();
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->date('date_inscription');
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }
};