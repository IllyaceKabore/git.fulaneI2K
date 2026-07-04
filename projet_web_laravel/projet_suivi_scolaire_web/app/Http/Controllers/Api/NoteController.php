<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::with(['eleve', 'matiere', 'enseignant'])
                    ->latest()
                    ->paginate(20);
        return view('notes.index', compact('notes'));
    }

    public function saisieParClasse(Request $request, $classe_id = null) 
    {
        $user = auth()->user();

    // SÉCURITÉ : Si c'est un enseignant, on le force STRICTEMENT à n'utiliser que SA classe
        if ($user->role === 'enseignant') {
        // On charge la classe qu'il gère
            $classeEnseignant = Classe::where('enseignant_id', $user->id)->first();
        
            if (!$classeEnseignant) {
            return redirect()->route('dashboard')->with('error', "Vous n'êtes assigné à aucune classe. Contactez le gestionnaire.");
            }
        
        // On écrase l'ID demandé par l'ID de sa propre classe
            $classe_id = $classeEnseignant->id;
        }    

        // 1. On récupère toutes les classes ordonnées pour le sélecteur (Cahier des charges burkinabè)
        $classes = Classe::orderByRaw("FIELD(nom, 'CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2')")->get();

        // 2. SYNCHRONISATION : On cherche l'ID partout (URL, puis formulaire/Query, puis Session)
        $classe_id = $classe_id ?? $request->input('classe_id') ?? session('classe_id');
        $trimestre = $request->input('trimestre') ?? session('trimestre', 'Trimestre 1');

        $eleves = collect();
        $matieres = collect();

        // 3. Si on a trouvé un ID de classe valide, on charge les élèves et les matières
        if ($classe_id) {
            $classe = Classe::find($classe_id);

            if ($classe) {
                $eleves = Eleve::where('classe_id', $classe_id)->orderBy('nom', 'asc')->get();
            
                // Récupération des matières de la classe via la relation Many-to-Many
                $matieres = $classe->matieres; 
                
                // Sécurité : Si le tableau de bord ou la table pivot est vide, on force pour tester
                if ($matieres->isEmpty()) {
                    $matieres = Matiere::all();
                }
            }
        }        

        // 4. On renvoie tout à la vue Blade
        return view('notes.saisie', compact('classes', 'classe_id', 'trimestre', 'eleves', 'matieres'));
    }

    // Enregistrement multiple de notes
    public function storeMultiple(Request $request)
    {
            if (is_string($request->trimestre)) {
                if (str_contains($request->trimestre, '1')) $request->merge(['trimestre' => 1]);
                elseif (str_contains($request->trimestre, '2')) $request->merge(['trimestre' => 2]);
                elseif (str_contains($request->trimestre, '3')) $request->merge(['trimestre' => 3]);
    }
            //dd($request->all());
            $request->validate([
            'trimestre' => 'required|integer|in:1,2,3',
            'notes'     => 'required|array',
            'notes.*.*'   => 'required|numeric|min:0|max:10',
        ]);

        $user_id = auth()->id();
        $trimestre = $request->trimestre;
        $count = 0;

        foreach ($request->notes as $data) {
            if (isset($data['note']) && $data['note'] !== '' && $data['note'] !== null) {
            
                Note::updateOrCreate(
                    [
                        'eleve_id'   => $data['eleve_id'],
                        'matiere_id' => $data['matiere_id'],
                        'trimestre'  => $trimestre,
                    ],
                    [
                        'user_id' => $user_id,
                        'note'    => $data['note'],
                    ]
                );
                $count++;
            }
        }
        if ($count > 0) {
            /*return redirect()->back()
                         ->with('success', $count . ' note(s) enregistrée(s) avec succès pour le Trimestre ' . $trimestre);*/
            return redirect()->route('notes.index', [
                    'classe_id' => $request->input('classe_id', 1), // Récupère l'ID de la classe depuis le formulaire
                    'trimestre' => $trimestre
                        ])->with('success', $count . ' note(s) enregistrée(s) avec succès pour le Trimestre ' . $trimestre);             
        } else {
            return redirect()->back()
                         ->with('info', 'Aucune note n\'a été saisie.');
        }
    }

    public function create()
    {
        return redirect()->route('notes.saisie');
    }

    public function show(Note $note)
    {
        $note->load(['eleve', 'matiere', 'enseignant']);
        return view('notes.show', compact('note'));
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->back()->with('success', 'Note supprimée');
    }

    // Afficher le formulaire de modification individuelle
    public function edit($id)
    {
    // Charge la note avec ses relations pour l'en-tête informatif
        $note = \App\Models\Note::with(['eleve.classe', 'matiere'])->findOrFail($id);
        $this->authorize('gererNotes', $classe);
        return view('notes.edit', compact('note'));
    }

    // Enregistrer le changement
    public function update(Request $request, Eleve $eleve)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'prenom'        => 'required|string|max:255',
            'date_naissance'=> 'required|date',
            'sexe'          => 'required|in:M,F',
            'classe_id'     => 'required|exists:classes,id',
            'tuteur'        => 'required|string|max:255',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

    // Gestion de la photo
        if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo si elle existe
            if ($eleve->photo) {
             \Storage::disk('public')->delete($eleve->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos/eleves', 'public');
        }

        $eleve->update($validated);

        return redirect()->route('eleves.index')
                        ->with('success', 'Élève modifié avec succès !');
    } 
}