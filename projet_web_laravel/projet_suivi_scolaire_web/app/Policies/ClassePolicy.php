<?php

namespace App\Policies;

use App\Models\Classe;
use App\Models\User;

class ClassePolicy
{
    /**
     * Règle générale : Les admins/gestionnaires peuvent tout faire
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Déterminer si l'utilisateur peut voir le bulletin ou modifier les notes de la classe
     */
    public function gererNotes(User $user, Classe $classe)
    {
        // L'enseignant doit être celui assigné à cette classe spécifique
        // (Adapte 'enseignant_id' selon le nom de ta colonne dans ta table 'classes')
        return $user->id === $classe->enseignant_id;
    }
}