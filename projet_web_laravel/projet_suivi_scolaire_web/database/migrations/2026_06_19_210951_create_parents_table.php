<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telephone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('parent_eleve', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->enum('lien', ['pere', 'mere', 'tuteur'])->default('tuteur');
            $table->boolean('est_principal')->default(false);
            $table->timestamps();

            $table->unique(['parent_id', 'eleve_id']);
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_eleve');
        Schema::dropIfExists('parents');
        Schema::dropIfExists('personal_access_tokens');
    }
};