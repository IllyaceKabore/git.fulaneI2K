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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // qui a encaissé
            $table->decimal('montant', 10, 2);
            $table->date('date_versement');
            $table->enum('mode_paiement', ['especes', 'mobile_money', 'banque', 'autre']);
            $table->string('reference_recu')->unique();
            $table->tinyInteger('trimestre')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }
};
