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
import com.illyace2k.school_connect.ui.screens.PasswordRecoveryScreen

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
    object Notifications : Screen("notifications/{eleveId}") {
        fun build(id: Int) = "notifications/$id"
    }
    object PasswordRecovery : Screen("password_recovery")
}

@Composable
fun AppNavigation(navController: NavHostController, startDestination: String) {
    NavHost(navController = navController, startDestination = startDestination) {

        // 1. ÉCRAN LOGIN
        composable(Screen.Login.route) {
            LoginScreen(
                onLoginSuccess = { eleveId ->
                    navController.navigate(Screen.Dashboard.build(eleveId)) {
                        popUpTo(Screen.Login.route) { inclusive = true }
                    }
                },
                // ✅ ICI : On passe la fonction de navigation
                onNavigateToPasswordRecovery = {
                    navController.navigate(Screen.PasswordRecovery.route)
                }
            )
        }

        // 2. ÉCRAN DASHBOARD
        composable(
            route = Screen.Dashboard.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val initialEleveId = back.arguments?.getInt("eleveId") ?: 0

            DashboardScreen(
                eleveId               = initialEleveId,
                // ✅ Type explicite (Int) ajouté pour s'assurer de la parfaite correspondance avec DashboardScreen.kt
                onNavigateToDashboard = { id -> navController.navigate(Screen.Dashboard.build(id)) },
                onNavigateToNotes     = { dynamicId: Int -> navController.navigate(Screen.Notes.build(dynamicId)) },
                onNavigateToPaiements = { dynamicId: Int -> navController.navigate(Screen.Paiements.build(dynamicId)) },
                onNavigateToAbsences  = { dynamicId: Int -> navController.navigate(Screen.Absences.build(dynamicId)) },
                onNavigateToNotifications = { dynamicId: Int -> navController.navigate(Screen.Notifications.build(dynamicId)) },
                onEnfantChange        = { newId ->
                    // ✅ Remplace le dashboard actuel par le nouveau dans la pile de navigation
                    navController.navigate(Screen.Dashboard.build(newId)) {
                        popUpTo(Screen.Dashboard.route) { inclusive = true }
                    }
                }
            )
        }

        // 3. ÉCRAN NOTES
        composable(
            route = Screen.Notes.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0
            NotesScreen(eleveId = eleveId, onBack = { navController.popBackStack() },
                onNavigateToAbsences  = { id -> navController.navigate(Screen.Absences.build(id)) },
                onNavigateToNotes = { id -> navController.navigate(Screen.Notes.build(id)) },
                onNavigateToPaiements = { id -> navController.navigate(Screen.Paiements.build(id)) },
                onNavigateToNotifications = { id -> navController.navigate(Screen.Notifications.build(id)) },
                onNavigateToDashboard = { id -> navController.navigate(Screen.Dashboard.build(id)) }
            )
        }

        // 4. ÉCRAN PAIEMENTS
        composable(
            route = Screen.Paiements.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0
            PaiementsScreen(eleveId = eleveId, onBack = { navController.popBackStack() },
                onNavigateToAbsences  = { id -> navController.navigate(Screen.Absences.build(id)) },
                onNavigateToPaiements = { id -> navController.navigate(Screen.Paiements.build(id)) },
                onNavigateToNotes = { id -> navController.navigate(Screen.Notes.build(id)) },
                onNavigateToNotifications = { id -> navController.navigate(Screen.Notifications.build(id)) },
                onNavigateToDashboard = { id -> navController.navigate(Screen.Dashboard.build(id)) }
            )
        }

        // 5. ÉCRAN ABSENCES
        composable(
            route = Screen.Absences.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0
            AbsencesScreen(eleveId = eleveId, onBack = { navController.popBackStack() },
                onNavigateToAbsences  = { id -> navController.navigate(Screen.Absences.build(id)) },
                onNavigateToNotes = { id -> navController.navigate(Screen.Notes.build(id)) },
                onNavigateToPaiements = { id -> navController.navigate(Screen.Paiements.build(id)) },
                onNavigateToNotifications = { id -> navController.navigate(Screen.Notifications.build(id)) },
                onNavigateToDashboard = { id -> navController.navigate(Screen.Dashboard.build(id)) }
            )
        }

        // 6. ÉCRAN NOTIFICATIONS
        composable(
            route = Screen.Notifications.route,
            arguments = listOf(navArgument("eleveId") { type = NavType.IntType })
        ) { back ->
            val eleveId = back.arguments?.getInt("eleveId") ?: 0

            NotificationsScreen(
                eleveId = eleveId, // <-- C'était l'élément manquant
                onBack = { navController.popBackStack() },
                onNavigateToAbsences = { id -> navController.navigate(Screen.Absences.build(id)) },
                onNavigateToPaiements = { id -> navController.navigate(Screen.Paiements.build(id)) },
                onNavigateToNotes = { id -> navController.navigate(Screen.Notes.build(id)) },
                onNavigateToNotifications = { id -> navController.navigate(Screen.Notifications.build(id)) },
                onNavigateToDashboard = { id -> navController.navigate(Screen.Dashboard.build(id)) }
            )
        }

        composable("password_recovery") {
            PasswordRecoveryScreen(
                onBack = { navController.popBackStack() },
                onResetRequested = { email ->
                    println("Bouton cliqué avec l'email : $email")
                }
            )
        }
    }
}