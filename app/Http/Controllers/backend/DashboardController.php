<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //index for dashboard

    // public function index(Request $request)
    // {



    //     //Liste des produit les plus vendus
    //     $produitsLesPlusVendus = Produit::withCount('ventes')
    //         ->withSum('ventes', 'produit_vente.prix_unitaire')
    //         ->orderBy('ventes_count', 'desc')
    //         ->having('ventes_count', '>', 0)
    //         ->take(10)
    //         ->get()
    //         ->map(function ($produit) {
    //             // Renommer l'attribut calculé dans la collection
    //             $produit->total_ventes = $produit->ventes_sum_produit_venteprix_unitaire;
    //             return $produit;
    //         });

    //     // statistique chiffre pour card


    //     // Montant total des ventes
    //     $montantTotalVentes = Vente::sum('montant_total');

    //     // Montant total des dépenses
    //     $montantTotalDepenses = Depense::sum('montant');


    //     // dd($montantTotalVentes);



    //     $revenus = DB::table('ventes')
    //         ->selectRaw("MONTHNAME(created_at) as mois, MONTH(created_at) as mois_num, SUM(montant_total) as total_revenu")
    //         ->groupBy('mois', 'mois_num')
    //         ->orderBy('mois_num')
    //         ->get();


    //     // Recuperer les mois et Traduire les mois en français avec Carbon
    //     $labels = $revenus->map(function ($revenu) {
    //         return Carbon::create()->month($revenu->mois_num)->locale('fr')->translatedFormat('F');
    //     });
    //     $data = $revenus->pluck('total_revenu'); // Revenus correspondants

    //     // dd($labels, $data);






    //     // dd($produitsLesPlusVendus->toArray());
    //     return view('backend.pages.index', compact(

    //         'produitsLesPlusVendus',
    //         'montantTotalVentes',
    //         'montantTotalDepenses',

    //         // 'chiffreAffaireParMois',
    //         'labels',
    //         'data'
    //     ));
    // }



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
