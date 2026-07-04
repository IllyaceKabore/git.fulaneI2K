@extends('layouts.app')

@section('content')
<div style="max-width: 1280px; margin: 0 auto; padding: 20px 20px 40px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #1f2937;">👨‍🏫 Liste des Enseignants</h1>
        
        <a href="{{ route('enseignants.create') }}" 
           style="background: #10b981; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
            ➕ Ajouter un Enseignant
        </a>
    </div>

    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px rgba(0,0,0,0.08);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #1f2937; color: white;">
                <tr>
                    <th style="padding: 18px 20px; text-align: left;">Nom & Prénom</th>
                    <th style="padding: 18px 20px; text-align: left;">Adresse Email</th>
                    <th style="padding: 18px 20px; text-align: center;">Classe Assignée</th>
                    <th style="padding: 18px 20px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enseignants as $enseignant)
                <tr style="border-top: 1px solid #e5e7eb;">
                    <td style="padding: 18px 20px; font-weight: 500;">{{ $enseignant->name }}</td>
                    <td style="padding: 18px 20px; color: #4b5563;">{{ $enseignant->email }}</td>
                    <td style="padding: 18px 20px; text-align: center;">
                        @if($enseignant->classeGeree ?? $enseignant->classe)
                            <span style="background: #dbeafe; color: #1e40af; padding: 6px 14px; border-radius: 9999px; font-size: 0.9rem;">
                                {{ $enseignant->classeGeree->nom ?? $enseignant->classe->nom }}
                            </span>
                        @else
                            <span style="color: #9ca3af;">— Aucune classe —</span>
                        @endif
                    </td>
                    <td style="padding: 18px 20px; text-align: center;">
                        <a href="{{ route('enseignants.edit', $enseignant) }}" 
                           style="color: #eab308; margin-right: 15px; text-decoration: none; font-weight: 500;">
                            ✏️ Modifier
                        </a>

                        <form action="{{ route('enseignants.destroy', $enseignant) }}" method="POST" 
                              style="display: inline;" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ? Cette action est irréversible.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="color: #ef4444; background: none; border: none; cursor: pointer; font-weight: 500;">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection