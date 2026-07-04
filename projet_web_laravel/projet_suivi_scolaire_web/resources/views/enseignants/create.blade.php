@extends('layouts.app')

@section('content')

<style>
  .enseignant-card {
    max-width: 32rem;
    margin: 2.5rem auto 0;
    background-color: white;
    border-radius: 1.5rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    padding: 2rem;
  }

  /* Header */
  .enseignant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }

  .enseignant-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
  }

  .link-retour {
    color: #6b7280;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.2s;
  }

  .link-retour:hover { color: #374151; }

  /* Form */
  .form-stack {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
  }

  .form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }

  .form-control {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    color: #111827;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
  }

  .btn-submit {
    width: 100%;
    background-color: #2563eb;
    color: white;
    padding: 1.25rem;
    border: none;
    border-radius: 1rem;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1rem;
    transition: background-color 0.2s;
  }

  .btn-submit:hover { background-color: #1d4ed8; }
</style>

<div class="enseignant-card">

  <div class="enseignant-header">
    <h1>Ajouter un Nouvel Enseignant</h1>
    <a href="{{ route('enseignants.index') }}" class="link-retour">← Retour à la liste</a>
  </div>

  <form method="POST" action="{{ route('enseignants.store') }}">
    @csrf
    <div class="form-stack">

      <div class="form-group">
        <label>Nom Complet</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Adresse Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
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

      <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: flex-end;">
          <button type="submit" 
                  style="padding: 14px 32px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">
              🚀 Enregistrer l'Enseignant
          </button>
      </div>

    </div>
  </form>
</div>

@endsection