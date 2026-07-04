<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id', 'user_id', 'montant', 'date_versement',
        'mode_paiement', 'reference_recu', 'trimestre', 'observation'
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}