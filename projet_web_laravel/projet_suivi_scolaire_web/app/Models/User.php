<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }

    public function isEnseignant()
    {
        return $this->role === 'enseignant';
    }

    public function notesSaisies()
    {
        return $this->hasMany(Note::class);
    }



    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    public function classe()
    {
        //return $this->belongsTo(Classe::class, 'classe_id');
        return $this->belongsTo(Classe::class, 'enseignant_id');
    }
 
    public function classeGeree()
    {
    // Un utilisateur (enseignant) a une seule classe principale
        return $this->hasOne(Classe::class, 'enseignant_id');
    }

    public function enfants()
    {
        return $this->hasMany(Eleve::class, 'parent_id');
    }
}