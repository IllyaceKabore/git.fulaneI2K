package com.illyace2k.school_connect.ui.screens.dashboard

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.List
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.vector.rememberVectorPainter
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import coil.compose.AsyncImage
import com.illyace2k.school_connect.ui.screens.login.EnfantsSelectionDialog

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun DashboardScreen(
    eleveId: Int,
    onNavigateToNotes: (Int) -> Unit,
    onNavigateToAbsences: (Int) -> Unit,
    onNavigateToPaiements: (Int) -> Unit,
    onNavigateToNotifications: (Int) -> Unit,
    onNavigateToDashboard: (Int) -> Unit,
    onEnfantChange: (Int) -> Unit,
    viewModel: DashboardViewModel = viewModel()
) {
    var showChildrenDialog by remember { mutableStateOf(false) }
    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val activeId = if (state.currentEleveId != 0) state.currentEleveId else eleveId

    LaunchedEffect(eleveId) { viewModel.loadDashboardData(eleveId) }

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Tableau de bord", fontWeight = FontWeight.Bold) },
                actions = {
                    IconButton(onClick = { showChildrenDialog = true }) {
                        Icon(Icons.Default.Refresh, "Changer d'enfant")
                    }
                }
            )
        },
        bottomBar = {
            BottomAppBar(containerColor = MaterialTheme.colorScheme.surfaceVariant) {
                // Composant pour les icônes du bas
                @Composable
                fun NavItem(icon: androidx.compose.ui.graphics.vector.ImageVector, label: String, onClick: () -> Unit) {
                    Column(Modifier
                        .weight(1f)
                        .clickable { onClick() }, horizontalAlignment = Alignment.CenterHorizontally) {
                        Icon(icon, label, tint = MaterialTheme.colorScheme.primary)
                        Text(label, fontSize = 10.sp)
                    }
                }
                NavItem(Icons.Default.Home, "Accueil") {
                    // On navigue vers le tableau de bord avec l'ID actuel
                    onNavigateToDashboard(activeId)
                }
                NavItem(Icons.AutoMirrored.Filled.List, "Notes") { onNavigateToNotes(activeId) }
                NavItem(Icons.Default.DateRange, "Absence") { onNavigateToAbsences(activeId) }
                NavItem(Icons.Default.Payment, "Paiement") { onNavigateToPaiements(activeId) }
                NavItem(Icons.Default.Notifications, "Notifications") { onNavigateToNotifications(activeId) }
            }
        }
    ) { padding ->
        if (state.isLoading) {
            Box(Modifier
                .fillMaxSize()
                .padding(padding), contentAlignment = Alignment.Center) { CircularProgressIndicator() }
        } else {
            LazyColumn(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(padding)
                    .padding(horizontal = 16.dp),
                verticalArrangement = Arrangement.spacedBy(16.dp),
                contentPadding = PaddingValues(bottom = 24.dp)
            ) {
                // 1. Vue d'ensemble
                item {
                    Card(modifier = Modifier.fillMaxWidth(), colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.primaryContainer)) {
                        Row(Modifier
                            .fillMaxWidth()
                            .padding(20.dp), horizontalArrangement = Arrangement.SpaceBetween) {
                            Column { Text("Moyenne", fontSize = 14.sp); Text("%.2f/10".format(state.moyenneGenerale), fontSize = 28.sp, fontWeight = FontWeight.Bold) }
                            Column(horizontalAlignment = Alignment.End) { Text("Rang", fontSize = 14.sp); Text(state.rang, fontSize = 28.sp, fontWeight = FontWeight.Bold) }
                        }
                    }
                }

                // 2. Profil
                item {
                    Text("Profil", fontWeight = FontWeight.Bold, fontSize = 18.sp)
                    Spacer(Modifier.height(8.dp))
                    Card(Modifier.fillMaxWidth()) {
                        Row(Modifier.padding(16.dp), verticalAlignment = Alignment.CenterVertically) {
                            AsyncImage(
                                model = state.currentElevePhoto ?: "",
                                contentDescription = "Profil",
                                modifier = Modifier
                                    .size(56.dp)
                                    .clip(CircleShape),
                                contentScale = ContentScale.Crop,
                                error = rememberVectorPainter(Icons.Default.AccountCircle)
                            )
                            Spacer(Modifier.width(16.dp))
                            Column {
                                Text(state.currentEleveNom, fontWeight = FontWeight.Bold, fontSize = 16.sp)
                                Text("Classe : ${state.currentEleveClasse}", fontSize = 14.sp)
                            }
                        }
                    }
                }

                // 3. Notes
                item { Text("Dernières notes", fontWeight = FontWeight.Bold, fontSize = 18.sp) }
                items(state.dernieresNotes) { note ->
                    Card(Modifier
                        .fillMaxWidth()
                        .padding(vertical = 4.dp), colors = CardDefaults.cardColors(
                        containerColor = MaterialTheme.colorScheme.primaryContainer.copy(alpha = 0.2f)
                    )) {
                        Row(
                            modifier = Modifier
                                .padding(16.dp)
                                .fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            // Le weight(1f) ici est crucial pour que le texte occupe l'espace restant
                            Column(
                                modifier = Modifier
                                    .weight(1f)
                                    .padding(end = 8.dp)
                            ) {
                                Text(
                                    text = note.matiere.nom ?: "Matière",
                                    fontWeight = FontWeight.Medium,
                                    fontSize = 16.sp,
                                    maxLines = 2, // Autoriser 2 lignes au lieu de 1
                                    overflow = TextOverflow.Ellipsis // Ajoute des "..." si c'est vraiment trop long
                                )
                            }

                            // Le badge de la note
                            Surface(
                                shape = RoundedCornerShape(8.dp),
                                color = MaterialTheme.colorScheme.primary // Couleur principale
                            ) {
                                Text(
                                    text = String.format("%.1f / 10", note.note),
                                    modifier = Modifier.padding(horizontal = 12.dp, vertical = 6.dp),
                                    color = MaterialTheme.colorScheme.onPrimary,
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }
                    }
                }
            }
        }
    }

    if (showChildrenDialog) {
        EnfantsSelectionDialog(
            listeEnfants = state.listeEnfants,
            onDismiss = { showChildrenDialog = false },
            onEnfantChoisi = { id ->
                showChildrenDialog = false
                viewModel.loadDashboardData(id)
                onEnfantChange(id)
            }
        )
    }
}