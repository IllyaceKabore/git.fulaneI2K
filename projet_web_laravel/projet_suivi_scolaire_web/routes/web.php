<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\MatiereController; // Ajouté pour éviter un crash sur les matières
use App\Http\Controllers\EleveController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\VersementController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
})->name('home');

// ==================== ROUTES PROTÉGÉES WEB (AUTHENTIFIÉES) ====================
Route::middleware('auth')->group(function () {

    // Tableaux de bord Web
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tableau de bord', [DashboardController::class, 'index']);

    // ==================== SEULEMENT GESTIONNAIRE (ÉCRITURE / ACTIONS) ====================
    Route::middleware('role:gestionnaire')->group(function () {

        // --- GESTION DES ENSEIGNANTS ---
        Route::get('enseignants', [EnseignantController::class, 'index'])->name('enseignants.index');
        Route::get('enseignants/create', [EnseignantController::class, 'create'])->name('enseignants.create');
        Route::post('enseignants', [EnseignantController::class, 'store'])->name('enseignants.store');
        Route::get('enseignants/{enseignant}/edit', [EnseignantController::class, 'edit'])->name('enseignants.edit');
        Route::put('enseignants/{enseignant}', [EnseignantController::class, 'update'])->name('enseignants.update');
        Route::delete('enseignants/{id}', [EnseignantController::class, 'destroy'])->name('enseignants.destroy');

        // --- GESTION DES MATIÈRES ---
        Route::post('matieres', [MatiereController::class, 'store'])->name('matieres.store');
        Route::put('matieres/{id}', [MatiereController::class, 'update'])->name('matieres.update');

        // --- ROUTES CLASSES ---
        Route::get('classes/create', [ClasseController::class, 'create'])->name('classes.create');
        Route::post('classes', [ClasseController::class, 'store'])->name('classes.store');
        Route::get('classes/{classe}/edit', [ClasseController::class, 'edit'])->name('classes.edit');
        Route::put('classes/{classe}', [ClasseController::class, 'update'])->name('classes.update');
        Route::delete('classes/{classe}', [ClasseController::class, 'destroy'])->name('classes.destroy');

        // --- ROUTES ÉLÈVES ---
        Route::get('eleves/create', [EleveController::class, 'create'])->name('eleves.create');
        Route::post('eleves', [EleveController::class, 'store'])->name('eleves.store');
        Route::get('eleves/{eleve}/edit', [EleveController::class, 'edit'])->name('eleves.edit');
        Route::put('eleves/{id}', [EleveController::class, 'update'])->name('eleves.update');
        Route::delete('eleves/{eleve}', [EleveController::class, 'destroy'])->name('eleves.destroy');
        Route::get('/eleves/{id}/bulletin/{trimestre}', [EleveController::class, 'bulletin'])->name('eleves.bulletin');

        // --- GESTION DES ABSENCES ---
        Route::get('absences', [AbsenceController::class, 'index'])->name('absences.index');
        Route::post('absences', [AbsenceController::class, 'store'])->name('absences.store');
        Route::delete('absences/{absence}', [AbsenceController::class, 'destroy'])->name('absences.destroy');

        // --- GESTION DES ANNONCES ---
        Route::get('annonces', [AnnonceController::class, 'index'])->name('annonces.index');
        Route::post('annonces', [AnnonceController::class, 'store'])->name('annonces.store');
        
        // --- FINANCES ---
        Route::resource('versements', VersementController::class);
        Route::get('versements/{versement}/recu', [VersementController::class, 'genererRecu'])->name('versements.recu');
    });

    // ==================== ACCÈS MIXTE (GESTIONNAIRE ET ENSEIGNANT) ====================
    Route::middleware('role:gestionnaire,enseignant')->group(function () {
        
        // --- CONSULTATION CLASSES ---
        Route::get('classes', [ClasseController::class, 'index'])->name('classes.index');
        Route::get('classes/{classe}', [ClasseController::class, 'show'])->name('classes.show'); 
        Route::get('classes/{classe}/classement', [ClasseController::class, 'classement'])->name('classes.classement');
        Route::post('/classes/{id}/matieres', [ClasseController::class, 'syncMatieres'])->name('classes.syncMatieres');

        // --- CONSULTATION ÉLÈVES ---
        Route::get('eleves', [EleveController::class, 'index'])->name('eleves.index');
        Route::get('eleves/{eleve}', [EleveController::class, 'show'])->name('eleves.show'); 

        // --- NOTES ---
        Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
        Route::get('notes/saisie/{classe_id?}', [NoteController::class, 'saisieParClasse'])->name('notes.saisie');
        Route::post('notes/saisie', [NoteController::class, 'storeMultiple'])->name('notes.storeMultiple');
        Route::delete('notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
        Route::get('notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
        Route::put('notes/{id}', [NoteController::class, 'update'])->name('notes.update');

        // --- PARENTS ---
        Route::get('parents', [ParentController::class, 'index'])->name('parents.index');
        Route::post('parents', [ParentController::class, 'store'])->name('parents.store');
        Route::post('/parents/{id}/associer-enfant', [ParentController::class, 'associerEnfant'])->name('parents.associer');
    });

    // ==================== PROFIL UTILISATEUR WEB ====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Authentification Laravel Breeze (Gère le login/logout des gestionnaires)
require __DIR__.'/auth.php';