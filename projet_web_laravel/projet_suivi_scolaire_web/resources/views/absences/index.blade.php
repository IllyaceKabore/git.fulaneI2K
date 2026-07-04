@extends('layouts.app')

@section('content') {{-- CORRIGÉ : On utilise @section à la place de @block --}}
<style>
    .sc-container {
        display: flex;
        gap: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .sc-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        flex: 1;
    }
    .sc-card-form {
        max-width: 400px;
    }
    .sc-header-danger {
        background-color: #dc3545;
        color: #ffffff;
        padding: 15px 20px;
        margin: 0;
        font-size: 1.2rem;
    }
    .sc-header-dark {
        background-color: #212529;
        color: #ffffff;
        padding: 15px 20px;
        margin: 0;
        font-size: 1.2rem;
    }
    .sc-body {
        padding: 20px;
    }
    .sc-form-group {
        margin-bottom: 20px;
    }
    .sc-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }
    .sc-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 1rem; {{-- CORRIGÉ : Retrait du 'y' de 1yrem --}}
    }
    .sc-control:focus {
        border-color: #dc3545;
        outline: none;
    }
    .sc-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        cursor: pointer;
    }
    .sc-btn-danger {
        width: 100%;
        background-color: #dc3545;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        font-size: 1rem;
    }
    .sc-btn-danger:hover {
        background-color: #bd2130;
    }
    .sc-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .sc-table th {
        background-color: #f8f9fa;
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
    }
    .sc-table td {
        padding: 12px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    .sc-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .bg-success { background-color: #d4edda; color: #155724; }
    .bg-warning { background-color: #fff3cd; color: #856404; }
    
    .sc-btn-outline {
        background: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
    }
    .sc-btn-outline:hover {
        background-color: #dc3545;
        color: white;
    }
</style>

<div class="sc-container">
    <div class="sc-card sc-card-form">
        <h5 class="sc-header-danger">🔴 Signaler une Absence</h5>
        <form action="{{ route('absences.store') }}" method="POST" class="sc-body">
            @csrf
            <div class="sc-form-group">
                <label for="eleve_id">Élève</label>
                <select name="eleve_id" id="eleve_id" class="sc-control" required>
                    <option value="">Choisir un élève...</option>
                    @foreach($eleves as $eleve)
                        <option value="{{ $eleve->id }}">{{ $eleve->nom }} {{ $eleve->prenom }} ({{ $eleve->classe->nom ?? 'Sans classe' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="sc-form-group">
                <label for="date_absence">Date de l'absence</label>
                <input type="date" name="date_absence" id="date_absence" class="sc-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="sc-form-group">
                <label for="periode">Période</label>
                <select name="periode" id="periode" class="sc-control">
                    <option value="Journée">Journée entière</option>
                    <option value="Matinée">Matinée</option>
                    <option value="Après-midi">Après-midi</option>
                </select>
            </div>

            <div class="sc-form-group">
                <label for="motif">Motif / Raison (Optionnel)</label>
                <input type="text" name="motif" id="motif" class="sc-control" placeholder="Ex: Raison médicale...">
            </div>

            <label class="sc-checkbox">
                <input type="checkbox" name="justifie" value="1">
                Absence justifiée
            </label>

            <button type="submit" class="sc-btn-danger">Enregistrer l'absence</button>
        </form>
    </div>

    <div class="sc-card">
        <h5 class="sc-header-dark">📋 Historique Récent des Absences</h5>
        <div class="sc-body">
            @if($absences->isEmpty())
                <p style="text-align: center; color: #6c757d;">Aucune absence enregistrée pour le moment.</p>
            @else
                <table class="sc-table">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Date</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absences as $absence)
                        <tr>
                            <td><strong>{{ $absence->eleve->nom ?? 'Inconnu' }} {{ $absence->eleve->prenom ?? '' }}</strong></td>
                            <td>{{ $absence->eleve->classe->nom ?? 'Sans classe' }}</td>
                            <td>{{ date('d/m/Y', strtotime($absence->date_absence)) }}</td>
                            <td>{{ $absence->periode }}</td>
                            <td>
                                @if($absence->justifie)
                                    <span class="sc-badge bg-success">✅ Justifiée</span>
                                @else
                                    <span class="sc-badge bg-warning">⚠️ Non justifiée</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('absences.destroy', $absence->id) }}" method="POST" onsubmit="return confirm('Supprimer cette absence ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="sc-btn-outline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection {{-- CORRIGÉ : On ferme avec @endsection --}}