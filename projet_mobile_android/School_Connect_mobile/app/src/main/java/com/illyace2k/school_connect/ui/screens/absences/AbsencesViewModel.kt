package com.illyace2k.school_connect.ui.screens.absences

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.AbsenceModel
import com.illyace2k.school_connect.data.remote.ApiManager
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

// L'état de l'UI attendu par ton AbsencesScreen
data class AbsencesUiState(
    val isLoading: Boolean = false,
    val absences: List<AbsenceModel> = emptyList(),
    val error: String? = null
)

class AbsencesViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(AbsencesUiState())
    val uiState = _uiState.asStateFlow()

    fun getAbsences(eleveId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }

            try {
                // Utilisation de notre ApiManager globalement authentifié
                val response = ApiManager.service.getAbsences(eleveId)

                if (response.isSuccessful && response.body()?.status == true) {
                    val list = response.body()?.absences ?: emptyList()
                    _uiState.update { it.copy(isLoading = false, absences = list) }
                } else {
                    _uiState.update { it.copy(isLoading = false, error = "Impossible de récupérer les absences.") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur réseau : ${e.localizedMessage}") }
            }
        }
    }
}