<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentApiController extends Controller
{
    /**
     * Récupérer les données du tableau de bord pour l'application mobile du parent
     */
    public function getDashboard(Request $request)
    {
        $parent = $request->user(); // Récupère le parent connecté via le Token API (Sanctum)

        // 🟢 Vérification et chargement des relations de manière optimisée
        // On charge la classe, les absences et les notes de chaque enfant rattaché
        $enfants = $parent->enfants()->with(['classe', 'absences', 'notes.matiere'])->get();

        // 🟢 Formatage propre pour simplifier le travail de ton modèle Kotlin (LoginResponse / ElevesResponse)
        $enfantsFormates = $enfants->map(function ($eleve) {
            // Calculer la moyenne générale de l'élève à la volée si nécessaire
            $moyenne = $eleve->notes->avg('valeur'); 

            return [
                'id' => $eleve->id,
                'nom' => $eleve->nom,
                'prenom' => $eleve->prenom,
                'classe' => $eleve->classe->libelle ?? 'Non assignée',
                'photo' => $eleve->photo ? asset('storage/' . $eleve->photo) : null,
                'moyenne_generale' => $moyenne ? round($moyenne, 2) : null,
                'total_absences' => $eleve->absences->count(),
                'absences' => $eleve->absences->map(fn($abs) => [
                    'id' => $abs->id,
                    'date' => $abs->date_absence,
                    'motif' => $abs->motif,
                    'justifiee' => (bool)$abs->est_justifiee,
                ]),
                'notes' => $eleve->notes->map(fn($note) => [
                    'id' => $note->id,
                    'matiere' => $note->matiere->nom ?? 'Inconnue',
                    'note' => $note->valeur,
                    'trimestre' => $note->trimestre,
                ]),
            ];
        });

        return response()->json([
            'success' => true,
            'parent' => [
                'id' => $parent->id,
                'nom' => $parent->nom,
                'prenom' => $parent->prenom,
                'email' => $parent->email,
                'telephone' => $parent->telephone,
            ],
            'enfants' => $enfantsFormates
        ], 200);
    }

    /**
     * Récupérer les notifications / annonces pour le parent
     */
    public function getNotifications(Request $request) 
    {
        $parent = $request->user();
    
        // 🟢 Requête propre ordonnée par date de création
        // Si tu as une table d'annonces globales ou spécifiques, ajuste la condition
        $notifications = \DB::table('notifications')
            ->where('parent_id', $parent->id)
            ->orWhereNull('parent_id') // Pour capter aussi les annonces globales d'établissement
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($notif) => [
                'id' => $notif->id,
                'titre' => $notif->titre ?? 'Notification',
                'contenu' => $notif->contenu ?? $notif->message,
                'lu' => isset($notif->read_at) ? true : false,
                'date' => \Carbon\Carbon::parse($notif->created_at)->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ], 200);
    }
}