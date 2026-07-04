@extends('layouts.app')

@section('content') {{-- ✅ CORRIGÉ : Utilisation de @section au lieu de @block --}}
<style>
    .sc-wrap {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .sc-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .sc-btn-primary {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }
    .sc-btn-primary:hover { background-color: #0056b3; }
    
    /* Grille d'annonces */
    .sc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
        gap: 20px;
    }
    .sc-annonce-card {
        background: white;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        border-left: 5px solid #28a745; /* Par défaut général */
        position: relative;
    }
    .border-examen { border-left-color: #dc3545; }
    .border-reunion { border-left-color: #007bff; }
    .border-paiement { border-left-color: #ffc107; }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .type-tag {
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 4px;
        background: #f1f3f5;
    }

    /* Styles pour les champs du formulaire dans la Modal */
    .sc-form-group {
        margin-bottom: 15px;
    }
    .sc-form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }
    .sc-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 1rem;
        box-sizing: border-box;
    }
    .sc-control:focus {
        border-color: #80bdff;
        outline: none;
    }

    /* CSS pour la fenêtre Modal */
    .sc-modal {
        display: none; 
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 1050; /* Augmenté pour passer au-dessus de la barre latérale */
    }
    .sc-modal-content {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 8px;
        overflow: hidden;
        animation: fadeIn 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .modal-header {
        background: #007bff;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .close-btn {
        background: transparent;
        color: white;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }
    @keyframes fadeIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<div class="sc-wrap">
    <div class="sc-topbar">
        <h2>📢 Communication & Annonces Scolaires</h2>
        <button class="sc-btn-primary" onclick="toggleModal(true)">⚡ Publier une annonce</button>
    </div>

    <div class="sc-grid">
        @if($annonces->isEmpty())
            <p style="grid-column: 1/-1; text-align: center; color: #6c757d; padding: 40px 0;">Aucune annonce publiée pour le moment.</p>
        @else
            @foreach($annonces as $annonce)
                <div class="sc-annonce-card border-{{ $annonce->type }}">
                    <div class="card-meta">
                        <h4 style="margin:0;">{{ $annonce->titre }}</h4>
                        <span class="type-tag">{{ $annonce->type }}</span>
                    </div>
                    <p style="color: #6c757d; font-size: 0.85rem; margin-bottom: 15px;">
                        Publié le {{ $annonce->created_at->format('d/m/Y à H:i') }}
                    </p>
                    <p style="line-height: 1.5; color: #333;">{{ $annonce->contenu }}</p>
                    
                    @if($annonce->date_evenement)
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; font-size: 0.9rem; margin-top: 10px;">
                            📅 <strong>Date de l'événement :</strong> {{ date('d/m/Y', strtotime($annonce->date_evenement)) }}
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Fenêtre Modal --}}
<div class="sc-modal" id="annonceModal">
    <div class="sc-modal-content">
        <div class="modal-header">
            <h3 style="margin:0;">Créer une annonce</h3>
            <button class="close-btn" onclick="toggleModal(false)">&times;</button>
        </div>
        <form action="{{ route('annonces.store') }}" method="POST" style="padding: 20px;">
            @csrf
            <div class="sc-form-group">
                <label>Titre de l'annonce</label>
                <input type="text" name="titre" class="sc-control" required placeholder="Ex: Réunion de parents">
            </div>
            <div class="sc-form-group">
                <label>Catégorie</label>
                <select name="type" class="sc-control">
                    <option value="generale">Générale</option>
                    <option value="reunion">Réunion</option>
                    <option value="examen">Examens</option>
                    <option value="paiement">Rappel de Paiement</option>
                </select>
            </div>
            <div class="sc-form-group">
                <label>Date prévue (Optionnel)</label>
                <input type="date" name="date_evenement" class="sc-control">
            </div>
            <div class="sc-form-group">
                <label>Message / Description</label>
                <textarea name="contenu" rows="4" class="sc-control" required placeholder="Écrivez votre message..."></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="sc-control" style="width:auto; cursor:pointer;" onclick="toggleModal(false)">Annuler</button>
                <button type="submit" class="sc-btn-primary">Diffuser</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(show) {
        document.getElementById('annonceModal').style.display = show ? 'flex' : 'none';
    }
</script>
@endsection {{-- ✅ CORRIGÉ : @endsection au lieu de @endblock --}}