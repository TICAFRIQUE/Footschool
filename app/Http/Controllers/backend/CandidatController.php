<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CandidatController extends Controller
{
    

    public function index()
    {
        $candidats = \App\Models\Candidat::latest()->get();

        return view('backend.candidats.index', compact('candidats'));
    }

    public function destroy($id)
    {
        \App\Models\Candidat::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
