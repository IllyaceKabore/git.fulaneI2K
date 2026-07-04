<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'type',
        'date_evenement',
        'user_id'
    ];

    /**
     * L'annonce est publiée par un utilisateur (Administration / Enseignant)
     */
    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}