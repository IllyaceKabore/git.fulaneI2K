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

// Modèle temporaire pour mapper les élèves reçus de l'API s'il y en a plusieurs
data class EleveInfo(
    val id: Int,
    val nom: String,
    val prenom: String,
    val classe: String? = null,
    val enseignant: String? = null
)

data class LoginUiState(
    val isLoading: Boolean = false,
    val error: String? = null,
    val success: Boolean = false,
    val eleveId: Int? = null,
    val hasMultipleChildren: Boolean = false, // 🟢 Indique s'il y a plusieurs enfants
    val listeEnfants: List<EleveInfo> = emptyList() // 🟢 Contient la liste des enfants pour l'affichage
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
                    // 1. Récupérer le token tout juste sauvegardé par le Repo dans le DataStore
                    val tokenSauvegarde = dataStore.token.firstOrNull()

                    // 2. Initialiser l'ApiManager pour que les futures requêtes soient authentifiées
                    ApiManager.initialize(tokenSauvegarde)

                    // 3. Récupérer la liste complète des élèves rattachés
                    try {
                        val elevesResponse = ApiManager.service.getEleves()

                        if (elevesResponse.isSuccessful && elevesResponse.body()?.status == true) {
                            val listeDesEleves = elevesResponse.body()?.eleves ?: emptyList()

                            when {
                                listeDesEleves.isEmpty() -> {
                                    _uiState.value = LoginUiState(error = "Aucun élève associé à ce compte parent.")
                                }
                                listeDesEleves.size == 1 -> {
                                    // 🟢 Cas 1 : Un seul enfant rattaché -> Connexion directe
                                    val uniqueEleve = listeDesEleves.first()
                                    dataStore.saveLastEleveId(uniqueEleve.id)

                                    // Extraction dynamique des informations de la classe et de l'enseignant
                                    val nomClasse = uniqueEleve.classe?.libelle ?: "Classe inconnue"
                                    val nomEnseignant = "Non assigné"

                                    _uiState.value = LoginUiState(
                                        success = true,
                                        eleveId = uniqueEleve.id,
                                        // On le met aussi dans la liste pour que le Dashboard puisse retrouver ses infos au premier lancement
                                        listeEnfants = listOf(
                                            EleveInfo(
                                                id = uniqueEleve.id,
                                                nom = uniqueEleve.nom,
                                                prenom = uniqueEleve.prenom,
                                                classe = nomClasse,
                                                enseignant = nomEnseignant
                                            )
                                        )
                                    )
                                }
                                else -> {
                                    // 🟢 Cas 2 : Plusieurs enfants -> On mappe dynamiquement toutes leurs propriétés vers l'UI
                                    val enfantsMappes = listeDesEleves.map { eleve ->
                                        val nomClasse = eleve.classe?.libelle ?: "Classe inconnue"

                                        EleveInfo(
                                            id = eleve.id,
                                            nom = eleve.nom,
                                            prenom = eleve.prenom,
                                            classe = nomClasse,       // 🟢 Transmis dynamiquement
                                        )
                                    }
                                    _uiState.value = LoginUiState(
                                        isLoading = false,
                                        hasMultipleChildren = true,
                                        listeEnfants = enfantsMappes
                                    )
                                }
                            }
                        } else {
                            _uiState.value = LoginUiState(error = "Erreur lors de la récupération des données de l'élève.")
                        }
                    } catch (e: Exception) {
                        e.printStackTrace()
                        _uiState.value = LoginUiState(error = "Erreur réseau après connexion : ${e.localizedMessage}")
                    }
                }
                is Resource.Error -> {
                    _uiState.value = LoginUiState(error = r.message)
                }
                else -> Unit
            }
        }
    }

    /**
     * 🟢 Méthode appelée par l'interface Jetpack Compose une fois que le parent
     * clique sur l'un de ses enfants dans la liste.
     */
    fun selectionnerEnfant(enfantId: Int) {
        viewModelScope.launch {
            dataStore.saveLastEleveId(enfantId)
            _uiState.value = _uiState.value.copy(
                success = true,
                eleveId = enfantId,
                hasMultipleChildren = false
            )
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