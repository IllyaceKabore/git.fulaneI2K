package com.illyace2k.school_connect.ui.screens.dashboard

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.NoteModel
import com.illyace2k.school_connect.data.remote.ApiManager
import com.illyace2k.school_connect.ui.screens.login.EleveInfo
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class DashboardUiState(
    val isLoading: Boolean = false,
    val dernieresNotes: List<NoteModel> = emptyList(),
    val moyenneGenerale: Double = 0.0,
    val rang: String = "",
    val error: String? = null,
    val currentEleveId: Int = 0,
    val listeEnfants: List<EleveInfo> = emptyList(),
    val currentEleveNom: String = "Nom inconnu",
    val currentEleveClasse: String = "Classe inconnue",
    val currentElevePhoto: String? = null
)

class DashboardViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(DashboardUiState())
    val uiState = _uiState.asStateFlow()

    fun loadDashboardData(eleveId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            try {
                var enfantsMappes = _uiState.value.listeEnfants

                // Si la liste est vide, on récupère les données de l'API
                if (enfantsMappes.isEmpty()) {
                    val elevesResponse = ApiManager.service.getEleves()
                    if (elevesResponse.isSuccessful && elevesResponse.body()?.status == true) {
                        enfantsMappes = elevesResponse.body()?.eleves?.map { eleve ->
                            EleveInfo(
                                id = eleve.id,
                                nom = eleve.nom,
                                prenom = eleve.prenom,
                                classe = eleve.classe?.libelle ?: "Classe inconnue"
                            )
                        } ?: emptyList()
                    }
                }

                // 1. On trouve l'élève actif dans la liste récupérée
                val enfantActuel = enfantsMappes.find { it.id == eleveId }

                // 2. Préparation des variables sécurisées
                val nomComplet = enfantActuel?.let { "${it.nom} ${it.prenom}" } ?: "Nom inconnu"
                val classeApparente = enfantActuel?.classe ?: "Classe inconnue"

                // 3. Récupération des notes
                val response = ApiManager.service.getNotes(eleveId)

                if (response.isSuccessful && response.body()?.status == true) {
                    val notes = response.body()?.notes ?: emptyList()
                    val moyenne = if (notes.isNotEmpty()) notes.map { it.note }.average() else 0.0

                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            dernieresNotes = notes.take(5),
                            moyenneGenerale = moyenne,
                            rang = "1er",
                            listeEnfants = enfantsMappes,
                            currentEleveId = eleveId,
                            currentEleveNom = nomComplet,
                            currentEleveClasse = classeApparente
                        )
                    }
                } else {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = "Erreur lors de la récupération des données.",
                            listeEnfants = enfantsMappes,
                            currentEleveId = eleveId,
                            currentEleveNom = nomComplet,
                            currentEleveClasse = classeApparente
                        )
                    }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur : ${e.localizedMessage}") }
            }
        }
    }
}