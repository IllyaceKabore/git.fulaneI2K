<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileApiController;
use App\Http\Controllers\Api\AnnonceController;

// 🟢 Route publique de connexion (POST /api/login)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/annonces', [AnnonceController::class, 'index']);

// 🔒 Routes mobiles protégées par le Token Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // --- Authentification / compte ---
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::put('/password',  [AuthController::class, 'updatePassword']);
    Route::get('/profil',    [AuthController::class, 'profil']);

    // --- Élèves rattachés au parent ---
    Route::get('/eleves',                   [MobileApiController::class, 'eleves']);
    Route::get('/eleves/{id}',              [MobileApiController::class, 'eleve']);
    Route::get('/eleves/{id}/notes',        [MobileApiController::class, 'notes']);
    Route::get('/eleves/{id}/paiements',    [MobileApiController::class, 'paiements']);
    Route::get('/eleves/{id}/absences',     [MobileApiController::class, 'absences']);
    
    
});
