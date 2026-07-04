package com.illyace2k.school_connect.ui.screens.login

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Visibility
import androidx.compose.material.icons.filled.VisibilityOff
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.*
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import com.illyace2k.school_connect.data.local.TokenDataStore

@Composable
fun LoginScreen(onLoginSuccess: (Int) -> Unit) {

    val context   = LocalContext.current
    val dataStore = TokenDataStore(context)
    val vm: LoginViewModel = viewModel(factory = LoginViewModelFactory(dataStore))
    val state by vm.uiState.collectAsStateWithLifecycle()

    var email   by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var showPwd  by remember { mutableStateOf(false) }

    LaunchedEffect(state.success, state.eleveId) {
        if (state.success && state.eleveId != null) {
            onLoginSuccess(state.eleveId!! as Int)
        }
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(horizontal = 32.dp),
        verticalArrangement   = Arrangement.Center,
        horizontalAlignment   = Alignment.CenterHorizontally
    ) {
        Text(
            text       = "SchoolConnect",
            fontSize   = 28.sp,
            fontWeight = FontWeight.Bold,
            color      = MaterialTheme.colorScheme.primary
        )
        Text(
            text  = "Suivi scolaire parent-enfant",
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
}