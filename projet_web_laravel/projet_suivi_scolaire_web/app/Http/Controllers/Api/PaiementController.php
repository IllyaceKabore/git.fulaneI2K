<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(Request $request, $id)
    {
        $eleve = $request->user()->eleves()->findOrFail($id);

        $paiements  = $eleve->paiements()->orderByDesc('date_paiement')->get();
        $totalDu    = $eleve->classe->frais_scolarite ?? 0;
        $totalPaye  = $paiements->sum('montant');

        return response()->json([
            'total_du'     => $totalDu,
            'total_paye'   => $totalPaye,
            'reste_a_payer'=> max(0, $totalDu - $totalPaye),
            'paiements'    => $paiements->map(fn($p) => [
                'id'             => $p->id,
                'montant'        => $p->montant,
                'date_paiement'  => $p->date_paiement,
                'recu_url'       => $p->recu
                                    ? asset('storage/' . $p->recu)
                                    : null,
            ]),
        ]);
    }

    public function recu(Request $request, $id)
    {
        $paiement = \App\Models\Paiement::findOrFail($id);

        // Vérifier que ce paiement appartient bien à un enfant du parent
        $request->user()->eleves()->findOrFail($paiement->eleve_id);

        if (! $paiement->recu) {
            return response()->json(['message' => 'Aucun reçu disponible.'], 404);
        }

        return response()->json([
            'recu_url' => asset('storage/' . $paiement->recu),
        ]);
    }
}