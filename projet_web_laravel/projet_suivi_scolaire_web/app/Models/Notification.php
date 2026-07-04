<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'parent_id',
        'titre',
        'message',
        'type',
        'lu',
        'lu_le'
    ];

    /**
     * La notification est adressée à un parent
     */
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
}