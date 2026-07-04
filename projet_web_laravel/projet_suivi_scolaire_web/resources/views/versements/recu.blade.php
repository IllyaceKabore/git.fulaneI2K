<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement - {{ $versement->reference_recu }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .amount {
            font-size: 28px;
            font-weight: bold;
            color: #166534;
            text-align: center;
            padding: 20px;
            border: 3px solid #166534;
            margin: 20px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">ÉCOLE PRIMAIRE KOUDOUBI KABORE</div>
        <p>Ouagadougou, Burkina Faso</p>
    </div>

    <div class="title">REÇU DE PAIEMENT</div>

    <div class="info">
        <div>
            <strong>Référence :</strong> {{ $versement->reference_recu }}<br>
            <strong>Date :</strong> {{ \Carbon\Carbon::parse($versement->date_versement)->format('d/m/Y') }}
        </div>
        <div style="text-align: right;">
            <strong>Trimestre :</strong> {{ $versement->trimestre }}<sup>e</sup> Trimestre<br>
            <strong>Encaissé par :</strong> {{ $versement->user->name }}
        </div>
    </div>

    <table>
        <tr>
            <th>Élève</th>
            <td>{{ $versement->eleve->nom }} {{ $versement->eleve->prenom }}</td>
        </tr>
        <tr>
            <th>Matricule</th>
            <td>{{ $versement->eleve->matricule }}</td>
        </tr>
        <tr>
            <th>Classe</th>
            <td>{{ $versement->eleve->classe->nom ?? '—' }}</td>
        </tr>
        <tr>
            <th>Tuteur</th>
            <td>{{ $versement->eleve->nom_tuteur }}</td>
        </tr>
    </table>

    <div class="amount">
        {{ number_format($versement->montant, 0, ',', ' ') }} FCFA
    </div>

    <p><strong>Mode de paiement :</strong> {{ ucfirst(str_replace('_', ' ', $versement->mode_paiement)) }}</p>
    
    @if($versement->observation)
        <p><strong>Observation :</strong> {{ $versement->observation }}</p>
    @endif

    <div class="footer">
        <p>Merci pour votre paiement • Ce reçu est généré automatiquement</p>
        <p>École Primaire KOUDOUBI KABORE • Tous droits réservés © 2026</p>
    </div>

    <div class="signature">
        <p>Signature du Gestionnaire</p>
            <p>________________</p>
    </div>
</body>
</html>