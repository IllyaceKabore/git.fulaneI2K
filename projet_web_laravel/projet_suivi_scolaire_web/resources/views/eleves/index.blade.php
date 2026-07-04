@extends('layouts.app')

@section('content')
<div style="max-width: 1280px; margin: 0 auto; padding: 20px 20px 40px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #1f2937;">👨‍🎓 Liste des Élèves</h1>
        
        <a href="{{ route('eleves.create') }}" 
           style="background: #2563eb; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600;">
            + Nouvel Élève
        </a>
    </div>

    <!-- Filtre par classe -->
    <div style="margin-bottom: 25px;">
        <form method="GET" action="{{ route('eleves.index') }}" style="display: flex; gap: 12px; align-items: center;">
            <select name="classe_id" onchange="this.form.submit()" 
                    style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 1rem; min-width: 280px;">
                <option value="">Toutes les classes</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->nom }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px rgba(0,0,0,0.08);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #1f2937; color: white;">
                <tr>
                    <th style="padding: 18px 20px; text-align: left;">Matricule</th>
                    <th style="padding: 18px 20px; text-align: center; width: 80px;">Photo</th>
                    <th style="padding: 18px 20px; text-align: left;">Nom Complet</th>
                    <th style="padding: 18px 20px; text-align: left;">Classe</th>
                    <th style="padding: 18px 20px; text-align: left;">Tuteur</th>
                    <th style="padding: 18px 20px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $eleve)
                <tr style="border-top: 1px solid #e5e7eb;">
                    <td style="padding: 18px 20px; font-family: monospace;">{{ $eleve->matricule }}</td>
                    
                    <td style="padding: 12px; text-align: center;">
                        @if($eleve->photo)
                            <img src="{{ Storage::url($eleve->photo) }}" 
                                 style="width: 52px; height: 52px; object-fit: cover; border-radius: 50%; border: 2px solid #e5e7eb;">
                        @else
                            <div style="width: 52px; height: 52px; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.5rem;">
                                👤
                            </div>
                        @endif
                    </td>

                    <td style="padding: 18px 20px; font-weight: 500;">{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                    <td style="padding: 18px 20px;">{{ $eleve->classe->nom ?? '—' }}</td>
                    <td style="padding: 18px 20px;">{{ $eleve->nom_tuteur ?? $eleve->tuteur ?? '—' }}</td>
                    
                    <td style="padding: 18px 20px; text-align: center;">
                        <a href="{{ route('eleves.show', $eleve) }}" style="color: #2563eb; margin-right: 12px;">Voir</a>
                        <a href="{{ route('eleves.edit', $eleve) }}" style="color: #eab308; margin-right: 12px;">Modifier</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 25px; text-align: center;">
        {{ $eleves->links() }}
    </div>

</div>
@endsection