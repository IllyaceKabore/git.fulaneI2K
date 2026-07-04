@extends('layouts.app')

@section('content')

<style>
  .notes-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem 1rem;
  }

  /* Header */
  .notes-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .notes-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .btn-saisir {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #16a34a;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: background-color 0.2s;
  }

  .btn-saisir:hover {
    background-color: #15803d;
  }

  /* Alert */
  .alert-success {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #dcfce7;
    border-left: 4px solid #22c55e;
    color: #15803d;
    border-radius: 0.375rem;
    font-weight: 500;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  }

  /* Card */
  .table-card {
    background-color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
    overflow: hidden;
    border: 1px solid #e5e7eb;
  }

  .table-wrapper {
    overflow-x: auto;
  }

  /* Table */
  .notes-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 0.875rem;
    color: #6b7280;
  }

  .notes-table thead {
    background-color: #f3f4f6;
    border-bottom: 1px solid #e5e7eb;
  }

  .notes-table th {
    padding: 1rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #374151;
    letter-spacing: 0.05em;
  }

  .notes-table th.col-center {
    text-align: center;
  }

  .notes-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.15s;
  }

  .notes-table tbody tr:hover {
    background-color: #f9fafb;
  }

  .notes-table td {
    padding: 1rem 1.5rem;
  }

  .notes-table td.col-center {
    text-align: center;
  }

  /* Cell content */
  .eleve-nom {
    font-weight: 600;
    color: #111827;
  }

  .eleve-matricule {
    font-size: 0.75rem;
    color: #9ca3af;
    font-family: monospace;
  }

  .td-medium {
    font-weight: 500;
    color: #374151;
  }

  .badge-trimestre {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background-color: #eff6ff;
    color: #1d4ed8;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .note-value {
    font-weight: 700;
    font-size: 1rem;
  }

  .note-ok   { color: #16a34a; }
  .note-fail { color: #ef4444; }

  /* Modifier button */
  .btn-modifier {
    display: inline-block;
    background-color: #fef2f2;
    color: #dc2626;
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.625rem;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
  }

  .btn-modifier:hover {
    background-color: #fee2e2;
    color: #7f1d1d;
  }

  /* Empty state */
  .empty-row td {
    padding: 3rem 1.5rem;
    text-align: center;
    color: #9ca3af;
    font-weight: 500;
  }

  /* Pagination */
  .pagination-wrapper {
    padding: 1rem 1.5rem;
    background-color: #f9fafb;
    border-top: 1px solid #e5e7eb;
  }
</style>

<div class="notes-container">

  <div class="notes-header">
    <div>
      <h2>Historique des Notes</h2>
    </div>
    <a href="{{ route('notes.saisie') }}" class="btn-saisir">
      📝 Saisir de nouvelles notes
    </a>
  </div>

  @if(session('success'))
    <div class="alert-success">
      ✅ {{ session('success') }}
    </div>
  @endif

  <div class="table-card">
    <div class="table-wrapper">
      <table class="notes-table">
        <thead>
          <tr>
            <th>Élève</th>
            <th>Classe</th>
            <th>Matière</th>
            <th>Trimestre</th>
            <th class="col-center">Note / 20</th>
            <th class="col-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notes as $note)
            <tr>
              <td>
                <div class="eleve-nom">
                  {{ $note->eleve->nom ?? 'Élève inconnu' }} {{ $note->eleve->prenom ?? '' }}
                </div>
                <div class="eleve-matricule">
                  {{ $note->eleve->matricule ?? '-' }}
                </div>
              </td>
              <td class="td-medium">{{ $note->eleve->classe->nom ?? '-' }}</td>
              <td class="td-medium">{{ $note->matiere->nom ?? 'Matière inconnue' }}</td>
              <td>
                <span class="badge-trimestre">{{ $note->trimestre }}</span>
              </td>
              <td class="col-center">
                <span class="note-value {{ $note->note >= 10 ? 'note-ok' : 'note-fail' }}">
                  {{ number_format($note->note, 2, ',', ' ') }}
                </span>
              </td>
              <td class="col-center">
                <form action="{{ route('notes.edit', $note->id) }}" method="POST">
                  @csrf
                  @method('GET')
                  <button type="submit" class="btn-modifier">Modifier</button>
                </form>
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="6">
                📋 Aucune note n'a encore été enregistrée dans la base de données.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($notes->hasPages())
      <div class="pagination-wrapper">
        {{ $notes->links() }}
      </div>
    @endif
  </div>

</div>

@endsection