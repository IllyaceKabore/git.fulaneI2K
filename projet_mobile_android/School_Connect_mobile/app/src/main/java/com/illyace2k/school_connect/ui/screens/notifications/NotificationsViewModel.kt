package com.illyace2k.school_connect.ui.screens.notifications

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.NotificationModel
import com.illyace2k.school_connect.data.remote.RetrofitClient
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

// 1. Définir l'état de l'écran pour corriger les erreurs 'isLoading', 'error' et 'notifications'
data class NotificationsState(
    val isLoading: Boolean = true,
    val notifications: List<NotificationModel> = emptyList(),
    val error: String? = null,
    val currentEleveId: Int = 0
)

class NotificationsViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(NotificationsState())
    val uiState: StateFlow<NotificationsState> = _uiState.asStateFlow()

    // 2. Corriger l'erreur 'Unresolved reference loadNotifications'
    fun loadNotifications() {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            try {
                val response = RetrofitClient.apiService.getAnnonces()

                if (response.isSuccessful) {
                    val listAnnonces = response.body() ?: emptyList()
                    _uiState.value = _uiState.value.copy(
                        notifications = listAnnonces,
                        isLoading = false
                    )
                } else {
                    _uiState.value = _uiState.value.copy(
                        error = "Erreur serveur : ${response.code()}",
                        isLoading = false
                    ) }
                } catch (e: Exception) {
                _uiState.value = _uiState.value.copy(
                    error = "Erreur de connexion : ${e.message}",
                    isLoading = false
                )
            }
        }
    }

    // 3. Corriger l'erreur 'Unresolved reference marquerCommeLue'
    fun marquerCommeLue(id: String) {
        viewModelScope.launch {
            // Logique pour dire à Laravel que le message est lu
        }
    }
}