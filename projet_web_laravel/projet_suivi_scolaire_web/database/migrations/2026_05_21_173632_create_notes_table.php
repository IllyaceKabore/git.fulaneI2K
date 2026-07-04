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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // enseignant
            $table->tinyInteger('trimestre'); // 1, 2 ou 3
            $table->decimal('note', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['eleve_id', 'matiere_id', 'trimestre']);
        });
    }
};
