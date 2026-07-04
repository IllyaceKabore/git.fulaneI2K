<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
    ];

    /**
     * Relation avec les classes (Many-to-Many)
     */
    public function classes()
    {
        return $this->belongsToMany(Classe::class);
    }

    /**
     * Relation avec les notes
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Accesseur pour afficher le nom complet (ex: "Français (FR)")
     */
    public function getNomCompletAttribute()
    {
        return $this->nom . ($this->code ? " ({$this->code})" : '');
    }
}