package com.illyace2k.school_connect.ui.screens.dashboard

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import com.illyace2k.school_connect.data.model.NoteModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun DashboardScreen(
    eleveId: Int,
    onNavigateToNotes: () -> Unit,
    onNavigateToAbsences: () -> Unit,
    onNavigateToPaiements: () -> Unit,
    viewModel: DashboardViewModel = viewModel()
) {
    // Rechargement automatique des données quand l'id élève change
    LaunchedEffect(eleveId) {
        viewModel.loadDashboardData(eleveId)
    }

    val state by viewModel.uiState.collectAsStateWithLifecycle()

    Scaffold(
        topBar = {
            TopAppBar(title = { Text("Tableau de bord", fontWeight = FontWeight.Bold) })
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
            } else {
                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(horizontal = 16.dp),
                    verticalArrangement = Arrangement.spacedBy(16.dp)
                ) {
                    // 1. Section Vue d'ensemble (Moyenne & Rang)
                    item {
                        Card(
                            modifier = Modifier.fillMaxWidth(),
                            shape = RoundedCornerShape(16.dp),
                            colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.primaryContainer)
                        ) {
                            Row(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(20.dp),
                                horizontalArrangement = Arrangement.SpaceBetween
                            ) {
                                Column {
                                    Text("Moyenne Générale", fontSize = 14.sp, color = MaterialTheme.colorScheme.onPrimaryContainer)
                                    // Formatage de la moyenne à 2 chiffres après la virgule
                                    Text(
                                        text = String.format("%.2f/20", state.moyenneGenerale),
                                        fontSize = 28.sp,
                                        fontWeight = FontWeight.Bold,
                                        color = MaterialTheme.colorScheme.onPrimaryContainer
                                    )
                                }
                                Column(horizontalAlignment = Alignment.End) {
                                    Text("Rang", fontSize = 14.sp, color = MaterialTheme.colorScheme.onPrimaryContainer)
                                    Text(
                                        text = state.rang,
                                        fontSize = 28.sp,
                                        fontWeight = FontWeight.Bold,
                                        color = MaterialTheme.colorScheme.onPrimaryContainer
                                    )
                                }
                            }
                        }
                    }

                    // 2. Boutons de navigation vers les autres sections
                    item {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.spacedBy(8.dp)
                        ) {
                            Button(onClick = onNavigateToNotes, modifier = Modifier.weight(1f)) { Text("Notes") }
                            Button(onClick = onNavigateToAbsences, modifier = Modifier.weight(1f)) { Text("Absences") }
                            Button(onClick = onNavigateToPaiements, modifier = Modifier.weight(1f)) { Text("Paiements") }
                        }
                    }

                    // 3. Titre de la section Dernières Notes
                    item {
                        Text(
                            text = "Dernières Notes",
                            fontSize = 18.sp,
                            fontWeight = FontWeight.Bold,
                            modifier = Modifier.padding(vertical = 4.dp)
                        )
                    }

                    // 4. Liste des dernières notes obtenues
                    if (state.dernieresNotes.isEmpty()) {
                        item {
                            Text(
                                text = "Aucune note disponible pour le moment.",
                                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f),
                                modifier = Modifier.padding(vertical = 16.dp)
                            )
                        }
                    } else {
                        items(state.dernieresNotes) { noteItem ->
                            DashboardNoteItem(note = noteItem)
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun DashboardNoteItem(note: NoteModel) {
    Card(
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            horizontalArrangement = Arrangement.SpaceBetween,
            verticalAlignment = Alignment.CenterVertically
        ) {
            Column {
                // Récupération sécurisée du nom de la matière
                Text(
                    text = note.matiere?.nom ?: "Matière inconnue",
                    fontWeight = FontWeight.SemiBold,
                    fontSize = 16.sp
                )
                Text(
                    text = note.periode,
                    fontSize = 12.sp,
                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                )
            }

            // Affichage de la note
            Box(
                modifier = Modifier
                    .clip(RoundedCornerShape(8.dp))
                    .background(
                        if (note.note >= 10.0) MaterialTheme.colorScheme.primaryContainer
                        else MaterialTheme.colorScheme.errorContainer
                    )
                    .padding(horizontal = 12.dp, vertical = 6.dp)
            ) {
                Text(
                    text = "${note.note}/20",
                    fontWeight = FontWeight.Bold,
                    color = if (note.note >= 10.0) MaterialTheme.colorScheme.primary
                    else MaterialTheme.colorScheme.error
                )
            }
        }
    }
}