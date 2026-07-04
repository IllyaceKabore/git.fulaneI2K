package com.illyace2k.school_connect.data.local

import android.content.Context
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.intPreferencesKey
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.map

val Context.dataStore by preferencesDataStore(name = "school_connect_prefs")

class TokenDataStore(private val context: Context) {

    companion object {
        private val TOKEN_KEY    = stringPreferencesKey("auth_token")
        private val ELEVE_ID_KEY = intPreferencesKey("last_eleve_id")
    }

    val token: Flow<String?> = context.dataStore.data
        .map { prefs -> prefs[TOKEN_KEY] }

    val lastEleveId: Flow<Int?> = context.dataStore.data
        .map { prefs -> prefs[ELEVE_ID_KEY] }

    suspend fun saveToken(token: String) {
        context.dataStore.edit { prefs -> prefs[TOKEN_KEY] = token }
    }

    suspend fun saveLastEleveId(eleveId: Int) {
        context.dataStore.edit { prefs -> prefs[ELEVE_ID_KEY] = eleveId }
    }

    suspend fun clearToken() {
        context.dataStore.edit { prefs ->
            prefs.remove(TOKEN_KEY)
            prefs.remove(ELEVE_ID_KEY)
        }
    }
}