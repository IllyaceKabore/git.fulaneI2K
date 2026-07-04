package com.illyace2k.school_connect.data.remote

import com.illyace2k.school_connect.data.remote.ApiService

object ApiManager {
    private var currentToken: String? = null
    private var apiService: ApiService? = null

    // Cette fonction sera appelée au démarrage (dans MainActivity) et juste après le login réussi
    fun initialize(token: String?) {
        currentToken = token
        apiService = RetrofitClient.create(token)
    }

    // C'est cette instance que tous tes ViewModels/Écrans appelleront pour faire des requêtes
    val service: ApiService
        get() {
            if (apiService == null) {
                // Au cas où, on initialise un client sans token (pour le login)
                apiService = RetrofitClient.create(null)
            }
            return apiService!!
        }
}