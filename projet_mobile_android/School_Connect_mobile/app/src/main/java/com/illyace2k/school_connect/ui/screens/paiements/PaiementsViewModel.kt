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
    val totalPaye: Double = 0.0,
    val resteAPayer: Double = 0.0,
    val error: String? = null,
    val currentEleveId: Int = 0
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
                    val responseBody = response.body()!!

                    val list = responseBody.paiements ?: emptyList()
                    // ✅ On récupère dynamiquement les montants calculés par Laravel
                    val total = responseBody.totalPaye
                    val reste = responseBody.resteAPayer

                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            paiements = list,
                            totalPaye = total,     // ✅ Injecté dans le State
                            resteAPayer = reste    // ✅ Injecté dans le State
                        )
                    }
                } else {
                    _uiState.update { it.copy(isLoading = false, error = "Impossible de récupérer l'historique des paiements.") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur réseau : ${e.localizedMessage}") }
            }
        }
    }
}