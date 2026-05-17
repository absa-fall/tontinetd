<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControlleurMembre;
use App\Http\Controllers\ControlleurCotisation;
use App\Http\Controllers\ControlleurTontine;
use App\Http\Controllers\ControlleurTour;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EspaceMembreController;

Route::get('/', function () {
    $membres     = \App\Models\Membre::all();
    $tontines    = \App\Models\Tontine::all();
    $cotisations = \App\Models\Cotisation::all();
    $tours       = \App\Models\Tour::all();
    return view('dashboard', compact('membres', 'tontines', 'cotisations', 'tours'));
});

// Auth
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Espace membre
Route::get('/mon-espace', [EspaceMembreController::class, 'index']);
Route::post('/mon-espace/cotisation', [EspaceMembreController::class, 'ajouterCotisation']);
Route::put('/mon-espace/cotisation/{id}', [EspaceMembreController::class, 'modifierCotisation']);
Route::post('/mon-espace/notif/{id}/lu', [EspaceMembreController::class, 'marquerLu']);
Route::get('/mon-espace/export/pdf', [EspaceMembreController::class, 'exportPdf']);
Route::get('/mon-espace/export/excel', [EspaceMembreController::class, 'exportExcel']);

// Admin - Membres
Route::get('/membres', [ControlleurMembre::class, 'index']);
Route::post('/membre', [ControlleurMembre::class, 'store']);
Route::put('/membre/{id}', [ControlleurMembre::class, 'update']);
Route::delete('/membre/{id}', [ControlleurMembre::class, 'destroy']);
Route::get('/admin/membre/{id}', [ControlleurMembre::class, 'show']);

// Tontines
Route::get('/tontines', [ControlleurTontine::class, 'index']);
Route::post('/tontine', [ControlleurTontine::class, 'store']);
Route::put('/tontine/{id}', [ControlleurTontine::class, 'update']);
Route::delete('/tontine/{id}', [ControlleurTontine::class, 'destroy']);

// Cotisations
Route::get('/cotisations', [ControlleurCotisation::class, 'index']);
Route::post('/cotisation', [ControlleurCotisation::class, 'store']);
Route::put('/cotisation/{id}', [ControlleurCotisation::class, 'update']);
Route::delete('/cotisation/{id}', [ControlleurCotisation::class, 'destroy']);

// Tours
Route::get('/tours', [ControlleurTour::class, 'index']);
Route::post('/tour', [ControlleurTour::class, 'store']);
Route::put('/tour/{id}', [ControlleurTour::class, 'update']);
Route::delete('/tour/{id}', [ControlleurTour::class, 'destroy']);