<?php

namespace App\Http\Controllers;

use App\Models\Versement;
use App\Models\Eleve;
use App\Models\Classe;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VersementController extends Controller
{
    public function index()
    {
        $versements = Versement::with(['eleve', 'user'])
                        ->latest()
                        ->paginate(15);
        return view('versements.index', compact('versements'));
    }

    public function create()
    {
        $eleves = Eleve::with('classe')->where('statut', 'actif')->get();
        return view('versements.create', compact('eleves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'montant' => 'required|numeric|min:0',
            'date_versement' => 'required|date',
            'mode_paiement' => 'required|in:especes,mobile_money,banque,autre',
            'trimestre' => 'nullable|integer|in:1,2,3',
            'observation' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['reference_recu'] = 'REC-' . date('Ymd') . '-' . Str::upper(Str::random(6));

        $versement = Versement::create($validated);

        return redirect()->route('versements.index')
            ->with('success', 'Versement enregistré avec succès. Référence : ' . $versement->reference_recu);
    }

    // Génération du reçu PDF
    public function genererRecu(Versement $versement)
    {
        $versement->load('eleve.classe');

        $pdf = Pdf::loadView('versements.recu', compact('versement'));

        return $pdf->stream('recu-' . $versement->reference_recu . '.pdf');
        // Ou download : ->download(...)
    }
}