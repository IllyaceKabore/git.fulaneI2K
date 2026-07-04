<?php

namespace App\Http\Controllers;

use App\Models\ParentEleve; // Importation stricte du modèle
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    /**
     * Afficher la liste de tous les parents inscrits
     */
    public function index()
    {
        $parents = ParentEleve::withCount('enfants')->orderBy('nom', 'asc')->get();
        $elevesSansParent = Eleve::doesntHave('parents')->get(); // On les récupère ici
        return view('parents.index', compact('parents'));
    }

    /**
     * Enregistrer un nouveau parent
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:parents,email',
            'telephone' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        ParentEleve::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('parents.index')->with('success', 'Le compte parent a été créé avec succès.');
    }

    /**
     * Associer un élève existant à ce parent
     */
    public function associerEnfant(Request $request, $id)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
        ]);

        $parent = \App\Models\ParentEleve::findOrFail($id);

        $eleve = \App\Models\Eleve::findOrFail($request->eleve_id);
        $eleve->parent_id = $parent->id;
        $eleve->save();
        $eleve->update([
            //'parent_id' => $parentId
            'parent_id' => $parent->id
        ]);

        return redirect()->back()->with('success', 'L\'élève a bien été rattaché à ce parent.');
    }
}