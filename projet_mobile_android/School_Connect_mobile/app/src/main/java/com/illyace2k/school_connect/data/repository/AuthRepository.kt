package com.illyace2k.school_connect.data.repository

import com.illyace2k.school_connect.data.local.TokenDataStore
import com.illyace2k.school_connect.data.model.LoginRequest
import com.illyace2k.school_connect.data.remote.ApiManager
import com.illyace2k.school_connect.data.remote.RetrofitClient

class AuthRepository(private val dataStore: TokenDataStore) {

    // 🟢 CHANGEMENT : On retourne un Int (l'ID de l'élève) à la place du String (token)
    suspend fun login(email: String, password: String): Resource<Int> {

        // 1. Appel sécurisé du Login
        val api = RetrofitClient.create()
        val result = safeApiCall { api.login(LoginRequest(email, password)) }

        if (result is Resource.Success) {
            val loginResponse = result.data
            val token = loginResponse.token

            // 2. Sauvegarde du jeton d'authentification
            dataStore.saveToken(token)

            // 3. Initialisation de l'ApiManager avec le nouveau token pour l'appel suivant
            ApiManager.initialize(token)

            // 4. Appel immédiat pour récupérer l'élève associé
            val elevesResult = safeApiCall { ApiManager.service.getEleves() }

            if (elevesResult is Resource.Success) {
                val premierEleve = elevesResult.data.eleves.firstOrNull()

                if (premierEleve != null) {
                    // 5. Sauvegarde locale de l'ID de l'élève
                    dataStore.saveLastEleveId(premierEleve.id)

                    // Tout est parfait, on renvoie l'ID à l'UI state !
                    return Resource.Success(premierEleve.id)
                } else {
                    return Resource.Error("Aucun élève associé à ce compte parent.")
                }
            } else if (elevesResult is Resource.Error) {
                return Resource.Error("Erreur élèves : ${elevesResult.message}")
            }
        }

        // Gestion des retours d'erreurs d'authentification initiaux
        return when (result) {
            is Resource.Error   -> Resource.Error(result.message)
            else                -> Resource.Error("Une erreur inconnue est survenue.")
        }
    }

    suspend fun logout(token: String) {
        safeApiCall { RetrofitClient.create(token).logout() }
        dataStore.clearToken()
    }
}