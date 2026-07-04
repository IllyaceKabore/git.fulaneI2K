@extends('layouts.app')

@section('content')

<style>
  .form-card {
    max-width: 42rem;
    margin: 2.5rem auto 0;
    background-color: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .form-card h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 2rem;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .form-control {
    width: 100%;
    border: 2px solid #d1d5db;
    padding: 1rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    box-sizing: border-box;
    transition: border-color 0.2s;
  }

  .form-control:focus {
    outline: none;
    border-color: #059669;
  }

  .form-control-lg {
    font-size: 1.5rem;
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }

  .btn-submit {
    margin-top: 2.5rem;
    width: 100%;
    background-color: #059669;
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    padding: 1.25rem;
    border: none;
    border-radius: 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .btn-submit:hover {
    background-color: #047857;
  }
</style>

<div class="form-card">
  <h1>Enregistrer un Versement</h1>

  <form method="POST" action="{{ route('versements.store') }}">
    @csrf

    <div class="form-group">
      <label>Élève</label>
      <select name="eleve_id" class="form-control" required>
        <option value="">-- Sélectionner un élève --</option>
        @foreach($eleves as $eleve)
          <option value="{{ $eleve->id }}">
            {{ $eleve->matricule }} - {{ $eleve->nom }} {{ $eleve->prenom }}
            ({{ $eleve->classe->nom ?? '' }})
          </option>
        @endforeach
      </select>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label>Montant (FCFA)</label>
        <input type="number" name="montant"
               class="form-control form-control-lg" required>
      </div>
      <div class="form-group">
        <label>Date du Versement</label>
        <input type="date" name="date_versement" value="{{ date('Y-m-d') }}"
               class="form-control" required>
      </div>
    </div>

    <div class="form-group">
      <label>Mode de Paiement</label>
      <select name="mode_paiement" class="form-control" required>
        <option value="especes">Espèces</option>
        <option value="mobile_money">Mobile Money</option>
        <option value="banque">Virement Bancaire</option>
        <option value="autre">Autre</option>
      </select>
    </div>

    <div class="form-group">
      <label>Trimestre concerné</label>
      <select name="trimestre" class="form-control">
        <option value="1">1er Trimestre</option>
        <option value="2">2ème Trimestre</option>
        <option value="3">3ème Trimestre</option>
      </select>
    </div>

    <div class="form-group">
      <label>Observation</label>
      <textarea name="observation" rows="3" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn-submit">
      Enregistrer le Versement
    </button>
  </form>
</div>

@endsection