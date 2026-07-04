<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
  // 2. Enregistrer une nouvelle matière (ex: Anglais)
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:matieres,nom',
        ]);

        Matiere::create([
            'nom' => $request->nom
        ]);

        return redirect()->back()->with('success', 'Matière ajoutée avec succès !');
    }

    // 3. MODIFICATION : Mettre à jour le nom d'une matière existante
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:matieres,nom,' . $id,
        ]);

        $matiere = Matiere::findOrFail($id);
        $matiere->update([
            'nom' => $request->nom
        ]);

        return redirect()->back()->with('success', 'La matière a été modifiée avec succès !');
    }
}