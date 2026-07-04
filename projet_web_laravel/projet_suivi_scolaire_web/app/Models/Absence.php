<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'date_absence',
        'periode',
        'motif',
        'justifiee',
    ];

    protected $casts = [
        'date_absence' => 'date',
        'justifiee'    => 'boolean',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}