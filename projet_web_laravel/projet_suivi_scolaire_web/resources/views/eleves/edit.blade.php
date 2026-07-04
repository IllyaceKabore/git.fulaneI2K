@extends('layouts.app')

@section('content')
<div style="max-width: 700px; margin: 30px auto; padding: 0 20px;">

    <div style="background: white; padding: 35px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">

        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 2.5rem;">✏️</span>
            <h1 style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin: 10px 0;">Modifier l'Élève</h1>
            <p style="color: #6b7280;">Mise à jour des informations de l'élève</p>
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

        <form action="{{ route('eleves.update', $eleve) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                <!-- Nom -->
                <div>
                    <label class="form-label">Nom de l'élève</label>
                    <input type="text" name="nom" value="{{ old('nom', $eleve->nom) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Prénom -->
                <div>
                    <label class="form-label">Prénom de l'élève</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $eleve->prenom) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Date de naissance -->
                <div>
                    <label class="form-label">Date de Naissance</label>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance', $eleve->date_naissance) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Sexe -->
                <div>
                    <label class="form-label">Sexe</label>
                    <select name="sexe" required style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                        <option value="M" {{ old('sexe', $eleve->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe', $eleve->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>

                <!-- Classe -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Classe Assignée</label>
                    <select name="classe_id" required style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                        <option value="">-- Choisir une classe --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id', $eleve->classe_id) == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tuteur -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Nom du Tuteur / Parent</label>
                    <input type="text" name="tuteur" value="{{ old('tuteur', $eleve->tuteur) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Photo Actuelle + Upload -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Photo de l'élève</label>
                    
                    @if($eleve->photo)
                    <div style="margin-bottom: 15px;">
                        <img src="{{ asset('storage/' . $eleve->photo) }}" 
                             alt="Photo actuelle" 
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 3px solid #e5e7eb;">
                    </div>
                    @endif

                    <input type="file" name="photo" accept="image/jpeg,image/png"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 12px;">
                    <small style="color: #6b7280;">Formats acceptés : JPG, PNG (max 2 Mo)</small>
                </div>

            </div>

            <div style="margin-top: 35px; display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('eleves.index') }}" 
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