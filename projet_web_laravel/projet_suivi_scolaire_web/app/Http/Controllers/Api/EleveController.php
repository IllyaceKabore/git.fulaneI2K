<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    public function index(Request $request)
    {
        $query = Eleve::with('classe');

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        $eleves = $query->paginate(15);
        $classes = Classe::all();

        return view('eleves.index', compact('eleves', 'classes'));
    }

    public function show(Eleve $eleve)
    {
        $eleve->load(['classe', 'notes.matiere', 'versements.user']);

        // Notes par trimestre
        $notesParTrimestre = [];
        $moyennes = [];

        for ($t = 1; $t <= 3; $t++) {
            $notes = $eleve->notes()->where('trimestre', $t)->with('matiere')->get();
            $notesParTrimestre[$t] = $notes;
            $moyennes[$t] = $notes->avg('note');
        }

        return view('eleves.show', compact('eleve', 'notesParTrimestre', 'moyennes'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('eleves.create', compact('classes'));
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'date_naissance' => 'required|date',
        'sexe' => 'required|in:M,F',
        'nom_tuteur' => 'required|string|max:255',
        'telephone_tuteur' => 'nullable|string|max:20',
        'classe_id' => 'required|exists:classes,id',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('photos/eleves', 'public');
    }

    $validated['matricule'] = 'ELV-' . date('Y') . '-' . str_pad(Eleve::count() + 1, 4, '0', STR_PAD_LEFT);
    $validated['date_inscription'] = now();

    Eleve::create($validated);

    return redirect()->route('eleves.index')
                     ->with('success', 'Élève inscrit avec succès !');
}
       
    public function edit($id)
    {
        $eleve = Eleve::findOrFail($id);
        $classes = Classe::all(); // Nécessaire pour pouvoir changer sa classe dans un menu déroulant

        return view('eleves.edit', compact('eleve', 'classes'));
    }

    public function update(Request $request, $id)
    {
    // 1. 🌟 LA CORRECTION : On récupère l'élève en BDD grâce à l'ID reçu
        $eleve = \App\Models\Eleve::findOrFail($id);

    // 2. Validation des données envoyées par le formulaire
        $validated = $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe'           => 'required|in:M,F',
            'classe_id'      => 'required|exists:classes,id',
            'tuteur'         => 'required|string',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

    // 3. Gestion de la photo si un nouveau fichier est chargé
        if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo physique si elle existe
            if ($eleve->photo && file_exists(public_path('storage/' . $eleve->photo))) {
                unlink(public_path('storage/' . $eleve->photo));
            }

        // Stocker la nouvelle photo et ajouter son chemin au tableau des données validées
            $validated['photo'] = $request->file('photo')->store('eleves', 'public');
        }

    // 4. Mise à jour de l'élève avec toutes les données validées d'un coup
        $eleve->update($validated);

    // 5. Redirection avec message de succès
        return redirect()->route('eleves.index')
                     ->with('success', 'Élève modifié avec succès');
    }

    public function bulletin($id, $trimestre)
    {
        // 1. On récupère l'élève avec sa classe, les matières de sa classe, 
        // et UNIQUEMENT ses notes du trimestre sélectionné
        $eleve = Eleve::with([
            'classe.matieres', 
            'notes' => function ($query) use ($trimestre) {
                $query->where('trimestre', $trimestre);
            }
        ])->findOrFail($id);

        // 2. Calcul des totaux pour la moyenne générale
        $totalPoints = 0;
        $totalCoeff = 0;

        foreach ($eleve->classe->matieres as $matiere) {
            // On récupère toutes les notes de l'élève pour cette matière spécifique au cours du trimestre
            $notesMatiere = $eleve->notes->where('matiere_id', $matiere->id);
        
            if ($notesMatiere->isNotEmpty()) {
                // Si la colonne dans ta table 'notes' s'appelle 'note' au lieu de 'valeur', 
                // remplace 'valeur' par 'note' ci-dessous
                $moyenneMatiere = $notesMatiere->avg('valeur') ?? $notesMatiere->avg('note') ?? 0;
            
                // On applique le coefficient de la matière
                $coef = $matiere->coefficient ?? 1;
                $totalPoints += ($moyenneMatiere * $coef);
                $totalCoeff += $coef;
            
                // Optionnel : On attache dynamiquement la moyenne calculée à l'objet matière 
                // pour qu'elle soit facilement accessible dans la vue
                $matiere->moyenne_calculee = $moyenneMatiere;
            } else {
                $matiere->moyenne_calculee = null; // Aucune note saisie pour le moment
            }
        }

        // 3. Calcul de la moyenne générale finale du trimestre
        $moyenneGenerale = $totalCoeff > 0 ? $totalPoints / $totalCoeff : 0;
        $rang = 1; // Tu pourras coder la logique des rangs plus tard

        // 4. On renvoie la vue avec les données exactes dont elle a besoin
        return view('eleves.bulletin', compact('eleve', 'moyenneGenerale', 'trimestre', 'rang'));
    }
}