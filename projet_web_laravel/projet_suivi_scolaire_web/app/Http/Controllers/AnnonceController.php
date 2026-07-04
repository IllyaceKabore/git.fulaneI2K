<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    /**
     * Afficher la liste des annonces (Grille) et le formulaire.
     */
    public function index()
    {
        // Récupère toutes les annonces de la plus récente à la plus ancienne
        $annonces = Annonce::with('auteur')->orderBy('created_at', 'desc')->get();

        // Renvoie vers la vue avec les données
        return view('annonces.index', compact('annonces'));
    }

    /**
     * Enregistrer une nouvelle annonce dans la base de données.
     */
    public function store(Request $request)
    {
        // 1. Validation des données du formulaire CSS
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string|in:generale,reunion,examen,paiement',
            'date_evenement' => 'nullable|date',
            'contenu' => 'required|string',
        ]);

        // 2. Création de l'annonce reliée à l'utilisateur connecté (Admin/Enseignant)
        Annonce::create([
            'titre' => $request->titre,
            'type' => $request->type,
            'date_evenement' => $request->date_evenement,
            'contenu' => $request->contenu,
            'user_id' => Auth::id(), // ID de la personne qui publie l'annonce depuis l'espace Web
        ]);

        // 3. Redirection avec un message de succès
        return redirect()->route('annonces.index')->with('success', 'L\'annonce a été diffusée avec succès aux parents !');
    }
}