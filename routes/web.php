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
use App\Http\Controllers\frontend\NomDomaineController;
use App\Http\Controllers\Api\StatsController;       // ← nouveau StatsController
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\CandidatController;


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
    Route::get('/candidat',         [CandidatController::class, 'index'])->name('candidat.index');
    Route::delete('/candidat/{id}', [CandidatController::class, 'destroy'])->name('candidat.destroy');
});


// ── Frontend ──────────────────────────────────────────────────────
Route::controller(IndexController::class)->group(function () {
    Route::get('/',            'index')->name('index');
    Route::post('/inscription', 'store')->name('inscription.store');
});
