package com.illyace2k.school_connect.ui.screens.dashboard

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.NoteModel
import com.illyace2k.school_connect.data.remote.ApiManager
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class DashboardUiState(
    val isLoading: Boolean = false,
    val dernieresNotes: List<NoteModel> = emptyList(),
    val moyenneGenerale: Double = 0.0,
    val rang: String = "N/A",
    val error: String? = null
)

class DashboardViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(DashboardUiState())
    val uiState = _uiState.asStateFlow()

    fun loadDashboardData(eleveId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            try {
                // Récupération des notes de l'élève
                val response = ApiManager.service.getNotes(eleveId)

                if (response.isSuccessful && response.body()?.status == true) {
                    val notes = response.body()?.notes ?: emptyList()

                    // Calcul de la moyenne générale dynamiquement
                    val moyenne = if (notes.isNotEmpty()) notes.map { it.note }.average() else 0.0

                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            dernieresNotes = notes.take(5), // On prend les 5 dernières pour le résumé du Dashboard
                            moyenneGenerale = moyenne,
                            rang = "1er" // Tu pourras dynamiser le rang plus tard selon ton API Laravel
                        )
                    }
                } else {
                    _uiState.update { it.copy(isLoading = false, error = "Erreur lors de la récupération des données.") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur réseau : ${e.localizedMessage}") }
            }
        }
    }
}