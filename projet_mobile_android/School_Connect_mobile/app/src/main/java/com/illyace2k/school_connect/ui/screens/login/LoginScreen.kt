package com.illyace2k.school_connect.ui.screens.login

import androidx.compose.foundation.Image
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.AccountCircle
import androidx.compose.material.icons.filled.Visibility
import androidx.compose.material.icons.filled.VisibilityOff
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.*
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import com.illyace2k.school_connect.data.local.TokenDataStore
import com.illyace2k.school_connect.R

@Composable
fun LoginScreen(onLoginSuccess: (Int) -> Unit,
                onNavigateToPasswordRecovery: () -> Unit) {

    val context   = LocalContext.current
    val dataStore = TokenDataStore(context)
    val vm: LoginViewModel = viewModel(factory = LoginViewModelFactory(dataStore))
    val state by vm.uiState.collectAsStateWithLifecycle()

    var email   by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var showPwd  by remember { mutableStateOf(false) }
    var showChildrenDialog by remember { mutableStateOf(false) }

    LaunchedEffect(state.success, state.eleveId) {
        if (state.success && state.eleveId != null) {
            onLoginSuccess(state.eleveId!!)
        }
    }

    Box(modifier = Modifier.fillMaxSize()) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(horizontal = 32.dp),
            verticalArrangement   = Arrangement.Center,
            horizontalAlignment   = Alignment.CenterHorizontally
        ) {
            Image(
                painter = painterResource(id = R.drawable.ic_school_logo), // Assurez-vous d'avoir cette icône
                contentDescription = "Logo de l'école",
                modifier = Modifier.size(100.dp) // Taille du logo
            )
            Spacer(modifier = Modifier.height(24.dp))
            Text(
                text       = "École Primaire KK",
                fontSize   = 28.sp,
                fontWeight = FontWeight.Bold,
                color      = MaterialTheme.colorScheme.primary
            )
            Text(
                text  = "Suivi parent-enfant",
                fontSize = 14.sp,
                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
            )

            Spacer(Modifier.height(40.dp))

            OutlinedTextField(
                value         = email,
                onValueChange = { email = it },
                label         = { Text("Email") },
                singleLine    = true,
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Email),
                modifier      = Modifier.fillMaxWidth()
            )

            Spacer(Modifier.height(16.dp))

            OutlinedTextField(
                value         = password,
                onValueChange = { password = it },
                label         = { Text("Mot de passe") },
                singleLine    = true,
                visualTransformation = if (showPwd) VisualTransformation.None
                else PasswordVisualTransformation(),
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Password),
                trailingIcon  = {
                    IconButton(onClick = { showPwd = !showPwd }) {
                        Icon(
                            imageVector = if (showPwd) Icons.Default.VisibilityOff
                            else Icons.Default.Visibility,
                            contentDescription = null
                        )
                    }
                },
                modifier = Modifier.fillMaxWidth()
            )
            TextButton(
                // ✅ Ajoutez les parenthèses () pour appeler la fonction
                onClick = { onNavigateToPasswordRecovery() },
                modifier = Modifier.align(Alignment.End)
            ) {
                Text("Mot de passe oublié ?")
            }

            Spacer(Modifier.height(8.dp))

            state.error?.let {
                Text(it, color = MaterialTheme.colorScheme.error, fontSize = 13.sp)
                Spacer(Modifier.height(8.dp))
            }

            Button(
                onClick  = { vm.login(email, password) },
                enabled  = email.isNotBlank() && password.isNotBlank() && !state.isLoading,
                modifier = Modifier.fillMaxWidth().height(50.dp)
            ) {
                if (state.isLoading) {
                    CircularProgressIndicator(
                        modifier    = Modifier.size(24.dp),
                        color       = MaterialTheme.colorScheme.onPrimary,
                        strokeWidth = 2.dp
                    )
                } else {
                    Text("Se connecter", fontSize = 16.sp)
                }
            }
        }

        // 🟢 LE CHANGEMENT : Affichage de la pop-up si le parent a plusieurs enfants
        if (state.hasMultipleChildren) {
            EnfantsSelectionDialog(
                listeEnfants = state.listeEnfants,
                onDismiss = { showChildrenDialog = false },
                onEnfantChoisi = { id ->
                vm.selectionnerEnfant(id)
            })
        }
    }
}

// 🟢 LE COMPOSANT DIALOGUE
@Composable
fun EnfantsSelectionDialog(
    listeEnfants: List<EleveInfo>,
    onEnfantChoisi: (Int) -> Unit,
    onDismiss: () -> Unit
) {
    AlertDialog(
        onDismissRequest = { /* Empêche la fermeture en cliquant à côté */ },
        title = {
            Text(text = "Sélectionnez un enfant", fontWeight = FontWeight.Bold, fontSize = 18.sp)
        },
        text = {
            LazyColumn(
                verticalArrangement = Arrangement.spacedBy(8.dp),
                modifier = Modifier.fillMaxWidth()
            ) {
                items(listeEnfants) { enfant ->
                    Card(
                        modifier = Modifier
                            .fillMaxWidth()
                            .clickable { onEnfantChoisi(enfant.id) },
                        colors = CardDefaults.cardColors(
                            containerColor = MaterialTheme.colorScheme.surfaceVariant
                        )
                    ) {
                        Row(
                            modifier = Modifier.padding(12.dp).fillMaxWidth(),
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Icon(
                                imageVector = Icons.Default.AccountCircle,
                                contentDescription = null,
                                modifier = Modifier.size(36.dp),
                                tint = MaterialTheme.colorScheme.primary
                            )
                            Spacer(modifier = Modifier.width(12.dp))
                            Text(
                                text = "${enfant.prenom} ${enfant.nom}",
                                fontWeight = FontWeight.Medium,
                                fontSize = 16.sp
                            )
                        }
                    }
                }
            }
        },
        confirmButton = {} // Aucun bouton requis, la sélection ferme le dialogue
    )
}