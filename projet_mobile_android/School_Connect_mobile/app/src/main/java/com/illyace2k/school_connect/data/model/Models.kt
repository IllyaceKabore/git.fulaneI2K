package com.illyace2k.school_connect.data.model

import com.google.gson.annotations.SerializedName

// --- 1. AUTHENTIFICATION ---

data class LoginRequest(
    val email: String,
    val password: String
)

data class LoginResponse(
    val status: Boolean,
    val message: String,
    val token: String,
    val parent: ParentModel?,
    val enfants: List<EleveModel>
)

data class ParentModel(
    val id: Int,
    val nom: String,
    val prenom: String,
    val email: String,
    val telephone: String,
    val photo: String?
)

// --- 2. ÉLÈVES ET DÉTAILS ---

data class ElevesResponse(
    val status: Boolean,
    val eleves: List<EleveModel>
)

data class EleveDetailResponse(
    val status: Boolean,
    val eleve: EleveModel
)

data class EleveModel(
    val id: Int,
    val matricule: String?,
    val nom: String,
    val prenom: String,
    @SerializedName("classe_id") val classeId: Int?,
    @SerializedName("date_naissance") val dateNaissance: String?,
    val sexe: String?,
    @SerializedName("nom_tuteur") val nomTuteur: String?,
    @SerializedName("telephone_tuteur") val telephoneTuteur: String?,
    @SerializedName("date_inscription") val dateInscription: String?,
    val classe: ClasseModel? = null


)

data class ClasseModel(
    val id: Int,
    val libelle: String,
    val slug: String?
)

// --- 3. NOTES / BULLETINS ---

data class NotesResponse(
    val status: Boolean,
    val notes: List<NoteModel>
)

data class NoteModel(
    val id: Int,
    @SerializedName("eleve_id") val eleveId: Int,
    @SerializedName("matiere_id") val matiereId: Int,
    val note: Double,
    val appreciation: String?,
    val periode: String,
    val matiere: MatiereModel
)

data class MatiereModel(
    val id: Int,
    val code: String?,
    val nom: String
)

// --- 4. PAIEMENTS ---

data class PaiementsResponse(
    val status: Boolean,
    @SerializedName("totalPaye")
    val totalPaye: Double, // Récupérera dynamiquement la somme des versements (ex: 15000.0)

    @SerializedName("resteAPayer")
    val resteAPayer: Double, // Récupérera le calcul (frais_scolarite - totalPaye)
    val paiements: List<PaiementModel> = emptyList()
)

data class PaiementModel(
    val id: Int,
    @SerializedName("eleve_id") val eleveId: Int,
    val montant: Double,
    @SerializedName("date_paiement") val datePaiement: String,
    @SerializedName("type_paiement") val typePaiement: String,
    val recu: String?
)

// --- 5. ABSENCES ---

data class AbsencesResponse(
    val status: Boolean,
    val absences: List<AbsenceModel>
)

data class AbsenceModel(
    val id: Int,
    @SerializedName("eleve_id") val eleveId: Int,
    @SerializedName("date_absence") val dateAbsence: String,
    val motif: String?,
    // 🟢 Sécurisé : Reçoit l'entier (0 ou 1) de Laravel pour éviter le crash de conversion Boolean
    val justifie: Int = 0
)

// --- 6. NOTIFICATIONS ---

data class NotificationsResponse(
    val status: Boolean,
    val notifications: List<NotificationModel>
)

data class NotificationModel(
    val id: Int,
    val titre: String?,
    @SerializedName("message")
    val contenu: String?,
    @SerializedName("categorie")
    val categorie: String?,
    @SerializedName("date_prevue")
    val datePrevue: String?,
    @SerializedName("lu_le")
    val luLe: String?,
    @SerializedName("created_at")
    val createdAt: String?
)