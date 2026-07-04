<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // Liaison avec le parent qui doit recevoir la notification
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            
            $table->string('titre');
            $table->text('message');
            $table->string('type')->default('info'); // Ex: 'absence', 'paiement', 'note', 'info'
            
            // Permet de savoir si le parent a ouvert la notification sur son téléphone
            $table->boolean('lu')->default(false); 
            $table->timestamp('lu_le')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};