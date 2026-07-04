package com.illyace2k.school_connect.ui.theme

import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.graphics.Color

private val Vert      = Color(0xFF2E7D32)
private val VertClair = Color(0xFF66BB6A)
private val VertFonce = Color(0xFF1B5E20)

private val LightColors = lightColorScheme(
    primary           = Vert,
    onPrimary         = Color.White,
    primaryContainer  = Color(0xFFC8E6C9),
    secondary         = VertClair,
    background        = Color(0xFFF5F5F5),
    surface           = Color.White,
    onBackground      = Color(0xFF1C1C1C),
    onSurface         = Color(0xFF1C1C1C),
)

@Composable
fun SchoolConnectTheme(
    content: @Composable () -> Unit
) {
    MaterialTheme(
        colorScheme = LightColors,
        typography  = Typography(),
        content     = content
    )
}