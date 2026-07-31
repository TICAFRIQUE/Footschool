<?php

use App\Http\Controllers\DashboardStatsController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ModuleController;
use App\Http\Controllers\backend\NewsLettersController;
use App\Http\Controllers\backend\ParametreController;
use App\Http\Controllers\backend\PermissionController;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\CommandeServiceController;
use App\Http\Controllers\frontend\BaseController;
use App\Http\Controllers\frontend\HebergementController;
use App\Http\Controllers\frontend\IndexController;
use App\Http\Controllers\frontend\InscriptionPaiementController;
use App\Http\Controllers\frontend\EspaceCandidatController;
use App\Http\Controllers\frontend\NomDomaineController;
use App\Http\Controllers\Api\StatsController;       // ← nouveau StatsController
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\CandidatController;
use App\Http\Controllers\backend\InscriptionController;
use App\Http\Controllers\backend\PaiementController;


Route::fallback(function () {
    return view('backend.utility.auth-404-basic');
});

Route::middleware(['admin'])->prefix('admin')->group(function () {

    // ── Authentification ─────────────────────────────────────────
    Route::controller(AdminController::class)->group(function () {
        Route::get('/login',  'login')->name('admin.login')->withoutMiddleware('admin');
        Route::post('/login', 'login')->name('admin.login')->withoutMiddleware('admin');
        Route::post('/logout', 'logout')->name('admin.logout');
    });

    // ── Dashboard ─────────────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // ── Stats API (appelées en AJAX par le dashboard) ─────────────
    // Accessibles via GET /admin/stats/all?days=30
    //                     GET /admin/stats/daily?days=7
    // Protégées par le même middleware 'admin' que le reste.
    Route::get('/stats/all',   [StatsController::class, 'all'])->name('stats.all');
    Route::get('/stats/daily', [StatsController::class, 'daily'])->name('stats.daily');

    // ── Paramètres application ────────────────────────────────────
    Route::prefix('parametre')->controller(ParametreController::class)->group(function () {
        Route::get('',                   'index')->name('parametre.index');
        Route::post('store',             'store')->name('parametre.store');
        Route::get('maintenance-up',     'maintenanceUp')->name('parametre.maintenance-up');
        Route::get('maintenance-down',   'maintenanceDown')->name('parametre.maintenance-down');
        Route::get('optimize-clear',     'optimizeClear')->name('parametre.optimize-clear');
        Route::get('download-backup/{file}', 'downloadBackup')->name('setting.download-backup');
    });

    // ── Admins ────────────────────────────────────────────────────
    Route::prefix('register')->controller(AdminController::class)->group(function () {
        Route::get('',                    'index')->name('admin-register.index');
        Route::post('store',              'store')->name('admin-register.store');
        Route::post('update/{id}',        'update')->name('admin-register.update');
        Route::delete('delete/{id}',      'delete')->name('admin-register.delete');
        Route::get('profil/{id}',         'profil')->name('admin-register.profil');
        Route::post('change-password',    'changePassword')->name('admin-register.new-password');
    });

    // ── Rôles ─────────────────────────────────────────────────────
    Route::prefix('role')->controller(RoleController::class)->group(function () {
        Route::get('',                'index')->name('role.index');
        Route::post('store',          'store')->name('role.store');
        Route::post('update/{id}',    'update')->name('role.update');
        Route::delete('delete/{id}',  'delete')->name('role.delete');
    });

    // ── Permissions ───────────────────────────────────────────────
    Route::prefix('permission')->controller(PermissionController::class)->group(function () {
        Route::get('',                'index')->name('permission.index');
        Route::get('create',          'create')->name('permission.create');
        Route::post('store',          'store')->name('permission.store');
        Route::get('edit{id}',        'edit')->name('permission.edit');
        Route::put('update/{id}',     'update')->name('permission.update');
        Route::delete('delete/{id}',  'delete')->name('permission.delete');
    });

    // ── Modules ───────────────────────────────────────────────────
    Route::prefix('module')->controller(ModuleController::class)->group(function () {
        Route::get('',                'index')->name('module.index');
        Route::post('store',          'store')->name('module.store');
        Route::post('update/{id}',    'update')->name('module.update');
        Route::delete('delete/{id}',  'delete')->name('module.delete');
    });

    // ── Candidats ─────────────────────────────────────────────────
    Route::get('/candidat',              [CandidatController::class, 'index'])->name('candidat.index');
    Route::get('/candidat/{id}/fiche',   [CandidatController::class, 'fiche'])->name('candidat.fiche');
    Route::delete('/candidat/{id}',      [CandidatController::class, 'destroy'])->name('candidat.destroy');

    // ── Inscriptions (candidats inscrits + paiement) ────────────────
    Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');

    // ── Paiements en attente (validation manuelle Wave) ──────────────
    Route::get('/paiements-attente', [PaiementController::class, 'index'])->name('paiements.index');
    Route::post('/paiements-attente/{candidat}/valider', [PaiementController::class, 'valider'])->name('paiements.valider');
});


// ── Préinscription (ancienne page d'accueil, désormais secondaire) ──
Route::controller(IndexController::class)->group(function () {
    Route::get('/preinscription', 'index')->name('preinscription');
    Route::post('/inscription',   'store')->name('inscription.store');
});

// ── Accueil : présentation de l'inscription officielle (phase 2) ────
Route::get('/', [InscriptionPaiementController::class, 'accueil'])->name('accueil');

// ── Finalisation de l'inscription (formulaire + paiement) ────────────
Route::controller(InscriptionPaiementController::class)
    ->prefix('inscription-officielle')->name('finalisation.')
    ->group(function () {
        Route::get('/',           'connexion')->name('connexion');
        Route::post('/verifier',  'verifier')->middleware('throttle:10,1')->name('verifier');
        Route::get('/reprendre',  'reprendre')->middleware('espace.candidat')->name('reprendre');

        Route::middleware('inscription.session')->group(function () {
            Route::get('/confirmation',  'confirmation')->name('confirmation');
            Route::post('/confirmer',    'confirmer')->name('confirmer');
            Route::get('/informations',  'informations')->name('informations');
            Route::post('/informations', 'enregistrerInformations')->name('informations.store');
            Route::get('/paiement',      'paiement')->name('paiement');
            Route::post('/paiement/initier', 'initierPaiement')->name('paiement.initier');
        });

        Route::get('/paiement/simuler/{paiement}', 'simulerFormulaire')->name('paiement.simuler');
        Route::post('/paiement/callback',           'callback')->name('paiement.callback');
        Route::get('/retour/{paiement}',            'retour')->name('retour');
    });

// ── Espace candidat ─────────────────────────────────────────────────
Route::controller(EspaceCandidatController::class)
    ->prefix('espace-candidat')->name('espace.')
    ->group(function () {
        Route::get('/',           'connexion')->name('connexion');
        Route::post('/verifier',  'verifier')->middleware('throttle:10,1')->name('verifier');

        Route::middleware('espace.candidat')->group(function () {
            Route::get('/dashboard',       'dashboard')->name('dashboard');
            Route::get('/fiche',           'fiche')->name('fiche');
            Route::get('/recu/{paiement}', 'recu')->name('recu');
            Route::post('/deconnexion',    'deconnexion')->name('deconnexion');
        });
    });
