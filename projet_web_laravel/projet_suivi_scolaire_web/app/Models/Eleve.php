<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = [
    'matricule', 'nom', 'prenom', 'date_naissance', 'sexe',
    'adresse', 'nom_tuteur', 'telephone_tuteur', 'photo',
    'classe_id', 'date_inscription', 'statut', 'parent_id', 'enseignant' // ← ajouter
];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    // Calcul du total payé
        // Total payé
    public function totalPaye()
    {
        return $this->versements()->sum('montant');
    }

    // Reste à payer (sécurisé)
    public function resteAPayer()
    {
        if (!$this->classe) {
            return 0;
        }
        return $this->classe->frais_scolarite - $this->totalPaye();
    }

    // Vérifie si l'élève est en retard
    public function estEnRetard()
    {
        return $this->resteAPayer() > 0;
    }

    public function absences()
    {
        return $this->hasMany(Absence::class)->orderBy('date_absence', 'desc');
    }

    public function parents()
    {
        //return $this->belongsToMany(ParentEleve::class, 'parent_eleve', 'eleve_id', 'parent_id');
        return $this->belongsTo(ParentEleve::class, 'parent_id');
    }    

}