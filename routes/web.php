<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ControlleurMembre;
use App\Http\Controllers\ControlleurCotisation;
use App\Http\Controllers\ControlleurTontine;
use App\Http\Controllers\ControlleurTour;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EspaceMembreController;
use App\Http\Controllers\AdminExportController;

// -----------------------------------------------
// LANDING PAGE
// -----------------------------------------------
Route::get('/', function () {
    return view('landing');
});

// -----------------------------------------------
// DASHBOARD ADMIN (protégé)
// -----------------------------------------------
Route::get('/admin', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    if (!Session::get('is_admin')) return redirect('/login');
    $membres     = \App\Models\Membre::all();
    $tontines    = \App\Models\Tontine::all();
    $cotisations = \App\Models\Cotisation::all();
    $tours       = \App\Models\Tour::all();
    return view('dashboard', compact('membres', 'tontines', 'cotisations', 'tours'));
});

// -----------------------------------------------
// MEMBRES
// -----------------------------------------------
Route::get('/membres', [ControlleurMembre::class, 'index']);
Route::post('/membre', [ControlleurMembre::class, 'store']);
Route::put('/membre/{id}', [ControlleurMembre::class, 'update']);
Route::delete('/membre/{id}', [ControlleurMembre::class, 'destroy']);

// -----------------------------------------------
// TONTINES
// -----------------------------------------------
Route::get('/tontines', [ControlleurTontine::class, 'index']);
Route::post('/tontine', [ControlleurTontine::class, 'store']);
Route::put('/tontine/{id}', [ControlleurTontine::class, 'update']);
Route::delete('/tontine/{id}', [ControlleurTontine::class, 'destroy']);

// -----------------------------------------------
// COTISATIONS
// -----------------------------------------------
Route::get('/cotisations', [ControlleurCotisation::class, 'index']);
Route::post('/cotisation', [ControlleurCotisation::class, 'store']);
Route::put('/cotisation/{id}', [ControlleurCotisation::class, 'update']);
Route::delete('/cotisation/{id}', [ControlleurCotisation::class, 'destroy']);

// -----------------------------------------------
// TOURS
// -----------------------------------------------
Route::get('/tours', [ControlleurTour::class, 'index']);
Route::post('/tour', [ControlleurTour::class, 'store']);
Route::put('/tour/{id}', [ControlleurTour::class, 'update']);
Route::delete('/tour/{id}', [ControlleurTour::class, 'destroy']);

// -----------------------------------------------
// LOGIN / LOGOUT
// -----------------------------------------------
Route::get('/login', [LoginController::class, 'showLogin']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/register', [LoginController::class, 'showRegister']);
Route::post('/register', [LoginController::class, 'register']);
Route::post('/changer-password', [LoginController::class, 'changerMotDePasse']);

// -----------------------------------------------
// ESPACE MEMBRE
// -----------------------------------------------
Route::get('/mon-espace', [EspaceMembreController::class, 'index']);
Route::post('/mon-espace/cotisation', [EspaceMembreController::class, 'ajouterCotisation']);
Route::get('/mon-espace/export/pdf', [EspaceMembreController::class, 'exportPdf']);
Route::get('/mon-espace/export/excel', [EspaceMembreController::class, 'exportExcel']);
Route::post('/mon-espace/notif/{id}/lu', [EspaceMembreController::class, 'marquerLu']);

// -----------------------------------------------
// EXPORTS ADMIN
// -----------------------------------------------
Route::get('/admin/export/pdf/{id}', [AdminExportController::class, 'pdfMembre']);
Route::get('/admin/export/excel/{id}', [AdminExportController::class, 'excelMembre']);
Route::get('/admin/export/global/pdf', [AdminExportController::class, 'pdfGlobal']);
Route::post('/admin/notifier-tour/{tourId}', [AdminExportController::class, 'notifierTour']);
Route::get('/admin/membre/{id}', [ControlleurMembre::class, 'show']);