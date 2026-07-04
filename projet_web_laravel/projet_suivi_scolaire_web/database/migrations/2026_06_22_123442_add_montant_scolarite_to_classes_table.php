<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Montant total annuel dû pour l'inscription dans cette classe (ex: 50000)
            $table->integer('montant_scolarite')->default(0)->after('nom');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('montant_scolarite');
        });
    }
};