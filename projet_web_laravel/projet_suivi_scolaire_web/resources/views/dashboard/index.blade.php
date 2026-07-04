@extends('layouts.app')

@section('content')

<style>
  .dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
  }

  .dashboard-container > h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 2rem;
  }

  /* ── Stat cards ── */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
  }

  .stat-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .stat-card h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.5rem;
  }

  .stat-value {
    font-size: 2.25rem;
    font-weight: 700;
    margin: 0;
  }

  .stat-blue    { color: #2563eb; }
  .stat-green   { color: #16a34a; }
  .stat-emerald { color: #059669; }
  .stat-red     { color: #dc2626; }

  /* ── Retard de paiement ── */
  .section {
    margin-top: 2.5rem;
  }

  .section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
  }

  .retard-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border: 1px solid #e5e7eb;
  }

  .retard-table thead tr {
    background-color: #f3f4f6;
  }

  .retard-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
  }

  .retard-table th.col-right { text-align: right; }

  .retard-table td {
    padding: 0.75rem 1rem;
    border-top: 1px solid #e5e7eb;
  }

  .retard-table td.col-right {
    text-align: right;
    font-weight: 700;
    color: #dc2626;
  }

  /* ── Classement ── */
  .classement-section {
    margin-top: 3rem;
  }

  .classement-section > h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  .classe-block {
    margin-bottom: 2.5rem;
    background-color: white;
    border-radius: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .classe-block-header {
    background: linear-gradient(to right, #2563eb, #4f46e5);
    color: white;
    padding: 1rem 1.5rem;
  }

  .classe-block-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
  }

  .classement-table {
    width: 100%;
    border-collapse: collapse;
  }

  .classement-table thead {
    background-color: #f9fafb;
  }

  .classement-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
  }

  .classement-table th.col-center { text-align: center; }
  .classement-table th.col-rang   { width: 4rem; }

  .classement-table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
  }

  .classement-table tbody tr:hover {
    background-color: #f9fafb;
  }

  .classement-table td {
    padding: 1rem 1.5rem;
  }

  .td-center  { text-align: center; }
  .td-mono    { font-family: monospace; }
  .td-medium  { font-weight: 500; }

  .rang-cell {
    text-align: center;
    font-weight: 700;
    font-size: 1.125rem;
  }

  .moyenne-value {
    font-weight: 700;
    font-size: 1.125rem;
  }

  .moy-excellent { color: #16a34a; }
  .moy-passable  { color: #2563eb; }
  .moy-faible    { color: #ea580c; }
</style>

<div class="dashboard-container">
  <h1>Tableau de Bord - Gestion Scolaire</h1>

  {{-- ── Stat cards ── --}}
  <div class="stats-grid">
    <div class="stat-card">
      <h3>Total Élèves</h3>
      <p class="stat-value stat-blue">{{ $totalEleves }}</p>
    </div>
    <div class="stat-card">
      <h3>Total Classes</h3>
      <p class="stat-value stat-green">{{ $totalClasses }}</p>
    </div>
    <div class="stat-card">
      <h3>Montant Collecté</h3>
      <p class="stat-value stat-emerald">{{ number_format($totalCollecte) }} FCFA</p>
    </div>
    <div class="stat-card">
      <h3>Élèves en Retard</h3>
      <p class="stat-value stat-red">{{ $elevesImpayes->count() }}</p>
    </div>
  </div>

  {{-- ── Retard de paiement ── --}}
  <div class="section">
    <h2>Élèves en retard de paiement</h2>
    <table class="retard-table">
      <thead>
        <tr>
          <th>Matricule</th>
          <th>Nom Complet</th>
          <th>Classe</th>
          <th class="col-right">Reste à Payer</th>
        </tr>
      </thead>
      <tbody>
        @foreach($elevesImpayes as $eleve)
          <tr>
            <td>{{ $eleve->matricule }}</td>
            <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
            <td>{{ $eleve->classe->nom ?? '-' }}</td>
            <td class="col-right">{{ number_format($eleve->resteAPayer()) }} FCFA</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- ── Classement par classe ── --}}
  <div class="classement-section">
    <h2>🏆 Classement des Élèves par Classe</h2>

    @foreach($classements as $classe)
      @if($classe->eleves->isNotEmpty())
        <div class="classe-block">
          <div class="classe-block-header">
            <h3>{{ $classe->nom }} ({{ $classe->eleves->count() }} élèves)</h3>
          </div>

          <table class="classement-table">
            <thead>
              <tr>
                <th class="col-rang col-center">Rang</th>
                <th>Matricule</th>
                <th>Nom & Prénom</th>
                <th class="col-center">Moyenne Générale</th>
              </tr>
            </thead>
            <tbody>
              @foreach($classe->eleves as $index => $eleve)
                <tr>
                  <td class="rang-cell">
                    @if($index == 0) 🥇
                    @elseif($index == 1) 🥈
                    @elseif($index == 2) 🥉
                    @else {{ $index + 1 }}
                    @endif
                  </td>
                  <td class="td-mono">{{ $eleve->matricule }}</td>
                  <td class="td-medium">{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                  <td class="td-center">
                    <span class="moyenne-value
                      {{ $eleve->moyenne >= 14 ? 'moy-excellent' : ($eleve->moyenne >= 10 ? 'moy-passable' : 'moy-faible') }}">
                      {{ number_format($eleve->moyenne, 2) }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    @endforeach
  </div>

</div>

@endsection