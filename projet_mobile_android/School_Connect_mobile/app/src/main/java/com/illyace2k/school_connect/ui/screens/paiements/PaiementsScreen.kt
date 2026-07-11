package com.illyace2k.school_connect.ui.screens.paiements

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
import com.illyace2k.school_connect.data.model.PaiementModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun PaiementsScreen(
    eleveId: Int,
    onBack: () -> Unit,
    onNavigateToNotes: (Int) -> Unit,
    onNavigateToAbsences: (Int) -> Unit,
    onNavigateToPaiements: (Int) -> Unit,
    onNavigateToNotifications: (Int) -> Unit,
    onNavigateToDashboard: (Int) -> Unit,
    viewModel: PaiementsViewModel = viewModel()
) {
    LaunchedEffect(eleveId) {
        viewModel.loadPaiements(eleveId)
    }

    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val activeId = if (state.currentEleveId != 0) state.currentEleveId else eleveId

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Paiement", fontWeight = FontWeight.Bold) },
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
            } else {
                val paiementsList = state.paiements

                // ✅ CORRIGÉ : On extrait directement les valeurs envoyées dynamiquement par Laravel via l'UiState
                val totalPaye = state.totalPaye
                val resteAPayer = state.resteAPayer

                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(horizontal = 16.dp),
                    verticalArrangement = Arrangement.spacedBy(12.dp)
                ) {
                    // Cartes de synthèse financière
                    item {
                        Card(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(vertical = 8.dp),
                            shape = RoundedCornerShape(16.dp),
                            colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surfaceVariant)
                        ) {
                            Column(modifier = Modifier.padding(16.dp)) {
                                Row(
                                    modifier = Modifier.fillMaxWidth(),
                                    horizontalArrangement = Arrangement.SpaceBetween
                                ) {
                                    Text("Total Payé", fontSize = 14.sp)
                                    Text("Reste à payer", fontSize = 14.sp)
                                }
                                Row(
                                    modifier = Modifier.fillMaxWidth(),
                                    horizontalArrangement = Arrangement.SpaceBetween,
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Text(
                                        text = String.format("%,.0f FCFA", totalPaye),
                                        fontSize = 20.sp,
                                        fontWeight = FontWeight.Bold,
                                        color = MaterialTheme.colorScheme.primary
                                    )
                                    Text(
                                        text = String.format("%,.0f FCFA", resteAPayer),
                                        fontSize = 20.sp,
                                        fontWeight = FontWeight.Bold,
                                        color = if (resteAPayer > 0) MaterialTheme.colorScheme.error else MaterialTheme.colorScheme.primary
                                    )
                                }
                            }
                        }
                    }

                    // Section Historique des reçus
                    item {
                        Text(
                            text = "Historique des versements",
                            fontSize = 16.sp,
                            fontWeight = FontWeight.Bold,
                            modifier = Modifier.padding(vertical = 4.dp)
                        )
                    }

                    if (paiementsList.isEmpty()) {
                        item {
                            Box(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(top = 32.dp),
                                contentAlignment = Alignment.Center
                            ) {
                                Text(
                                    text = "Aucun versement trouvé.",
                                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
                                )
                            }
                        }
                    } else {
                        items(paiementsList) { paiement ->
                            PaiementItem(paiement = paiement)
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun PaiementItem(paiement: PaiementModel) {
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
                Text(
                    text = paiement.typePaiement,
                    fontWeight = FontWeight.Bold,
                    fontSize = 15.sp
                )
                Text(
                    text = "Date : ${paiement.datePaiement}",
                    fontSize = 13.sp,
                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                )
            }

            Box(
                modifier = Modifier
                    .clip(RoundedCornerShape(8.dp))
                    .background(MaterialTheme.colorScheme.primaryContainer)
                    .padding(horizontal = 12.dp, vertical = 6.dp)
            ) {
                Text(
                    text = String.format("%,.0f F", paiement.montant),
                    fontWeight = FontWeight.Bold,
                    color = MaterialTheme.colorScheme.primary
                )
            }
        }
    }
}