package com.illyace2k.school_connect.data.repository

import retrofit2.Response

sealed class Resource<T> {
    data class Success<T>(val data: T) : Resource<T>()
    data class Error<T>(val message: String) : Resource<T>()
    class Loading<T> : Resource<T>()
}

suspend fun <T> safeApiCall(call: suspend () -> Response<T>): Resource<T> {
    return try {
        val response = call()
        if (response.isSuccessful && response.body() != null) {
            Resource.Success(response.body()!!)
        } else {
            when (response.code()) {
                401  -> Resource.Error("Session expirée. Veuillez vous reconnecter.")
                422  -> Resource.Error("Identifiants incorrects.")
                else -> Resource.Error("Erreur serveur (${response.code()}).")
            }
        }
    } catch (e: Exception) {
        Resource.Error("Impossible de joindre le serveur. Vérifiez votre connexion.")
    }
}