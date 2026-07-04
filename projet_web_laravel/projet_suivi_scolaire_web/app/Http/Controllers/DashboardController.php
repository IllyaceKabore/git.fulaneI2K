<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Versement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEleves = Eleve::count();
        $totalClasses = Classe::count();

        // Statistiques financières
        $totalAttendu = Classe::sum('frais_scolarite') * $totalEleves;
        $totalCollecte = Versement::sum('montant');

        // Élèves en retard de paiement
        $elevesImpayes = Eleve::with('classe')
            ->get()
            ->filter(fn($eleve) => $eleve->resteAPayer() > 0);

        // Classement corrigé (solution stable)
        $classements = Classe::with(['eleves' => function ($query) {
            $query->leftJoin('notes', 'eleves.id', '=', 'notes.eleve_id')
                  ->select([
                      'eleves.id',
                      'eleves.matricule',
                      'eleves.nom',
                      'eleves.prenom',
                      'eleves.classe_id',
                      DB::raw('COALESCE(AVG(notes.note), 0) as moyenne')
                  ])
                  ->groupBy('eleves.id', 'eleves.matricule', 'eleves.nom', 'eleves.prenom', 'eleves.classe_id')
                  ->orderByDesc('moyenne')
                  ->take(8);
        }])->get();

        return view('dashboard.index', compact(
            'totalEleves', 
            'totalClasses', 
            'totalCollecte', 
            'totalAttendu', 
            'elevesImpayes', 
            'classements'
        ));
    }
}