package com.illyace2k.school_connect.ui.screens

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.illyace2k.school_connect.ui.screens.login.EleveInfo

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun SelectChildScreen(
    enfants: List<EleveInfo>, // Utilisation de EleveInfo pour correspondre au ViewModel
    onChildSelected: (Int) -> Unit
) {
    Scaffold(
        topBar = { TopAppBar(title = { Text("Sélectionnez votre enfant", fontWeight = FontWeight.Bold) }) }
    ) { paddingValues ->
        LazyColumn(
            modifier = Modifier
                .padding(paddingValues)
                .fillMaxSize(),
            verticalArrangement = Arrangement.spacedBy(16.dp),
            contentPadding = PaddingValues(16.dp)
        ) {
            // ✅ Correction de l'argument de items()
            items(enfants) { enfant ->
                Card(
                    modifier = Modifier
                        .fillMaxWidth()
                        .clickable { onChildSelected(enfant.id) }, // ✅ Correction de 'id'
                    elevation = CardDefaults.cardElevation(4.dp)
                ) {
                    Row(
                        modifier = Modifier.padding(16.dp), // ✅ Correction du 'pi' en 'dp'
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        // ✅ Correction des paramètres de l'icône Person
                        Icon(
                            imageVector = Icons.Default.Person,
                            contentDescription = "Icône élève",
                            modifier = Modifier.size(40.dp),
                            tint = MaterialTheme.colorScheme.primary
                        )
                        Spacer(modifier = Modifier.width(16.dp))
                        Column {
                            // ✅ Les propriétés prénom et nom sont désormais accessibles
                            Text(
                                text = "${enfant.prenom} ${enfant.nom}",
                                fontWeight = FontWeight.Bold,
                                fontSize = 18.sp
                            )
                            Text(
                                text = "Cliquez pour voir le tableau de bord",
                                fontSize = 14.sp,
                                color = Color.Gray
                            )
                        }
                    }
                }
            }
        }
    }
}