<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'date_absence' => 'required|date',
            'periode' => 'required|string',
            'motif' => 'nullable|string',
            // Si tu as une validation ici, assure-toi qu'elle n'utilise pas "rôle" comme règle !
        ]);
        $absence = new \App\Models\Absence();
    
        // 3. Affectation des valeurs du formulaire
        $absence->eleve_id = $request->eleve_id;
        $absence->date_absence = $request->date_absence;
        $absence->periode = $request->periode;
        $absence->motif = $request->motif;
    
        // Vérification de la case à cocher pour la justification (si cochée, renvoie true, sinon false)
        $absence->justifiee = $request->has('absence_justified') ? true : false;

        // 4. Sauvegarde dans la base de données
        $absence->save();

        return redirect()->route('absences.index')->with('success', 'L\'absence a été enregistrée avec succès.');
    }
}