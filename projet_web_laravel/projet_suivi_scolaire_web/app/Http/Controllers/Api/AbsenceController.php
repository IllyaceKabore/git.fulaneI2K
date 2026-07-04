<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Absence; 
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     * Afficher l'interface de toutes les absences
     */
    public function index()
    {
        // 1. On récupère TOUS les élèves avec leur classe pour le formulaire de saisie
        $eleves = Eleve::with('classe')->orderBy('nom', 'asc')->get();

        // 2. On récupère TOUTES les absences pour l'historique (le tableau de droite)
        $absences = Absence::with('eleve.classe')->orderByDesc('date_absence')->get();

        // 3. On envoie les DEUX variables à la vue avec compact()
        return view('absences.index', compact('eleves', 'absences'));
    }
}