package com.illyace2k.school_connect.ui.screens.notes

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.model.NoteModel
import com.illyace2k.school_connect.data.remote.ApiManager
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class NotesUiState(
    val isLoading: Boolean = false,
    // On groupe les notes par période (ex: "Trimestre 1" -> Liste de notes)
    val notesParPeriode: Map<String, List<NoteModel>> = emptyMap(),
    val error: String? = null,
    val currentEleveId: Int = 0
)

class NotesViewModel : ViewModel() {

    private val _uiState = MutableStateFlow(NotesUiState())
    val uiState = _uiState.asStateFlow()

    fun loadNotes(eleveId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            try {
                val response = ApiManager.service.getNotes(eleveId)

                if (response.isSuccessful && response.body()?.status == true) {
                    val toutesLesNotes = response.body()?.notes ?: emptyList()

                    // On regroupe les notes par leur propriété "periode"
                    val groupe = toutesLesNotes.groupBy { it.periode }

                    _uiState.update { it.copy(isLoading = false, notesParPeriode = groupe) }
                } else {
                    _uiState.update { it.copy(isLoading = false, error = "Impossible de charger les notes.") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, error = "Erreur réseau : ${e.localizedMessage}") }
            }
        }
    }
}