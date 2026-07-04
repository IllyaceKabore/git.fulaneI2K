@extends('layouts.app')

@section('content')
<div style="background: white; padding: 50px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; font-family: 'Times New Roman', serif;">
    
    <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
        <div style="text-align: center; width: 40%;">
            <strong>ÉCOLE PRIMAIRE KOUDOUBI KABORÉ</strong><br>
            <small>Discipline - Travail - Succès</small><br>
            <small>Burkina Faso</small>
        </div>
        <div style="text-align: center; width: 40%;">
            <strong>ANNÉE SCOLAIRE : 2025-2026</strong><br>
            <strong>BULLETIN DU {{ $trimestre }}e TRIMESTRE</strong>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <p><strong>NOM ET PRÉNOM :</strong> {{ $eleve->nom }} {{ $eleve->prenom }}</p>
        <p><strong>CLASSE :</strong> {{ $eleve->classe->nom ?? 'N/A' }} | <strong>MATRICULE :</strong> {{ $eleve->matricule }}</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background: #f2f2f2;">
                <th style="border: 1px solid #000; padding: 10px; text-align: left;">MATIÈRES</th>
                <th style="border: 1px solid #000; padding: 10px;">COEFF</th>
                <th style="border: 1px solid #000; padding: 10px;">MOYENNE /10</th>
                <th style="border: 1px solid #000; padding: 10px;">APPRÉCIATIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($eleve->classe->matieres as $matiere)
                <tr>
                    <td style="border: 1px solid #000; padding: 10px;">{{ $matiere->nom }}</td>
                    <td style="border: 1px solid #000; padding: 10px; text-align: center;">{{ $matiere->coefficient ?? 1 }}</td>
                    <td style="border: 1px solid #000; padding: 10px; text-align: center; font-weight: bold;">
                        {{ $matiere->moyenne_calculee !== null ? number_format($matiere->moyenne_calculee, 2, '.', '') : 'N.E' }}
                    </td>
                    <td style="border: 1px solid #000; padding: 10px; font-style: italic;">
                        @php
                            $moyenne = $matiere->moyenne_calculee;
                        @endphp

                        @if($moyenne === null)
                            Non Évalué
                        @elseif($moyenne >= 10)
                            Excellent
                        @elseif($moyenne >= 8)
                            Très Bien
                        @elseif($moyenne >= 7)
                            Bien
                        @elseif($moyenne >= 6)
                            Assez Bien
                        @elseif($moyenne >= 5)
                            Passable
                        @elseif($moyenne >= 4)
                            Insuffisant
                        @else
                            Médiocre
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="border: 1px solid #000; padding: 10px; text-align: center;">Aucune matière enregistrée pour cette classe.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
        <div style="border: 2px solid #000; padding: 15px; width: 300px; text-align: center;">
            <span style="font-size: 1.2rem;">MOYENNE GÉNÉRALE :</span><br>
            <strong style="font-size: 2rem;">{{ number_format($moyenneGenerale, 1) }} / 10</strong>
        </div>
    </div>

    <div style="margin-top: 50px; display: flex; justify-content: space-between;">
        <p style="text-decoration: underline;">Le Directeur de l'École</p>
    </div>

    <div style="margin-top: 30px; text-align: center;" class="no-print">
        <a href="{{ route('eleves.index') }}" style="background: #f1f5f9; color: #334155; padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: 600;">
            ⬅️ Retour à la liste
        </a>
        <button onclick="window.print();" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Imprimer le bulletin
        </button>
    </div>
</div>

<style>
    @media print {
        .sidebar, .no-print, .btn-primary, form { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        body { background: white !important; }
    }
</style>
@endsection