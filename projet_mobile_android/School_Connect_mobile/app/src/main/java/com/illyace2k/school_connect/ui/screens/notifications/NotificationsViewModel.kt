package com.illyace2k.school_connect.ui.screens.notifications

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.NotificationModel
import com.illyace2k.school_connect.data.remote.ApiManager
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class NotificationsUiState(
    val isLoading: Boolean = false,
    val notifications: List<NotificationModel> = emptyList(),
    val error: String? = null
)

class NotificationsViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(NotificationsUiState())
    val uiState = _uiState.asStateFlow()

    fun loadNotifications() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            try {
                val response = ApiManager.service.getNotifications()
                if (response.isSuccessful && response.body()?.status == true) {
                    val list = response.body()?.notifications ?: emptyList()
                    _uiState.update { it.copy(isLoading = false, notifications = list) }
                } else {
                    _uiState.update { it.copy(isLoading = false, error = "Impossible de récupérer les notifications.") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur réseau : ${e.localizedMessage}") }
            }
        }
    }

    fun marquerCommeLue(notificationId: String) {
        viewModelScope.launch {
            try {
                val response = ApiManager.service.marquerNotificationLue(notificationId)
                if (response.isSuccessful) {
                    // On rafraîchit localement la liste pour mettre à jour l'indicateur visuel à l'écran
                    loadNotifications()
                }
            } catch (e: Exception) {
                // Optionnel : Gérer l'erreur silencieusement ou notifier l'UI
            }
        }
    }
}