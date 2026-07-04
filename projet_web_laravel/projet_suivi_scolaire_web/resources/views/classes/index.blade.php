@extends('layouts.app')

@section('content')

<style>
  .classes-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
  }

  /* Header */
  .classes-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }

  .classes-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
  }

  .btn-new {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #2563eb;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.2s;
  }

  .btn-new:hover {
    background-color: #1d4ed8;
  }

  /* Card */
  .table-card {
    background-color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
    overflow: hidden;
  }

  /* Table */
  .classes-table {
    width: 100%;
    border-collapse: collapse;
  }

  .classes-table thead {
    background-color: #1f2937;
    color: white;
  }

  .classes-table th {
    padding: 1rem 1.5rem;
    font-weight: 600;
  }

  .classes-table th.col-left   { text-align: left; }
  .classes-table th.col-center { text-align: center; }
  .classes-table th.col-right  { text-align: right; }

  .classes-table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
  }

  .classes-table tbody tr:hover {
    background-color: #f9fafb;
  }

  .classes-table td {
    padding: 1.25rem 1.5rem;
  }

  .td-left   { text-align: left; }
  .td-center { text-align: center; }
  .td-right  { text-align: right; }

  .td-semibold { font-weight: 600; }
  .td-medium   { font-weight: 500; }

  /* Badge */
  .badge-eleves {
    display: inline-block;
    background-color: #dbeafe;
    color: #1d4ed8;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
  }

  /* Actions */
  .link-voir {
    color: #2563eb;
    text-decoration: none;
    margin: 0 0.5rem;
  }

  .link-voir:hover {
    text-decoration: underline;
  }

  .link-modifier {
    color: #d97706;
    text-decoration: none;
    margin: 0 0.5rem;
  }

  .link-modifier:hover {
    text-decoration: underline;
  }

  /* Empty state */
  .empty-row td {
    padding: 3rem 1.5rem;
    text-align: center;
    color: #6b7280;
  }
</style>

<div class="classes-container">

  <div class="table-card">
    <table class="classes-table">
      <thead>
        <tr>
          <th class="col-left">Nom de la Classe</th>
          <th class="col-center">Capacité</th>
          <th class="col-right">Frais de Scolarité</th>
          <th class="col-center">Nombre d'Élèves</th>
          <th class="col-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($classes as $classe)
          <tr>
            <td class="td-left td-semibold">{{ $classe->nom }}</td>
            <td class="td-center">{{ $classe->capacite_max }}</td>
            <td class="td-right td-medium">{{ number_format($classe->frais_scolarite) }} FCFA</td>
            <td class="td-center">
              <span class="badge-eleves">{{ $classe->eleves_count ?? 0 }} élèves</span>
            </td>
            <td class="td-center">
              <a href="{{ route('classes.show', $classe) }}" class="link-voir">Voir</a>
              <a href="{{ route('classes.edit', $classe) }}" class="link-modifier">Modifier</a>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="5">Aucune classe enregistrée pour le moment.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@endsection