@extends('layouts.app')

@section('content')
<div style="max-width: 700px; margin: 30px auto; padding: 0 20px;">

    <div style="background: white; padding: 35px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">

        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 2.5rem;">👨‍🏫</span>
            <h1 style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin: 10px 0;">
                Modifier l'Enseignant
            </h1>
        </div>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 15px; border-radius: 12px; margin-bottom: 25px;">
                <ul style="list-style-type: disc; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('enseignants.update', $enseignant) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                <!-- Nom Complet -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Nom Complet</label>
                    <input type="text" name="name" value="{{ old('name', $enseignant->name) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 1.1rem;">
                </div>

                <!-- Email -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email', $enseignant->email) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Classe Assignée -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Classe Assignée</label>
                    <select name="classe_id" 
                            style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                        <option value="">-- Aucune classe assignée --</option>
                        @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" 
                            {{ old('classe_id', $enseignant->classe_id ?? '') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('enseignants.index') }}" 
                   style="padding: 14px 28px; background: #e5e7eb; color: #374151; border-radius: 12px; text-decoration: none; font-weight: 600;">
                    Annuler
                </a>
                <button type="submit" 
                        style="padding: 14px 32px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">
                    💾 Enregistrer les Modifications
                </button>
            </div>

        </form>
    </div>
</div>
@endsection