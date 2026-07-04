<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'capacite_max', 'frais_scolarite', 'annee_scolaire', 'enseignant_id'];

    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'classe_matiere');
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
        //return $this->belongsTo(User::class, 'user_id');
    }

    public function baremeMax()
    {
    // On définit les classes dont les notes sont sur 10
        $classesSurDix = ['CP1', 'CP2', 'CE1', 'CE2'];

    // Si le nom de la classe est dans la liste, le max est 10, sinon c'est 20
        return in_array(strtoupper($this->nom), $classesSurDix) ? 10 : 20;
    }
}