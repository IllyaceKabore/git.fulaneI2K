@extends('layouts.app')

@section('content')

<style>
  .versements-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
  }

  .versements-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .versements-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
  }

  .btn-success {
    background-color: #059669;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
  }

  .btn-success:hover {
    background-color: #047857;
  }

  .versements-table {
    width: 100%;
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border-collapse: collapse;
  }

  .versements-table thead {
    background-color: #1f2937;
    color: white;
  }

  .versements-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
  }

  .versements-table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
  }

  .versements-table tbody tr:hover {
    background-color: #f9fafb;
  }

  .versements-table td {
    padding: 1rem 1.5rem;
  }

  .td-mono {
    font-family: monospace;
  }

  .td-bold {
    font-weight: 700;
  }

  .link-recu {
    color: #2563eb;
    text-decoration: none;
  }

  .link-recu:hover {
    text-decoration: underline;
  }
</style>

<div class="versements-container">
  <div class="versements-header">
    <h1>Historique des Versements</h1>
    <a href="{{ route('versements.create') }}" class="btn-success">+ Nouveau Versement</a>
  </div>

  <table class="versements-table">
    <thead>
      <tr>
        <th>Référence</th>
        <th>Élève</th>
        <th>Montant</th>
        <th>Date</th>
        <th>Mode</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($versements as $versement)
      <tr>
        <td class="td-mono">{{ $versement->reference_recu }}</td>
        <td>{{ $versement->eleve->nom }} {{ $versement->eleve->prenom }}</td>
        <td class="td-bold">{{ number_format($versement->montant) }} FCFA</td>
        <td>{{ $versement->date_versement }}</td>
        <td>{{ ucfirst(str_replace('_', ' ', $versement->mode_paiement)) }}</td>
        <td>
          <a href="{{ route('versements.recu', $versement) }}" class="link-recu" target="_blank">
            📄 Reçu PDF
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection