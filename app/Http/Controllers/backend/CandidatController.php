<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Barryvdh\DomPDF\Facade\Pdf;

class CandidatController extends Controller
{
    public function index()
    {
        $candidats = Candidat::latest()->get();
        return view('backend.candidats.index', compact('candidats'));
    }

    public function fiche($id)
    {
        $candidat = Candidat::findOrFail($id);

        $pdf = Pdf::loadView('frontend.espace.fiche-pdf', compact('candidat'));

        return $pdf->download('fiche-candidat-' . ($candidat->numero_dossier ?? $candidat->id) . '.pdf');
    }

    public function destroy($id)
    {
        try {
            $candidat = Candidat::findOrFail($id);
            $candidat->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Candidat supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }
}
