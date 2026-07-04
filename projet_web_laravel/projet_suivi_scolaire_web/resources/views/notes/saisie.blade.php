@extends('layouts.app')

@section('content')

<style>
  .notes-page {
    min-height: 100vh;
    background-color: white;
    padding: 1.5rem;
  }

  /* Alert */
  .alert-success {
    max-width: 80rem;
    margin: 0 auto 1.5rem;
    padding: 1rem;
    background-color: #dcfce7;
    border-left: 4px solid #22c55e;
    color: #15803d;
    border-radius: 0.375rem;
    font-weight: 500;
  }

  /* Title */
  .notes-page > h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  /* Filter box */
  .filter-box {
    margin-bottom: 1.5rem;
    background-color: #f9fafb;
    padding: 1rem;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
  }

  .filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  @media (max-width: 640px) {
    .filter-grid { grid-template-columns: 1fr; }
    .notes-page  { padding: 1rem; }
  }

  .filter-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
  }

  .form-control {
    width: 100%;
    border: 1px solid #d1d5db;
    padding: 0.75rem;
    border-radius: 0.375rem;
    background-color: white;
    font-size: 1rem;
    box-sizing: border-box;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: border-color 0.2s;
  }

  .form-control:focus {
    outline: none;
    border-color: #2563eb;
  }

  /* Notes table */
  .table-wrapper { overflow-x: auto; }

  .notes-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    table-layout: fixed;
  }

  .notes-table thead {
    background-color: #f9fafb;
  }

  .notes-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    color: #374151;
  }

  .notes-table th.col-matiere {
    text-align: center;
    background-color: #eff6ff;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
  }

  .notes-table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
  }

  .notes-table tbody tr:hover { background-color: #f9fafb; }

  .notes-table td {
    padding: 0.75rem 1rem;
  }

  .notes-table td.col-note {
    text-align: center;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
    background-color: rgba(249, 250, 251, 0.5);
  }

  .td-mono {
    font-family: monospace;
    font-size: 0.875rem;
    color: #4b5563;
  }

  .td-name {
    font-weight: 500;
    color: #111827;
  }

  /* Note input */
  .input-note {
    width: 5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.25rem 0.5rem;
    text-align: center;
    font-size: 0.9rem;
    transition: border-color 0.2s;
  }

  .input-note:focus {
    outline: none;
    border-color: #2563eb;
  }

  /* Submit row */
  .submit-row {
    display: flex;
    justify-content: flex-end;
  }

  .btn-save {
    margin-top: 1.5rem;
    background-color: #16a34a;
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
    padding: 1rem 2.5rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: background-color 0.2s;
  }

  .btn-save:hover { background-color: #15803d; }

  /* Empty state */
  .alert-warning {
    background-color: #fefce8;
    border-left: 4px solid #facc15;
    padding: 1rem;
    border-radius: 0.375rem;
    color: #a16207;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
  }
</style>

<div class="notes-page">

  @if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
  @endif

  <h1>Saisie des Notes</h1>

  {{-- ── Filtres ── --}}
  <div class="filter-box">
    <form method="GET" action="{{ route('notes.saisie') }}" id="form-selection">
      <div class="filter-grid">

        <div class="filter-group">
          <label>Classe</label>
          <select name="classe_id"
                  onchange="document.getElementById('form-selection').submit()"
                  class="form-control">
            <option value="">-- Sélectionner une classe (CP1 au CM2) --</option>
            @foreach(['CP1','CP2','CE1','CE2','CM1','CM2'] as $nom)
              @foreach($classes as $c)
                @if($c->nom == $nom)
                  <option value="{{ $c->id }}"
                    {{ isset($classe_id) && $classe_id == $c->id ? 'selected' : '' }}>
                    {{ $nom }}
                  </option>
                @endif
              @endforeach
            @endforeach
          </select>
        </div>

        <div class="filter-group">
          <label>Trimestre</label>
          <select name="trimestre"
                  onchange="document.getElementById('form-selection').submit()"
                  class="form-control">
            <option value="">-- Sélectionner un trimestre --</option>
            @foreach(['Trimestre 1','Trimestre 2','Trimestre 3'] as $t)
              <option value="{{ $t }}" {{ $trimestre == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>

      </div>
    </form>
  </div>

  {{-- ── Tableau des notes ── --}}
  @if(isset($eleves) && $eleves->isNotEmpty())
    <form method="POST" action="{{ route('notes.storeMultiple') }}" id="notesForm">
      @csrf
      <input type="hidden" name="trimestre"  value="{{ $trimestre }}">
      <input type="hidden" name="classe_id"  value="{{ $classe_id }}">

      <div class="table-wrapper">
        <table class="notes-table">
          <thead>
            <tr>
              <th>Matricule</th>
              <th>Élève</th>
              @foreach($matieres as $matiere)
                <th class="col-matiere">{{ $matiere->nom }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($eleves as $eleve)
              <tr>
                <td class="td-mono">{{ $eleve->matricule }}</td>
                <td class="td-name">{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                @foreach($matieres as $matiere)
                  <td class="col-note">
                    <input type="hidden"
                           name="notes[{{ $eleve->id }}_{{ $matiere->id }}][eleve_id]"
                           value="{{ $eleve->id }}">
                    <input type="hidden"
                           name="notes[{{ $eleve->id }}_{{ $matiere->id }}][matiere_id]"
                           value="{{ $matiere->id }}">
                    <input type="number" step="0.25" min="0" max="20"
                           name="notes[{{ $eleve->id }}_{{ $matiere->id }}][note]"
                           class="input-note">
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="submit-row">
        <button type="submit" class="btn-save">
          💾 Enregistrer les Notes du {{ $trimestre }}
        </button>
      </div>
    </form>

  @elseif(isset($classe_id) && $classe_id != '')
    <div class="alert-warning">
      💡 Aucun élève n'est encore inscrit dans cette classe pour le moment.
    </div>
  @endif

</div>

@endsection