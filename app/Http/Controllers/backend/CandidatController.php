<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;

class CandidatController extends Controller
{
    /**
     * Affiche la liste des candidats
     */
    public function index()
    {
        // On récupère tous les candidats pour alimenter le tableau et les filtres dynamiques
        $candidats = Candidat::latest()->get();

        return view('backend.candidats.index', compact('candidats'));
    }

    /**
     * Supprime un candidat
     */
    public function destroy($id)
    {
        $candidat = Candidat::findOrFail($id);
        $candidat->delete();

        return response()->json(['success' => true]);
    }
}
