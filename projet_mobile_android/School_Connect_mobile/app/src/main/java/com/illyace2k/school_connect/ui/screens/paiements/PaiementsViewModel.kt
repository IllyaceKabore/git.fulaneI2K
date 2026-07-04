package com.illyace2k.school_connect.ui.screens.paiements

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.PaiementModel
import com.illyace2k.school_connect.data.remote.ApiManager
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class PaiementsUiState(
    val isLoading: Boolean = false,
    val paiements: List<PaiementModel> = emptyList(),
    val error: String? = null
)

class PaiementsViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(PaiementsUiState())
    val uiState = _uiState.asStateFlow()

    fun loadPaiements(eleveId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            try {
                val response = ApiManager.service.getPaiements(eleveId)
                if (response.isSuccessful && response.body()?.status == true) {
                    val list = response.body()?.paiements ?: emptyList()
                    _uiState.update { it.copy(isLoading = false, paiements = list) }
                } else {
                    _uiState.update { it.copy(isLoading = false, error = "Impossible de récupérer l'historique des paiements.") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur réseau : ${e.localizedMessage}") }
            }
        }
    }
}