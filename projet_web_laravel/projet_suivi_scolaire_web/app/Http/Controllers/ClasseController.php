<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    // CORRECTION POUR LA PAGE /classes (image_918cdf.png)
    public function index()
    {
        $classes = Classe::withCount('eleves')->get();
        return view('classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:50',
            'capacite_max' => 'required|integer',
            'frais_scolarite' => 'required|numeric',
            'annee_scolaire' => 'required|string',
            'enseignant_id' => 'nullable|exists:users,id',
            'matieres' => 'array'
        ]);

        $classe = Classe::create($validated);
        if ($request->has('matieres')) {
            $classe->matieres()->attach($request->matieres);
        }

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès !');
    } // <-- L'accolade fermante de store est bien isolée ici !

    // CORRECTION POUR LA PAGE /classes/1 (image_ce2fa4.png)
    public function show(Classe $classe)
    {
        // On récupère la classe avec ses élèves et ses matières associées
        $classe->load(['eleves', 'matieres']);
    
        // On récupère toutes les matières existantes en BDD pour le formulaire
        $toutesLesMatieres = \App\Models\Matiere::all();

        return view('classes.show', compact('classe', 'toutesLesMatieres'));
    }

    public function edit(Classe $classe)
    {
        $classe->load('matieres'); // Charger les matières déjà associées

        $matieres = Matiere::all();
        $enseignants = User::where('role', 'enseignant')->get();

        return view('classes.edit', compact('classe', 'matieres', 'enseignants'));
    }

    public function update(Request $request, $id)
    {
    // 1. Validation des données reçues du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'frais_scolarite' => 'required|numeric',
            'annee_scolaire' => 'required|string',
            'enseignant_id' => 'nullable|exists:users,id', // 'enseignant_id' pointe vers la table users
            'matieres' => 'array' // Tableau des IDs des matières cochées
        ]);

    // 2. Trouver la classe correspondante en BDD
        $classe = Classe::findOrFail($id);

    // 3. Mettre à jour les attributs de la classe
        $classe->update([
            'nom' => $request->nom,
            'capacite_max' => $request->capacite_max,
            'frais_scolarite' => $request->frais_scolarite,
            'annee_scolaire' => $request->annee_scolaire,
            'enseignant_id' => $request->enseignant_id, // Liaison avec l'enseignant choisi
        ]);

    // 4. Synchroniser les matières dans la table pivot (relation BelongsToMany)
    // sync() va automatiquement cocher, décocher et nettoyer les matières de la classe
        if ($request->has('matieres')) {
            $classe->matieres()->sync($request->matieres);
        } else {
        // Si aucune matière n'est cochée, on vide la liste pour cette classe
            $classe->matieres()->sync([]);
        }

    // 5. Redirection vers la liste des classes avec un beau message vert de succès
        return redirect()->route('classes.index')
            ->with('success', 'La classe a été modifiée avec succès !');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès !');
    }

    public function syncMatieres(Request $request, $id)
    {
        // On doit d'abord récupérer la classe avec l'ID reçu
        $classe = \App\Models\Classe::findOrFail($id);
        // Utilisation uniforme du Route Model Binding ici aussi
        $classe->matieres()->sync($request->input('matieres', []));

        return redirect()->route('classes.show', $classe->id)->with('success', 'Les matières de la classe ont été mises à jour !');
    }
        
}