<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Global — TontineTD</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1a1a2e; margin: 0; padding: 20px; }
        .header { background: #0a0e1a; padding: 20px; border-radius: 8px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 22px; color: #f0a500; }
        .header p { margin: 4px 0 0; font-size: 12px; color: #aaa; }
        .stats { display: table; width: 100%; margin-bottom: 24px; }
        .stat { display: table-cell; text-align: center; background: #f5f5f5; padding: 14px; border-radius: 8px; }
        .stat-val { font-size: 24px; font-weight: bold; color: #f0a500; }
        .stat-label { font-size: 10px; color: #666; text-transform: uppercase; }
        .section-title { font-size: 14px; font-weight: bold; margin: 24px 0 8px; color: #0a0e1a; border-left: 4px solid #f0a500; padding-left: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #0a0e1a; color: #f0a500; padding: 9px 10px; text-align: left; font-size: 11px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TontineTD — Rapport Global</h1>
        <p>Généré le {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
    </div>

    <div class="stats">
        <div class="stat"><div class="stat-val">{{ $membres->count() }}</div><div class="stat-label">Membres</div></div>
        <div class="stat"><div class="stat-val">{{ $tontines->count() }}</div><div class="stat-label">Tontines</div></div>
        <div class="stat"><div class="stat-val">{{ $cotisations->count() }}</div><div class="stat-label">Cotisations</div></div>
        <div class="stat"><div class="stat-val">{{ number_format($cotisations->sum('montant'), 0, ',', ' ') }} F</div><div class="stat-label">Total collecté</div></div>
    </div>

    <div class="section-title">Membres</div>
    <table>
        <thead><tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Nb Cotisations</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($membres as $m)
            <tr>
                <td>{{ $m->nom }}</td><td>{{ $m->prenom }}</td><td>{{ $m->email }}</td>
                <td>{{ $m->telephone }}</td>
                <td>{{ $m->cotisations->count() }}</td>
                <td>{{ number_format($m->cotisations->sum('montant'), 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Tontines</div>
    <table>
        <thead><tr><th>Nom</th><th>Montant</th><th>Début</th><th>Fin</th><th>Fréquence</th></tr></thead>
        <tbody>
            @foreach($tontines as $t)
            <tr>
                <td>{{ $t->nom }}</td>
                <td>{{ number_format($t->montant, 0, ',', ' ') }} F</td>
                <td>{{ $t->date_debut }}</td><td>{{ $t->date_fin }}</td>
                <td>{{ $t->frequence }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Tours</div>
    <table>
        <thead><tr><th>Date</th><th>État</th></tr></thead>
        <tbody>
            @foreach($tours as $t)
            <tr>
                <td>{{ $t->date_tour }}</td>
                <td>{{ $t->etat === 'terminer' ? 'Terminé' : 'En attente' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">TontineTD — Rapport généré automatiquement — {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
</body>
</html>