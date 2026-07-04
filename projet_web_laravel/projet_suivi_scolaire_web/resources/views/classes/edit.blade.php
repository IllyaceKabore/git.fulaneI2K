@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 30px auto; padding: 0 20px;">

    <div style="background: white; padding: 35px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">

        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 2.5rem;">🏫</span>
            <h1 style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin: 10px 0;">
                Modifier la Classe : {{ $classe->nom }}
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

        <form action="{{ route('classes.update', $classe) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                <!-- Nom de la classe -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Nom de la Classe</label>
                    <input type="text" name="nom" value="{{ old('nom', $classe->nom) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 1.1rem;">
                </div>

                <!-- Capacité -->
                <div>
                    <label class="form-label">Capacité Maximale</label>
                    <input type="number" name="capacite_max" value="{{ old('capacite_max', $classe->capacite_max) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Frais de scolarité -->
                <div>
                    <label class="form-label">Frais de Scolarité (FCFA)</label>
                    <input type="number" name="frais_scolarite" value="{{ old('frais_scolarite', $classe->frais_scolarite) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Année scolaire -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Année Scolaire</label>
                    <input type="text" name="annee_scolaire" value="{{ old('annee_scolaire', $classe->annee_scolaire) }}" required
                           style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                </div>

                <!-- Enseignant Principal -->
                <div style="grid-column: span 2;">
                    <label class="form-label">Enseignant Principal</label>
                    <select name="enseignant_id" 
                            style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 12px;">
                        <option value="">-- Aucun enseignant assigné --</option>
                        @foreach($enseignants as $enseignant)
                        <option value="{{ $enseignant->id }}" 
                            {{ old('enseignant_id', $classe->enseignant_id) == $enseignant->id ? 'selected' : '' }}>
                            {{ $enseignant->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Matières -->
                <div style="grid-column: span 2;">
                    <label class="form-label" style="margin-bottom: 12px;">Matières Enseignées</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        @foreach($matieres as $matiere)
                        <label style="background: #f8fafc; padding: 14px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" 
                                   name="matieres[]" 
                                   value="{{ $matiere->id }}"
                                   {{ $classe->matieres->contains($matiere->id) ? 'checked' : '' }}
                                   style="width: 18px; height: 18px;">
                            <span>{{ $matiere->nom }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('classes.index') }}" 
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