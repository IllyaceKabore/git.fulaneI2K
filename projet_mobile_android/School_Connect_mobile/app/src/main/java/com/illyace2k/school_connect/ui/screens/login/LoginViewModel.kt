package com.illyace2k.school_connect.ui.screens.login

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.illyace2k.school_connect.data.local.TokenDataStore
import com.illyace2k.school_connect.data.remote.ApiManager
import com.illyace2k.school_connect.data.repository.AuthRepository
import com.illyace2k.school_connect.data.repository.Resource
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.firstOrNull
import kotlinx.coroutines.launch

// 🟢 CORRECTION : eleveId fait maintenant partie des propriétés modifiables du constructeur
data class LoginUiState(
    val isLoading: Boolean = false,
    val error: String? = null,
    val success: Boolean = false,
    val eleveId: Int? = null
)

class LoginViewModel(private val dataStore: TokenDataStore) : ViewModel() {
    private val repo = AuthRepository(dataStore)

    private val _uiState = MutableStateFlow(LoginUiState())
    val uiState = _uiState.asStateFlow()

    fun login(email: String, password: String) {
        viewModelScope.launch {
            _uiState.value = LoginUiState(isLoading = true)

            when (val r = repo.login(email.trim(), password)) {
                is Resource.Success -> {
                    // 1. 🟢 CRUCIAL : Récupérer le token tout juste sauvegardé par ton Repo dans le DataStore
                    val tokenSauvegarde = dataStore.token.firstOrNull()

                    // 2. Initialiser l'ApiManager pour que les futures requêtes soient authentifiées
                    ApiManager.initialize(tokenSauvegarde)

                    // 3. Récupérer la liste des élèves pour avoir un ID valide pour le Dashboard
//                    try {
//                        val elevesResponse = ApiManager.service.getEleves()
//                        if (elevesResponse.isSuccessful && elevesResponse.body()?.status == true) {
//                            val premierEleve = elevesResponse.body()?.eleves?.firstOrNull()
//
//                            if (premierEleve != null) {
//                                // Sauvegarder l'ID de l'élève pour le prochain démarrage
//                                dataStore.saveLastEleveId(premierEleve.id)
//
//                                // Déclencher le succès avec le bon ID !
//                                _uiState.value = LoginUiState(
//                                    success = true,
//                                    eleveId = premierEleve.id
//                                )
//                            } else {
//                                _uiState.value = LoginUiState(error = "Aucun élève associé à ce compte parent.")
//                            }
//                        } else {
//                            _uiState.value = LoginUiState(error = "Erreur lors de la récupération de l'élève.")
//                        }
//                    } catch (e: Exception) {
//                        _uiState.value = LoginUiState(error = "Erreur réseau après connexion : ${e.localizedMessage}")
//                    }

                    try {
                        val elevesResponse = ApiManager.service.getEleves()

                        println("CODE = ${elevesResponse.code()}")
                        println("BODY = ${elevesResponse.body()}")
                        println("ERROR = ${elevesResponse.errorBody()?.string()}")

                        _uiState.value = LoginUiState(
                            success = true,
                            eleveId = 1
                        )

                    } catch (e: Exception) {
                        e.printStackTrace()

                        _uiState.value = LoginUiState(
                            error = e.toString()
                        )
                    }
                }
                is Resource.Error -> {
                    _uiState.value = LoginUiState(error = r.message)
                }
                else -> Unit
            }
        }
    }
}

class LoginViewModelFactory(
    private val dataStore: TokenDataStore
) : ViewModelProvider.Factory {
    override fun <T : ViewModel> create(modelClass: Class<T>): T {
        @Suppress("UNCHECKED_CAST")
        return LoginViewModel(dataStore) as T
    }
}