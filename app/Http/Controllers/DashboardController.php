<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected StatsService $statsService) {}

    /**
     * Affiche le tableau de bord.
     * Seuls les KPIs sont passés à la vue (rendu SSR).
     * Les graphiques sont chargés dynamiquement via l'API.
     */
    public function index()
    {
        $stats = $this->statsService->getKpis();

        return view('backend.pages.index', compact('stats'));
    }
}
