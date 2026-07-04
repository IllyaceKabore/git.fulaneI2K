@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 2rem; color: #1e2937; margin: 0;">Détails de la classe : {{ $classe->nom }}</h1>
            <p style="color: #64748b; margin-top: 5px;">Aperçu des matières enseignées et des élèves inscrits.</p>
        </div>
        <a href="{{ route('classes.index') }}" style="background: #f1f5f9; color: #334155; padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: 600;">
            ⬅️ Retour aux classes
        </a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 30px;">
        
        <div style="flex: 1; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #1e3a8a; font-size: 1.3rem; margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                📚 Matières Enseignées ({{ $classe->matieres->count() }})
            </h3>

            <form action="{{ route('classes.syncMatieres', $classe->id) }}" method="POST">
                @csrf
                <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px;">
                    
                    @foreach($toutesLesMatieres as $matiere)
                        @php
                            // On vérifie si la matière fait déjà partie de cette classe
                            $estAttribuee = $classe->matieres->contains($matiere->id);
                        @endphp
                        
                        <label style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <input type="checkbox" name="matieres[]" value="{{ $matiere->id }}" {{ $estAttribuee ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="font-weight: 600; color: #334155;">{{ $matiere->nom }}</span>
                            </div>
                            
                            @if($estAttribuee)
                                <span style="background: #e0f2fe; color: #0369a1; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: bold;">Actif</span>
                            @else
                                <span style="background: #f1f5f9; color: #94a3b8; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem;">Non enseignée</span>
                            @endif
                        </label>
                    @endforeach

                </div>

                <button type="submit" style="width: 100%; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem; transition: background 0.2s;">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>

        <div style="flex: 1.5; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #1e2937; font-size: 1.3rem; margin-top: 0; margin-bottom: 20px;">
                🎓 Élèves Inscrits ({{ $classe->eleves->count() }})
            </h3>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #cbd5e1; text-align: left; color: #475569;">
                        <th style="padding: 10px 0;">Matricule</th>
                        <th style="padding: 10px 0;">Nom & Prénoms</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classe->eleves as $eleve)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 0; color: #64748b;">{{ $eleve->matricule }}</td>
                        <td style="padding: 12px 0; font-weight: 600; color: #1e2937;">{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 20px 0; text-align: center; color: #94a3b8;">Aucun élève inscrit dans cette classe.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection