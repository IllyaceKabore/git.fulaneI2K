package com.illyace2k.school_connect

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.navigation.compose.rememberNavController
import com.illyace2k.school_connect.data.local.TokenDataStore
import com.illyace2k.school_connect.ui.navigation.AppNavigation
import com.illyace2k.school_connect.ui.navigation.Screen
import com.illyace2k.school_connect.ui.theme.SchoolConnectTheme
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.runBlocking

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        val dataStore = TokenDataStore(applicationContext)
        val token     = runBlocking { dataStore.token.first() }
        val eleveId   = runBlocking { dataStore.lastEleveId.first() }

        val start = if (token != null && eleveId != null) {
            Screen.Dashboard.build(eleveId)
        } else {
            Screen.Login.route
        }

        setContent {
            SchoolConnectTheme {
                val navController = rememberNavController()
                AppNavigation(
                    navController    = navController,
                    startDestination = start
                )
            }
        }
    }
}