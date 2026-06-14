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
use App\Http\Controllers\GerantController;

// -----------------------------------------------
// LANDING PAGE
// -----------------------------------------------
Route::get('/', function () {
    return view('landing');
});

// -----------------------------------------------
// LOGIN UNIQUE (MEMBRE + ADMIN)
// -----------------------------------------------
Route::get('/login', [LoginController::class, 'showLogin']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegister']);
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/changer-password', [LoginController::class, 'changerMotDePasse']);

// -----------------------------------------------
// DASHBOARD ADMIN
// -----------------------------------------------
Route::get('/admin', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    if (!Session::get('is_admin')) return redirect('/login');
    $membres             = \App\Models\Membre::all();
    $tontines            = \App\Models\Tontine::with('membres')->get();
    $cotisations         = \App\Models\Cotisation::all();
    $tours               = \App\Models\Tour::with(['tontine', 'membre'])->get();
    $membresEnAttente    = \App\Models\Membre::where('statut', 'en_attente')->get();
    $notifsAdmin         = \App\Models\NotificationAdmin::with('membre')->orderBy('created_at', 'desc')->get();
    return view('dashboard', compact('membres', 'tontines', 'cotisations', 'tours', 'membresEnAttente', 'notifsAdmin'));
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
Route::post('/tontine/{tontineId}/membre', [ControlleurTontine::class, 'ajouterMembre']);
Route::delete('/tontine/{tontineId}/membre/{membreId}', [ControlleurTontine::class, 'retirerMembre']);
Route::post('/tontines/supprimer-selection', [ControlleurTontine::class, 'supprimerSelection']);
Route::post('/tontines/supprimer-tout', [ControlleurTontine::class, 'supprimerTout']);
Route::get('/tontine/{id}/details', [ControlleurTontine::class, 'details']);
// -----------------------------------------------
// COTISATIONS
// -----------------------------------------------
Route::get('/cotisations', [ControlleurCotisation::class, 'index']);
Route::post('/cotisation', [ControlleurCotisation::class, 'store']);
Route::put('/cotisation/{id}', [ControlleurCotisation::class, 'update']);
Route::delete('/cotisation/{id}', [ControlleurCotisation::class, 'destroy']);
Route::post('/cotisations/supprimer-selection', [ControlleurCotisation::class, 'supprimerSelection']);
Route::post('/cotisations/supprimer-tout', [ControlleurCotisation::class, 'supprimerTout']);

// -----------------------------------------------
// TOURS
// -----------------------------------------------
Route::get('/tours', [ControlleurTour::class, 'index']);
Route::post('/tour', [ControlleurTour::class, 'store']);
Route::put('/tour/{id}', [ControlleurTour::class, 'update']);
Route::delete('/tour/{id}', [ControlleurTour::class, 'destroy']);
Route::post('/tour/{id}/mode-reception', [ControlleurTour::class, 'choisirModeReception']);
Route::post('/tours/supprimer-selection', [ControlleurTour::class, 'supprimerSelection']);
Route::post('/tours/supprimer-tout', [ControlleurTour::class, 'supprimerTout']);
// -----------------------------------------------
// ESPACE MEMBRE
// -----------------------------------------------
Route::get('/mon-espace', [EspaceMembreController::class, 'index']);
Route::post('/mon-espace/cotisation', [EspaceMembreController::class, 'ajouterCotisation']);
Route::get('/mon-espace/export/pdf', [EspaceMembreController::class, 'exportPdf']);
Route::get('/mon-espace/export/excel', [EspaceMembreController::class, 'exportExcel']);
Route::post('/mon-espace/notif/{id}/lu', [EspaceMembreController::class, 'marquerLu']);
Route::post('/mon-espace/notifs/marquer-tout-lu', [EspaceMembreController::class, 'marquerToutLu']);
Route::post('/mon-espace/notifs/supprimer-tout', [EspaceMembreController::class, 'supprimerToutNotifs']);
Route::post('/mon-espace/notifs/supprimer-selection', [EspaceMembreController::class, 'supprimerSelectionNotifs']);

Route::get('/admin/export/pdf/{id}', [AdminExportController::class, 'pdfMembre']);
Route::get('/admin/export/excel/{id}', [AdminExportController::class, 'excelMembre']);
Route::get('/admin/export/global/pdf', [AdminExportController::class, 'pdfGlobal']);
Route::post('/admin/notifier-tour/{tourId}', [AdminExportController::class, 'notifierTour']);
Route::get('/admin/membre/{id}', [ControlleurMembre::class, 'show']);

Route::post('/admin/membre/{id}/approuver', [AdminExportController::class, 'approuverMembre']);
Route::post('/admin/membre/{id}/refuser', [AdminExportController::class, 'refuserMembre']);
Route::post('/admin/membres/approuver-tout', [AdminExportController::class, 'approuverTout']);
Route::post('/admin/membres/refuser-tout', [AdminExportController::class, 'refuserTout']);
Route::post('/admin/notifs/marquer-tout-lu', [AdminExportController::class, 'marquerToutLu']);
Route::post('/admin/notifs/supprimer-tout', [AdminExportController::class, 'supprimerToutNotifs']);

// Rôles
Route::post('/admin/membre/{id}/rendre-admin', [AdminExportController::class, 'rendreAdmin']);
Route::post('/admin/membre/{id}/rendre-gerant', [AdminExportController::class, 'rendreGerant']);
Route::post('/admin/membre/{id}/retirer-role', [AdminExportController::class, 'retirerRole']);
Route::post('/admin/tontine/{tontineId}/membre/{membreId}/rendre-admin', [AdminExportController::class, 'rendreAdminTontine']);

// Activer / Désactiver
Route::post('/admin/membre/{id}/activer', [AdminExportController::class, 'activer']);
Route::post('/admin/membre/{id}/desactiver', [AdminExportController::class, 'desactiver']);

Route::get('/gerant', [GerantController::class, 'index']);