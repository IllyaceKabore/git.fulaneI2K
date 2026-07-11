package com.illyace2k.school_connect.ui.screens.notifications

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.automirrored.filled.List
import androidx.compose.material.icons.filled.DateRange
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Notifications
import androidx.compose.material.icons.filled.Payment
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import com.illyace2k.school_connect.data.model.NotificationModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun NotificationsScreen(
    eleveId: Int,
    onBack: () -> Unit,
    onNavigateToNotes: (Int) -> Unit,
    onNavigateToAbsences: (Int) -> Unit,
    onNavigateToPaiements: (Int) -> Unit,
    onNavigateToNotifications: (Int) -> Unit,
    onNavigateToDashboard: (Int) -> Unit,
    viewModel: NotificationsViewModel = viewModel()
) {
    LaunchedEffect(Unit) {
        viewModel.loadNotifications()
    }

    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val activeId = if (state.currentEleveId != 0) state.currentEleveId else eleveId

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Notifications", fontWeight = FontWeight.Bold) },
                navigationIcon = {
                    IconButton(onClick = onBack) {
                        Icon(imageVector = Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Retour")
                    }
                }
            )
        },
        bottomBar = {
            BottomAppBar(containerColor = MaterialTheme.colorScheme.surfaceVariant) {
                // Composant pour les icônes du bas
                @Composable
                fun NavItem(icon: androidx.compose.ui.graphics.vector.ImageVector, label: String, onClick: () -> Unit) {
                    Column(Modifier.weight(1f).clickable { onClick() }, horizontalAlignment = Alignment.CenterHorizontally) {
                        Icon(icon, label, tint = MaterialTheme.colorScheme.primary)
                        Text(label, fontSize = 10.sp)
                    }
                }
                NavItem(Icons.Default.Home, "Accueil") { onNavigateToDashboard(activeId) }
                NavItem(Icons.AutoMirrored.Filled.List, "Notes") { onNavigateToNotes(activeId) }
                NavItem(Icons.Default.DateRange, "Absence") { onNavigateToAbsences(activeId) }
                NavItem(Icons.Default.Payment, "Paiement") { onNavigateToPaiements(activeId) }
                NavItem(Icons.Default.Notifications, "Notifications") { onNavigateToNotifications(activeId) }
            }
        }
    ) { padding ->
        Box(
            modifier = Modifier
                .fillMaxSize()
                .padding(padding),
            contentAlignment = Alignment.Center
        ) {
            if (state.isLoading) {
                CircularProgressIndicator()
            } else if (state.error != null) {
                Text(text = state.error!!, color = MaterialTheme.colorScheme.error)
            } else if (state.notifications.isEmpty()) {
                Text(
                    text = "Aucune notification reçue.",
                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
                )
            } else {
                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(horizontal = 16.dp),
                    verticalArrangement = Arrangement.spacedBy(10.dp)
                ) {
                    items(state.notifications) { itemNotification ->
                        NotificationItem(
                            notification = itemNotification,
                            onClick = { viewModel.marquerCommeLue(itemNotification.id.toString()) }
                        )
                    }
                }
            }
        }
    }
}

@Composable
fun NotificationItem(
    notification: NotificationModel,
    modifier: Modifier = Modifier,
    onClick: () -> Unit
) {
    // 🟢 Fonction de nettoyage rapide pour la date ISO (ex: "2026-07-02T10:52:52.000000Z")
    val dateAffichee = remember(notification.createdAt) {
        if (!notification.createdAt.isNullOrEmpty() && notification.createdAt.length >= 10) {
            // Extrait simplement "2026-07-02"
            val dateBrute = notification.createdAt.substring(0, 10)
            // Inverse pour l'avoir en format lisible "02/07/2026"
            val parts = dateBrute.split("-")
            if (parts.size == 3) "${parts[2]}/${parts[1]}/${parts[0]}" else dateBrute
        } else {
            "Date inconnue"
        }
    }

    Card(
        modifier = modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp, vertical = 8.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surface
        ),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp),
        shape = RoundedCornerShape(12.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
        ) {
            // Ligne du haut : Badge de Type + Date épurée
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                // Badge du type d'annonce (ex: PAIEMENT, REUNION...)
                Surface(
                    color = MaterialTheme.colorScheme.primaryContainer,
                    shape = RoundedCornerShape(4.dp)
                ) {
                    Text(
                        text = (notification.categorie ?: "ANNONCE").uppercase(),
                        style = MaterialTheme.typography.labelSmall,
                        color = MaterialTheme.colorScheme.onPrimaryContainer,
                        modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                    )
                }

                // Date nettoyée
                Text(
                    text = dateAffichee,
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }

            Spacer(modifier = Modifier.height(8.dp))

            // Titre de l'annonce
            Text(
                text = notification.titre ?: "Sans titre",
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.Bold,
                color = MaterialTheme.colorScheme.onSurface
            )

            Spacer(modifier = Modifier.height(4.dp))

            // Corps du message (Contenu)
            Text(
                text = notification.contenu ?: "",
                style = MaterialTheme.typography.bodyMedium,
                color = MaterialTheme.colorScheme.onSurfaceVariant,
                lineHeight = 20.sp
            )
        }
    }
}