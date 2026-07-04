<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // CP1, CP2, CE1, CE2, CM1, CM2
            $table->integer('capacite_max')->default(50);
            $table->decimal('frais_scolarite', 10, 2); // montant annuel ou trimestriel
            $table->string('annee_scolaire')->default('2025-2026');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};