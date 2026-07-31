<?php

namespace App\Providers;

use App\Models\Candidat;
use App\Models\Parametre;
use App\Observers\CandidatObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // ── Observer Candidat → invalide le cache dashboard automatiquement
        Candidat::observe(CandidatObserver::class);

        // ── Sync des permissions pour les rôles privilégiés
        $this->app->booted(function () {
            try {
                if (Schema::hasTable('permissions') && Schema::hasTable('roles')) {
                    $permissions = Permission::pluck('id')->toArray();

                    $developpeurRole = Role::where('name', 'developpeur')->first();
                    $superadminRole  = Role::where('name', 'superadmin')->first();

                    if ($developpeurRole) {
                        $developpeurRole->permissions()->sync($permissions);
                    }

                    if ($superadminRole) {
                        $superadminRole->permissions()->sync($permissions);
                    }
                }
            } catch (\Exception $e) {
                // Note : on ne peut pas appeler back() dans un ServiceProvider
                // (pas de requête HTTP disponible à ce stade).
                // On logue l'erreur sans interrompre le boot.
                logger()->error('Erreur sync permissions : ' . $e->getMessage());
            }
        });

        // ── Partage des paramètres globaux avec toutes les vues
        // Enveloppé dans un try/catch : certaines commandes (ex. composer
        // install → package:discover) tournent sans base de données
        // disponible (CI, premier déploiement avant migration...).
        $data_parametre = null;

        try {
            if (Schema::hasTable('parametres')) {
                $data_parametre = Parametre::with('media')->first();
            }
        } catch (\Exception $e) {
            logger()->error('Erreur chargement paramètres : ' . $e->getMessage());
        }

        view()->share([
            'data_parametre' => $data_parametre,
        ]);
    }
}