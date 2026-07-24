<?php

namespace App\Services;

use App\Models\Candidat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsService
{
    protected int $cacheTtl = 300; // 5 minutes

    /**
     * Point d'entrée unique — retourne TOUTES les données en 1 appel caché.
     */
    public function getAllStats(int $days = 30): array
    {
        return Cache::remember("dashboard.all_stats.{$days}", $this->cacheTtl, function () use ($days) {
            return [
                'kpis'                  => $this->getKpis(),
                'daily_registrations'   => $this->getDailyRegistrations($days),
                'city_distribution'     => $this->getCityDistribution(),
                'education_levels'      => $this->getEducationLevels(),
                'age_distribution'      => $this->getAgeDistribution(),
                'position_distribution' => $this->getPositionDistribution(),
                'foot_distribution'     => $this->getFootDistribution(),
                'recent_activity'       => $this->getRecentActivity(8),
            ];
        });
    }

    /**
     * KPIs — jamais ::get(), tout est agrégé côté SQL.
     */
    public function getKpis(): array
    {
        return Cache::remember('dashboard.kpis', $this->cacheTtl, function () {
            return [
                'total'      => Candidat::count(),
                'villes'     => Candidat::distinct('ville')->count('ville'),
                'niveaux'    => Candidat::distinct('niveau_etudes')->count('niveau_etudes'),
                'aujourdhui' => Candidat::whereDate('created_at', today())->count(),
                'croissance' => $this->tauxCroissanceHebdo(),
                'top_ville'  => $this->getTopVille(),
                'completude' => $this->getTauxCompletude(),
                'pic_jour'   => $this->getPeakDay(),
            ];
        });
    }

    /**
     * Inscriptions journalières sur N jours (0 pour les jours sans inscription).
     */
    public function getDailyRegistrations(int $days = 30): array
    {
        return Cache::remember("dashboard.daily.{$days}", $this->cacheTtl, function () use ($days) {
            $results = Candidat::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = [];
            $series = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date     = now()->subDays($i)->format('Y-m-d');
                $labels[] = now()->subDays($i)->locale('fr')->isoFormat('D MMM');
                $series[] = (int) ($results[$date] ?? 0);
            }

            return compact('labels', 'series');
        });
    }

    /**
     * Top 6 villes par nombre de candidats.
     */
    public function getCityDistribution(): array
    {
        return Cache::remember('dashboard.cities', $this->cacheTtl, function () {
            $data = Candidat::select('ville', DB::raw('COUNT(*) as total'))
                ->whereNotNull('ville')->where('ville', '!=', '')
                ->groupBy('ville')->orderByDesc('total')->limit(6)->get();

            return [
                'labels' => $data->pluck('ville')->toArray(),
                'series' => $data->pluck('total')->map(fn($v) => (int) $v)->toArray(),
            ];
        });
    }

    /**
     * Niveaux d'études avec comptage SQL.
     */
    public function getEducationLevels(): array
    {
        return Cache::remember('dashboard.education', $this->cacheTtl, function () {
            $data = Candidat::select('niveau_etudes', DB::raw('COUNT(*) as total'))
                ->whereNotNull('niveau_etudes')->where('niveau_etudes', '!=', '')
                ->groupBy('niveau_etudes')->orderByDesc('total')->get();

            return [
                'labels' => $data->pluck('niveau_etudes')->toArray(),
                'series' => $data->pluck('total')->map(fn($v) => (int) $v)->toArray(),
            ];
        });
    }

    /**
     * Distribution par tranches d'âge.
     */
    public function getAgeDistribution(): array
    {
        return Cache::remember('dashboard.ages', $this->cacheTtl, function () {
            $tranches = [
                '18–24' => [18, 24], '25–34' => [25, 34],
                '35–44' => [35, 44], '45–54' => [45, 54], '55+' => [55, 99],
            ];

            $labels = [];
            $series = [];
            foreach ($tranches as $label => [$min, $max]) {
                $labels[] = $label;
                $series[] = (int) Candidat::whereBetween('age', [$min, $max])->count();
            }
            return compact('labels', 'series');
        });
    }

    /**
     * Répartition des numéros de poste (1 à 11).
     */
    public function getPositionDistribution(): array
    {
        return Cache::remember('dashboard.positions', $this->cacheTtl, function () {
            $counts = Candidat::select('numero_poste', DB::raw('COUNT(*) as total'))
                ->whereNotNull('numero_poste')
                ->groupBy('numero_poste')
                ->orderBy('numero_poste')
                ->pluck('total', 'numero_poste')
                ->toArray();

            $labels = [];
            $series = [];
            for ($i = 1; $i <= 11; $i++) {
                $labels[] = (string) $i;
                $series[] = isset($counts[$i]) ? (int) $counts[$i] : 0;
            }

            return compact('labels', 'series');
        });
    }

    /**
     * Répartition par pied fort.
     */
    public function getFootDistribution(): array
    {
        return Cache::remember('dashboard.feet', $this->cacheTtl, function () {
            $order = ['gauche' => 'Gauche', 'droit' => 'Droit', 'les deux' => 'Les deux'];
            $counts = Candidat::select('pieds_fort', DB::raw('COUNT(*) as total'))
                ->whereNotNull('pieds_fort')
                ->where('pieds_fort', '!=', '')
                ->groupBy('pieds_fort')
                ->pluck('total', 'pieds_fort')
                ->toArray();

            $labels = [];
            $series = [];
            foreach ($order as $key => $label) {
                $labels[] = $label;
                $series[] = isset($counts[$key]) ? (int) $counts[$key] : 0;
            }

            return compact('labels', 'series');
        });
    }

    /**
     * Derniers candidats inscrits (cache court : 1 min).
     */
    public function getRecentActivity(int $limit = 8): array
    {
        return Cache::remember("dashboard.recent.{$limit}", 60, function () use ($limit) {
            return Candidat::select('nom', 'prenom', 'ville', 'niveau_etudes', 'created_at')
                ->latest()->limit($limit)->get()
                ->map(fn($c) => [
                    'nom'           => $c->nom,
                    'prenom'        => $c->prenom,
                    'ville'         => $c->ville,
                    'niveau_etudes' => $c->niveau_etudes,
                    'created_at'    => $c->created_at->toISOString(),
                ])->toArray();
        });
    }

    // ─── Helpers privés ────────────────────────────────────────────────────

    protected function tauxCroissanceHebdo(): float
    {
        $cette    = Candidat::whereBetween('created_at', [now()->startOfWeek(), now()])->count();
        $derniere = Candidat::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek(),
        ])->count();

        if ($derniere === 0) return $cette > 0 ? 100.0 : 0.0;
        return round((($cette - $derniere) / $derniere) * 100, 1);
    }

    protected function getTopVille(): ?string
    {
        return Candidat::select('ville', DB::raw('COUNT(*) as total'))
            ->whereNotNull('ville')->groupBy('ville')->orderByDesc('total')->value('ville');
    }

    protected function getTauxCompletude(): float
    {
        $total = Candidat::count();
        if ($total === 0) return 0.0;

        $complets = Candidat::whereNotNull('nom')->whereNotNull('prenom')
            ->whereNotNull('ville')->whereNotNull('niveau_etudes')
            ->whereNotNull('telephone')->whereNotNull('date_naissance')->count();

        return round(($complets / $total) * 100, 1);
    }

    protected function getPeakDay(): ?string
    {
        $jours  = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $result = Candidat::select(
                DB::raw('DAYOFWEEK(created_at) as jour'),
                DB::raw('COUNT(*) as total')
            )->groupBy('jour')->orderByDesc('total')->first();

        return $result ? $jours[$result->jour - 1] : null;
    }

    /**
     * Invalider tout le cache dashboard (à appeler dans les observers du modèle Candidat).
     */
    public static function flushCache(): void
    {
        foreach (['7', '30', '90'] as $d) {
            Cache::forget("dashboard.all_stats.{$d}");
            Cache::forget("dashboard.daily.{$d}");
        }
        foreach (['kpis', 'cities', 'education', 'ages', 'positions', 'feet', 'recent.8'] as $k) {
            Cache::forget("dashboard.{$k}");
        }
    }
}