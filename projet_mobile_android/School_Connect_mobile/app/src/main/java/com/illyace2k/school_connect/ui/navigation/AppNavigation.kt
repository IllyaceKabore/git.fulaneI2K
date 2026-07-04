package com.illyace2k.school_connect.ui.navigation

import androidx.compose.runtime.Composable
import androidx.navigation.NavHostController
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.navArgument
import com.illyace2k.school_connect.ui.screens.absences.AbsencesScreen
import com.illyace2k.school_connect.ui.screens.dashboard.DashboardScreen
import com.illyace2k.school_connect.ui.screens.login.LoginScreen
import com.illyace2k.school_connect.ui.screens.notes.NotesScreen
import com.illyace2k.school_connect.ui.screens.notifications.NotificationsScreen
import com.illyace2k.school_connect.ui.screens.paiements.PaiementsScreen

sealed class Screen(val route: String) {
    object Login         : Screen("login")
    object Dashboard     : Screen("dashboard/{eleveId}") {
        fun build(id: Int) = "dashboard/$id"
    }
    object Notes         : Screen("notes/{eleveId}") {
        fun build(id: Int) = "notes/$id"
    }
    object Paiements     : Screen("paiements/{eleveId}") {
        fun build(id: Int) = "paiements/$id"
    }
    object Absences      : Screen("absences/{eleveId}") {
        fun build(id: Int) = "absences/$id"
    }
    object Notifications : Screen("notifications")
}

@Composable
fun AppNavigation(navController: NavHostController, startDestination: String) {
    NavHost(navController = navController, startDestination = startDestination) {

        // 1. ÉCRAN LOGIN
        composable(Screen.Login.route) {
            LoginScreen(onLoginSuccess = { eleveId ->
                navController.navigate(Screen.Dashboard.build(eleveId)) {
                    popUpTo(Screen.Login.route) { inclusive = true }
                }
            })
        }

        // 2. ÉCRAN DASHBOARD
        composable(
            route = Screen.Dashboard.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType }) // 🟢 Sécurisation du type de l'argument
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0

            DashboardScreen(
                eleveId              = eleveId,
                onNavigateToNotes    = { navController.navigate(Screen.Notes.build(eleveId)) },
                onNavigateToPaiements= { navController.navigate(Screen.Paiements.build(eleveId)) },
                onNavigateToAbsences = { navController.navigate(Screen.Absences.build(eleveId)) }
                // 💡 Si ton DashboardScreen possède un bouton déconnexion ou notification,
                // tu pourras décommenter et ajouter ces paramètres :
                // onNavigateToNotifications = { navController.navigate(Screen.Notifications.route) },
                // onLogout = {
                //     navController.navigate(Screen.Login.route) {
                //         popUpTo(0) { inclusive = true }
                //     }
                // }
            )
        }

        // 3. ÉCRAN NOTES
        composable(
            route = Screen.Notes.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0
            NotesScreen(eleveId = eleveId, onBack = { navController.popBackStack() })
        }

        // 4. ÉCRAN PAIEMENTS
        composable(
            route = Screen.Paiements.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0
            PaiementsScreen(eleveId = eleveId, onBack = { navController.popBackStack() })
        }

        // 5. ÉCRAN ABSENCES
        composable(
            route = Screen.Absences.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0
            AbsencesScreen(eleveId = eleveId, onBack = { navController.popBackStack() })
        }

        // 6. ÉCRAN NOTIFICATIONS
        composable(Screen.Notifications.route) {
            NotificationsScreen(onBack = { navController.popBackStack() })
        }
    }
}