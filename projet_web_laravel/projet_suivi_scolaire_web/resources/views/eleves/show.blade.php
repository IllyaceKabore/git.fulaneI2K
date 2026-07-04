@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden;">

        <!-- En-tête -->
        <div style="background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; padding: 30px 40px; display: flex; align-items: center; gap: 25px;">
            <div>
                @if($eleve->photo)
                    <img src="{{ asset('storage/' . $eleve->photo) }}" 
                         alt="Photo de {{ $eleve->nom }}" 
                         style="width: 140px; height: 140px; object-fit: cover; border-radius: 50%; border: 5px solid rgba(255,255,255,0.3);">
                @else
                    <div style="width: 140px; height: 140px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 4rem;">
                        👤
                    </div>
                @endif
            </div>
            <div>
                <h1 style="font-size: 2.2rem; margin-bottom: 8px;">{{ $eleve->nom }} {{ $eleve->prenom }}</h1>
                <p style="font-size: 1.1rem; opacity: 0.9;">Matricule : <strong>{{ $eleve->matricule }}</strong></p>
            </div>
        </div>

        <div style="padding: 40px;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">

                <!-- Informations personnelles -->
                <div>
                    <h2 style="font-size: 1.3rem; margin-bottom: 20px; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Informations Personnelles
                    </h2>
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 12px 0; color: #6b7280; width: 140px;">Classe :</td>
                            <td style="padding: 12px 0; font-weight: 600;">{{ $eleve->classe->nom ?? 'Non assignée' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #6b7280;">Date de Naissance :</td>
                            <td style="padding: 12px 0; font-weight: 500;">
                                {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d F Y') : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #6b7280;">Sexe :</td>
                            <td style="padding: 12px 0; font-weight: 500;">{{ $eleve->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #6b7280;">Tuteur :</td>
                            <td style="padding: 12px 0; font-weight: 500;">{{ $eleve->tuteur }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Situation Financière -->
                <div>
                    <h2 style="font-size: 1.3rem; margin-bottom: 20px; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Situation Financière
                    </h2>
                    <div style="background: #f8fafc; padding: 25px; border-radius: 16px;">
                        <p style="font-size: 1.1rem; margin-bottom: 8px;">Frais de scolarité :</p>
                        <p style="font-size: 2rem; font-weight: bold; color: #2563eb;">
                            {{ number_format($eleve->classe->frais_scolarite ?? 0) }} FCFA
                        </p>
                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
                        <p style="font-size: 1.1rem; margin-bottom: 8px;">Total Payé :</p>
                        <p style="font-size: 1.8rem; font-weight: bold; color: #10b981;">
                            {{ number_format($eleve->totalPaye()) }} FCFA
                        </p>
                        <p style="font-size: 1.4rem; margin-top: 15px; color: #ef4444; font-weight: bold;">
                            Reste à payer : {{ number_format($eleve->resteAPayer()) }} FCFA
                        </p>
                    </div>
                </div>

            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 20px;">
                <h3 style="color: #1e2937; margin-top: 0;">📋 Bulletins Trimestriels</h3>
                <p style="color: #64748b; font-size: 0.9rem;">Sélectionnez un trimestre pour consulter ou imprimer le bulletin de l'élève.</p>
    
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <a href="{{ route('eleves.bulletin', [$eleve->id, 1]) }}" style="flex: 1; text-align: center; background: #2563eb; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        1<sup>er</sup> Trimestre
                    </a>
                    <a href="{{ route('eleves.bulletin', [$eleve->id, 2]) }}" style="flex: 1; text-align: center; background: #2563eb; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        2<sup>ème</sup> Trimestre
                    </a>
                    <a href="{{ route('eleves.bulletin', [$eleve->id, 3]) }}" style="flex: 1; text-align: center; background: #2563eb; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        3<sup>ème</sup> Trimestre
                    </a>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: center;">
                <a href="{{ route('eleves.edit', $eleve) }}" 
                   style="padding: 14px 32px; background: #2563eb; color: white; border-radius: 12px; text-decoration: none; font-weight: 600;">
                    ✏️ Modifier les informations
                </a>
                <a href="{{ route('eleves.index') }}" 
                   style="padding: 14px 32px; background: #e5e7eb; color: #374151; border-radius: 12px; text-decoration: none; font-weight: 600;">
                    ← Retour à la liste
                </a>
            </div>

        </div>
    </div>
</div>
@endsection