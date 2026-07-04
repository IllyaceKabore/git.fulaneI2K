@extends('layouts.app')

@section('content')
<style>
    /* Correction de l'arrière-plan global et alignement avec la sidebar */
    .p-wrapper {
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
        background-color: #f8f9fa; /* Fond clair uniforme pour éviter les reflets sombres */
        min-height: 100vh;
    }
    .p-container {
        display: flex;
        flex-wrap: wrap; /* Permet de passer à la ligne sur petit écran plutôt que de couper le texte */
        gap: 25px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        width: 100%;
        margin: 0 auto;
    }
    .p-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        overflow: hidden;
        flex: 1;
        min-width: 320px; /* Évite que la carte devienne trop étroite */
    }
    .p-card-form {
        flex: 0 0 350px; /* Largeur fixe et stable pour le formulaire de gauche */
    }
    .p-header-blue {
        background-color: #007bff;
        color: #ffffff;
        padding: 15px 20px;
        margin: 0;
        font-size: 1.1rem;
    }
    .p-header-dark {
        background-color: #212529;
        color: #ffffff;
        padding: 15px 20px;
        margin: 0;
        font-size: 1.1rem;
    }
    .p-body {
        padding: 20px;
    }
    .p-form-group {
        margin-bottom: 15px;
    }
    .p-form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }
    .p-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 0.95rem;
    }
    .p-control:focus {
        border-color: #007bff;
        outline: none;
    }
    .p-btn-primary {
        width: 100%;
        background-color: #007bff;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        font-size: 1rem;
    }
    .p-btn-primary:hover {
        background-color: #0056b3;
    }
    
    /* Table et conteneur responsive pour empêcher le texte de déborder */
    .p-table-responsive {
        width: 100%;
        overflow-x: auto; /* Ajoute une barre de défilement interne si l'écran est trop petit */
    }
    .p-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        white-space: nowrap; /* Empêche les retours à la ligne chaotiques dans les cellules */
    }
    .p-table th {
        background-color: #f8f9fa;
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
    }
    .p-table td {
        padding: 12px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    .p-inline-form {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    .p-btn-sm {
        padding: 8px 14px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: bold;
    }
    .p-btn-sm:hover {
        background-color: #218838;
    }
    .text-muted {
        color: #6c757d;
        font-size: 0.85rem;
    }

    /* Force l'affichage propre du texte de profil dans la sidebar */
    .sidebar, [class*="sidebar"] {
        color: #ffffff !important;
    }
    .sidebar .text-muted, [class*="sidebar"] .text-muted {
        color: #a0aec0 !important; /* Rend l'email bien visible */
    }
</style>

<div class="p-wrapper">
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
            ✅ {{ session('success') }}
        </div>
    @endif
    <div class="p-container">
        
        <div class="p-card p-card-form">
            <h5 class="p-header-blue">👤 Ajouter un Parent</h5>
            <form action="{{ route('parents.store') }}" method="POST" class="p-body">
                @csrf
                <div class="p-form-group">
                    <label for="nom">Nom de famille</label>
                    <input type="text" name="nom" id="nom" class="p-control" required placeholder="Ex: Ouedraogo">
                </div>
                <div class="p-form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="p-control" required placeholder="Ex: Adama">
                </div>
                <div class="p-form-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" name="email" id="email" class="p-control" required placeholder="Ex: parent@email.com">
                </div>
                <div class="p-form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" class="p-control" placeholder="Ex: +226 70 00 00 00">
                </div>
                <div class="p-form-group">
                    <label for="password">Mot de passe temporaire</label>
                    <input type="password" name="password" id="password" class="p-control" required placeholder="Minimum 6 caractères">
                </div>
                <button type="submit" class="p-btn-primary">Créer le compte</button>
            </form>
        </div>

        <div class="p-card">
            <h5 class="p-header-dark">👨‍👩‍👧‍👦 Comptes Parents & Familles</h5>
            <div class="p-body">
                @if($parents->isEmpty())
                    <p style="text-align: center; color: #6c757d;">Aucun parent enregistré pour le moment.</p>
                @else
                    <div class="p-table-responsive">
                        <table class="p-table">
                            <thead>
                                <tr>
                                    <th>Parent</th>
                                    <th>Contact</th>
                                    <th>Enfants rattachés</th>
                                    <th>Rattacher un enfant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parents as $parent)
                                <tr>
                                    <td>
                                        <strong>{{ $parent->nom }} {{ $parent->prenom }}</strong>
                                    </td>
                                    <td>
                                        <div>📧 {{ $parent->email }}</div>
                                        <div class="text-muted">📞 {{ $parent->telephone ?? 'Non renseigné' }}</div>
                                    </td>
                                    <td>
                                        <span style="background: #e9ecef; padding: 4px 10px; border-radius: 12px; font-weight: bold;">
                                            {{ $parent->enfants_count }} enfant(s)
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('parents.associer', $parent->id) }}" method="POST" class="p-inline-form">
                                            @csrf
                                            <select name="eleve_id" class="p-control" style="width: 200px; padding: 6px;" required>
                                                <option value="">Choisir un élève...</option>
                                                @foreach(\App\Models\Eleve::doesntHave('parents')->get() as $eleve)
                                                    <option value="{{ $eleve->id }}">{{ $eleve->nom }} {{ $eleve->prenom }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="p-btn-sm">Lier</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection