<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index()
    {
        // Test ultra-simple sans charger toute la table d'un coup
        $annonces = Annonce::latest()->get();
        return response()->json($annonces, 200);
    }
}