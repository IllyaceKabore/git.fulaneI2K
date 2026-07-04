<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Classe;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = User::where('role', 'enseignant')
                           ->with('classeGeree')
                           ->get();
        return view('enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('enseignants.create', compact('classes'));
    }

    public function store(Request $request)
    {
        // 1. Ajout de la validation pour classe_id (optionnelle ou obligatoire selon tes besoins)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'classe_id' => 'nullable|exists:classes,id', 
        ]);

        // 2. Création de l'utilisateur enseignant
        $enseignant = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'enseignant',
        ]);

        // 3. Liaison avec la classe si elle a été sélectionnée (comme dans ton update)
        if ($request->filled('classe_id')) {
            Classe::where('id', $request->classe_id)
                ->update(['enseignant_id' => $enseignant->id]);
        }

        return redirect()->route('enseignants.index')
                         ->with('success', 'Enseignant ajouté avec succès');
    }

    public function edit(User $enseignant)
    {
        $classes = Classe::all();
        return view('enseignants.edit', compact('enseignant', 'classes'));
    }

    public function update(Request $request, User $enseignant)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $enseignant->id,
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $enseignant->update($validated);

        if ($request->filled('classe_id')) {
            Classe::where('enseignant_id', $enseignant->id)
                ->update(['enseignant_id' => null]);

            Classe::where('id', $request->classe_id)
                ->update(['enseignant_id' => $enseignant->id]);
        }

        return redirect()->route('enseignants.index')
                        ->with('success', 'Enseignant modifié avec succès !');
    }

    public function destroy($id)
    {
        $enseignant = User::where('role', 'enseignant')->findOrFail($id);

        // Nettoyage de la classe si l'enseignant est supprimé
        Classe::where('enseignant_id', $enseignant->id)
            ->update(['enseignant_id' => null]);

        $enseignant->delete();

        return redirect()->route('enseignants.index')->with('success', 'L\'enseignant a été supprimé avec succès.');
    }
}