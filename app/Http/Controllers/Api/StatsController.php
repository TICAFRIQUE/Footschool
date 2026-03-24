<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Déclaré dans routes/web.php sous le groupe middleware(['admin']) :
 *
 *   Route::get('/stats/all',   [StatsController::class, 'all']);
 *   Route::get('/stats/daily', [StatsController::class, 'daily']);
 *
 * Accessible via GET /admin/stats/all?days=30
 *                    /admin/stats/daily?days=7
 */
class StatsController extends Controller
{
    public function __construct(protected StatsService $statsService) {}

    /**
     * GET /admin/stats/all?days=7|30|90
     *
     * Retourne TOUTES les données du dashboard en un seul appel.
     * Remplace les 5 anciens endpoints /admin/stats/daily-registrations etc.
     */
    public function all(Request $request): JsonResponse
    {
        $days = (int) $request->input('days', 30);
        $days = in_array($days, [7, 30, 90]) ? $days : 30;

        return response()->json(
            $this->statsService->getAllStats($days)
        );
    }

    /**
     * GET /admin/stats/daily?days=7|30|90
     *
     * Endpoint séparé pour le filtre de période (graphique inscriptions).
     */
    public function daily(Request $request): JsonResponse
    {
        $days = (int) $request->input('days', 30);
        $days = in_array($days, [7, 30, 90]) ? $days : 30;

        return response()->json(
            $this->statsService->getDailyRegistrations($days)
        );
    }
}
