<?php

namespace App\Http\Controllers;

use App\Models\Candidat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    // Inscriptions par jour (7 derniers jours)
    public function dailyRegistrations(Request $request)
    {
        $days = $request->get('days', 7);
        $data = Candidat::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->locale('fr')->isoFormat('ddd D MMM')),
            'series' => $data->pluck('total')
        ]);
    }

    // Répartition par ville (top 5 + autres)
    public function cityDistribution()
    {
        $cities = Candidat::select('ville', DB::raw('count(*) as total'))
            ->groupBy('ville')
            ->orderByDesc('total')
            ->get();

        $top = $cities->take(5);
        $otherCount = $cities->slice(5)->sum('total');

        $labels = $top->pluck('ville')->toArray();
        $data   = $top->pluck('total')->toArray();

        if ($otherCount > 0) {
            $labels[] = 'Autres';
            $data[]   = $otherCount;
        }

        return response()->json(['labels' => $labels, 'series' => $data]);
    }

    // Niveaux d'études
    public function educationLevels()
    {
        $levels = Candidat::select('niveau_etudes', DB::raw('count(*) as total'))
            ->groupBy('niveau_etudes')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'labels' => $levels->pluck('niveau_etudes'),
            'series' => $levels->pluck('total')
        ]);
    }

    // Tranches d'âge
    public function ageDistribution()
    {
        $candidats = Candidat::select('date_naissance')->get();
        $ranges = [
            'moins de 20' => 0,
            '20-25' => 0,
            '26-30' => 0,
            '31-35' => 0,
            '36-40' => 0,
            'plus de 40' => 0
        ];

        foreach ($candidats as $c) {
            $age = Carbon::parse($c->date_naissance)->age;
            if ($age < 20) $ranges['moins de 20']++;
            elseif ($age <= 25) $ranges['20-25']++;
            elseif ($age <= 30) $ranges['26-30']++;
            elseif ($age <= 35) $ranges['31-35']++;
            elseif ($age <= 40) $ranges['36-40']++;
            else $ranges['plus de 40']++;
        }

        return response()->json([
            'labels' => array_keys($ranges),
            'series' => array_values($ranges)
        ]);
    }

    // Dernières inscriptions
    public function recentActivity()
    {
        $recent = Candidat::latest()->take(5)->get(['nom', 'prenom', 'ville', 'created_at']);
        return response()->json($recent);
    }
}