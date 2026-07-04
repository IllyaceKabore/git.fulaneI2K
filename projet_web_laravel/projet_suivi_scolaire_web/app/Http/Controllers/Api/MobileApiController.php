<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Contrôleur dédié à l'application mobile (School_Connect).
 * Chaque réponse JSON correspond EXACTEMENT aux modèles Gson de l'app Kotlin
 * (ElevesResponse, NotesResponse, PaiementsResponse, AbsencesResponse, NotificationsResponse).
 */
class MobileApiController extends Controller
{
    /**
     * Récupère un élève appartenant bien au parent connecté.
     * Renvoie null si l'élève n'existe pas ou n'appartient pas au parent (sécurité).
     */
    private function eleveDuParent(Request $request, $id)
    {
        return $request->user()->enfants()->where('id', $id)->first();
    }

    private function interdit()
    {
        return response()->json([
            'status'  => false,
            'message' => "Cet élève n'est pas rattaché à votre compte.",
        ], 403);
    }

    /**
     * Formate un élève au format attendu par EleveModel (Kotlin),
     * avec la classe sous forme d'OBJET { id, libelle, slug }.
     */
    private function formatEleve($e): array
    {
        return [
            'id'                => $e->id,
            'matricule'         => $e->matricule,
            'nom'               => $e->nom,
            'prenom'            => $e->prenom,
            'classe_id'         => $e->classe_id,
            'date_naissance'    => $e->date_naissance,
            'sexe'              => $e->sexe,
            'nom_tuteur'        => $e->nom_tuteur,
            'telephone_tuteur'  => $e->telephone_tuteur,
            'date_inscription'  => $e->date_inscription,
            'photo'             => $e->photo ? asset('storage/' . $e->photo) : null,
            'classe'            => $e->classe ? [
                'id'      => $e->classe->id,
                // La table 'classes' utilise la colonne 'nom' -> mappée vers 'libelle' pour Kotlin
                'libelle' => $e->classe->nom,
                'slug'    => $e->classe->slug ?? null,
            ] : null,
        ];
    }

    /**
     * GET /api/eleves
     * Réponse : { status, eleves: [EleveModel] }
     */
    public function eleves(Request $request)
    {
        $eleves = $request->user()->enfants()->with('classe')->get()
            ->map(fn($e) => $this->formatEleve($e));

        return response()->json([
            'status' => true,
            'eleves' => $eleves,
        ], 200);
    }

    /**
     * GET /api/eleves/{id}
     * Réponse : { status, eleve: EleveModel }
     */
    public function eleve(Request $request, $id)
    {
        $eleve = $this->eleveDuParent($request, $id);
        if (!$eleve) {
            return $this->interdit();
        }
        $eleve->load('classe');

        return response()->json([
            'status' => true,
            'eleve'  => $this->formatEleve($eleve),
        ], 200);
    }

    /**
     * GET /api/eleves/{id}/notes
     * Réponse : { status, notes: [NoteModel] }
     * Mapping : notes.note -> note, notes.trimestre -> periode
     */
    public function notes(Request $request, $id)
    {
        $eleve = $this->eleveDuParent($request, $id);
        if (!$eleve) {
            return $this->interdit();
        }

        $notes = $eleve->notes()->with('matiere')->get()->map(fn($n) => [
            'id'           => $n->id,
            'eleve_id'     => $n->eleve_id,
            'matiere_id'   => $n->matiere_id,
            'note'         => (float) $n->note,
            'appreciation' => null, // pas de colonne dédiée en base
            'periode'      => (string) ($n->trimestre ?? ''),
            'matiere'      => $n->matiere ? [
                'id'   => $n->matiere->id,
                'code' => $n->matiere->code,
                'nom'  => $n->matiere->nom,
            ] : null,
        ]);

        return response()->json([
            'status' => true,
            'notes'  => $notes,
        ], 200);
    }

    /**
     * GET /api/eleves/{id}/paiements
     * Réponse : { status, paiements: [PaiementModel] }
     * Mapping versements -> paiements :
     *   date_versement -> date_paiement, mode_paiement -> type_paiement, reference_recu -> recu
     */
    public function paiements(Request $request, $id)
    {
        // 1. Récupération sécurisée de l'élève
        $eleve = $this->eleveDuParent($request, $id);
        if (!$eleve) {
            return $this->interdit();
        }

        // 2. Récupération de la liste des versements (paiements) effectuée
        $versements = $eleve->versements()->orderByDesc('date_versement')->get();

        // 3. Calculs dynamiques basés sur la base de données
        $totalPaye = (float) $versements->sum('montant');
        
        // On récupère le montant de la scolarité définie sur la classe de l'élève
        // (Assure-toi que la table/modèle Classe possède bien une colonne 'frais_scolarite')
        $fraisClasse = $eleve->classe ? (float) $eleve->classe->frais_scolarite : 0.0;
        
        $resteAPayer = $fraisClasse - $totalPaye;

        // 4. Formatage de la liste pour l'application Kotlin
        $paiementsMappes = $versements->map(fn($v) => [
            'id'            => $v->id,
            'eleve_id'      => $v->eleve_id,
            'montant'       => (float) $v->montant,
            'date_paiement' => $v->date_versement ? \Illuminate\Support\Carbon::parse($v->date_versement)->format('Y-m-d') : '',
            'type_paiement' => (string) ($v->mode_paiement ?? ''),
            'recu'          => $v->reference_recu,
        ]);

        // 5. Envoi de la réponse complète attendue par l'app Mobile
        return response()->json([
            'status'      => true,
            'totalPaye'   => $totalPaye,   // Sera automatiquement associé à val totalPaye dans Kotlin
            'resteAPayer' => $resteAPayer, // Sera automatiquement associé à val resteAPayer dans Kotlin
            'paiements'   => $paiementsMappes,
        ], 200);
    }

    /**
     * GET /api/eleves/{id}/absences
     * Réponse : { status, absences: [AbsenceModel] }
     * Mapping : absences.justifiee (bool) -> justifie (int 0/1)
     */
    public function absences(Request $request, $id)
    {
        $eleve = $this->eleveDuParent($request, $id);
        if (!$eleve) {
            return $this->interdit();
        }

        $absences = $eleve->absences()->get()->map(fn($a) => [
            'id'           => $a->id,
            'eleve_id'     => $a->eleve_id,
            'date_absence' => $a->date_absence ? \Illuminate\Support\Carbon::parse($a->date_absence)->format('Y-m-d') : '',
            'motif'        => $a->motif,
            'justifie'     => (int) $a->justifiee,
        ]);

        return response()->json([
            'status'   => true,
            'absences' => $absences,
        ], 200);
    }

    /**
     * GET /api/notifications
     * Réponse : { status, notifications: [NotificationModel] }
     * Mapping : notifications.message -> contenu
     */
    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->orderByDesc('created_at')->get()->map(fn($n) => [
                'id'         => $n->id,
                'titre'      => $n->titre ?? 'Notification',
                'contenu'    => $n->message ?? '',
                'lu_le'      => $n->lu_le,
                'created_at' => (string) $n->created_at,
            ]);

        return response()->json([
            'status'        => true,
            'notifications' => $notifications,
        ], 200);
    }

    /**
     * PUT /api/notifications/{id}/lire
     * Marque une notification comme lue.
     */
    public function marquerNotificationLue(Request $request, $id)
    {
        $notif = $request->user()->notifications()->where('id', $id)->first();

        if ($notif) {
            $notif->update([
                'lu'    => true,
                'lu_le' => now(),
            ]);
        }

        return response()->json([
            'status' => true,
        ], 200);
    }
}
