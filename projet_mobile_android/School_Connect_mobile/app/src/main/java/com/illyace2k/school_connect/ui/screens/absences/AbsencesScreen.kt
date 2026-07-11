package com.illyace2k.school_connect.ui.screens.absences

import androidx.compose.foundation.background
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
import androidx.compose.ui.draw.clip
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import com.illyace2k.school_connect.data.model.AbsenceModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun AbsencesScreen(
    eleveId: Int,
    onBack: () -> Unit,
    onNavigateToNotes: (Int) -> Unit,
    onNavigateToAbsences: (Int) -> Unit,
    onNavigateToPaiements: (Int) -> Unit,
    onNavigateToNotifications: (Int) -> Unit,
    onNavigateToDashboard: (Int) -> Unit,
    viewModel: AbsencesViewModel = viewModel()
) {
    LaunchedEffect(eleveId) {
        viewModel.getAbsences(eleveId)
    }

    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val activeId = if (state.currentEleveId != 0) state.currentEleveId else eleveId

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Absences", fontWeight = FontWeight.Bold) },
                navigationIcon = {
                    IconButton(onClick = onBack) {
                        Icon(
                            imageVector = Icons.AutoMirrored.Filled.ArrowBack,
                            contentDescription = "Retour"
                        )
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
                Text(text = state.error ?: "Une erreur est survenue", color = MaterialTheme.colorScheme.error)
            } else {
                val absencesList = state.absences

                // Déclaration propre des compteurs
                val totalAbsences = absencesList.size
                val absencesJustifiees = absencesList.count { it.justifie == 1 }
                val absencesNonJustifiees = totalAbsences - absencesJustifiees

                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(horizontal = 16.dp),
                    verticalArrangement = Arrangement.spacedBy(12.dp)
                ) {
                    item {
                        Row(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(vertical = 8.dp),
                            horizontalArrangement = Arrangement.spacedBy(12.dp)
                        ) {
                            StatChip(
                                label = "Total",
                                value = "$totalAbsences",
                                modifier = Modifier.weight(1f),
                                bg = MaterialTheme.colorScheme.errorContainer,
                                fg = MaterialTheme.colorScheme.error
                            )
                            StatChip(
                                label = "Justifiées",
                                value = "$absencesJustifiees",
                                modifier = Modifier.weight(1f),
                                bg = MaterialTheme.colorScheme.primaryContainer,
                                fg = MaterialTheme.colorScheme.primary
                            )
                            StatChip(
                                label = "Non just.",
                                value = "$absencesNonJustifiees",
                                modifier = Modifier.weight(1f),
                                bg = MaterialTheme.colorScheme.surfaceVariant,
                                fg = MaterialTheme.colorScheme.onSurfaceVariant
                            )
                        }
                    }

                    if (absencesList.isEmpty()) {
                        item {
                            Box(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(top = 40.dp),
                                contentAlignment = Alignment.Center
                            ) {
                                Text(
                                    text = "Aucune absence enregistrée.",
                                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                                )
                            }
                        }
                    } else {
                        items(absencesList) { itemAbsence ->
                            AbsenceItem(absence = itemAbsence)
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun StatChip(
    label: String,
    value: String,
    modifier: Modifier = Modifier,
    bg: androidx.compose.ui.graphics.Color,
    fg: androidx.compose.ui.graphics.Color
) {
    Column(
        modifier = modifier
            .clip(RoundedCornerShape(12.dp))
            .background(bg)
            .padding(vertical = 12.dp, horizontal = 8.dp),
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(text = value, fontSize = 20.sp, fontWeight = FontWeight.Bold, color = fg)
        Spacer(modifier = Modifier.height(4.dp))
        Text(text = label, fontSize = 12.sp, color = fg.copy(alpha = 0.8f))
    }
}

@Composable
fun AbsenceItem(absence: AbsenceModel) {
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
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = "Date : ${absence.dateAbsence}",
                    fontWeight = FontWeight.Bold,
                    fontSize = 16.sp
                )
                Spacer(modifier = Modifier.height(4.dp))
                Text(
                    text = "Motif : ${absence.motif ?: "Non spécifié"}",
                    fontSize = 14.sp,
                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                )
            }

            val estJustifiee = absence.justifie == 1
            val statusText = if (estJustifiee) "Justifiée" else "Non justifiée"
            val statusColor = if (estJustifiee) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.error
            val statusBg = if (estJustifiee) MaterialTheme.colorScheme.primaryContainer else MaterialTheme.colorScheme.errorContainer

            Box(
                modifier = Modifier
                    .clip(RoundedCornerShape(8.dp))
                    .background(statusBg)
                    .padding(horizontal = 10.dp, vertical = 6.dp)
            ) {
                Text(
                    text = statusText,
                    color = statusColor,
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Medium
                )
            }
        }
    }
}