<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ParentEleve extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'parents';

    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'telephone', 'photo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    /*public function enfants()
    {
        return $this->hasMany(Eleve::class, 'parent_id');
    }*/

    public function enfants()
    {
        // On indique à Laravel qu'un parent a plusieurs enfants via la table parent_eleve
        //return $this->belongsToMany(Eleve::class, 'parent_eleve', 'parent_id', 'eleve_id');
        return $this->hasMany(Eleve::class, 'parent_id');
    }    

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'parent_id');
    }
}