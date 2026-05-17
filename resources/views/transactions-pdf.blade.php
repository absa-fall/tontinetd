<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transactions — {{ $membre->nom }} {{ $membre->prenom }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1a1a2e; margin: 0; padding: 20px; }
        .header { background: #f0a500; padding: 20px; border-radius: 8px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 22px; color: #0a0e1a; }
        .header p { margin: 4px 0 0; font-size: 12px; color: #0a0e1a; }
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-item { display: table-cell; width: 50%; padding: 10px; background: #f5f5f5; border-radius: 6px; }
        .info-label { font-size: 10px; color: #666; text-transform: uppercase; }
        .info-value { font-size: 13px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #0a0e1a; color: #f0a500; padding: 10px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 9px 10px; border-bottom: 1px solid #eee; font-size: 12px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .section-title { font-size: 14px; font-weight: bold; margin: 20px 0 8px; color: #0a0e1a; border-left: 4px solid #f0a500; padding-left: 10px; }
        .total { text-align: right; font-size: 14px; font-weight: bold; color: #0a0e1a; margin-top: 10px; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; }
        .badge-green { background: #d4f4e7; color: #1a7a4a; }
        .badge-yellow { background: #fef3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TontineTD — Relevé de transactions</h1>
        <p>Généré le {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Membre</div>
            <div class="info-value">{{ $membre->nom }} {{ $membre->prenom }}</div>
        </div>
        <div class="info-item" style="padding-left: 20px;">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $membre->email }}</div>
        </div>
    </div>

    <!-- COTISATIONS -->
    <div class="section-title">Cotisations</div>
    @if($membre->cotisations->count() > 0)
    <table>
        <thead>
            <tr><th>Date</th><th>Montant</th></tr>
        </thead>
        <tbody>
            @foreach($membre->cotisations as $c)
            <tr>
                <td>{{ $c->date_cotisation }}</td>
                <td>{{ number_format($c->montant, 0, ',', ' ') }} F CFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">Total cotisations : {{ number_format($membre->cotisations->sum('montant'), 0, ',', ' ') }} F CFA</div>
    @else
    <p style="color:#999; font-size:12px;">Aucune cotisation enregistrée.</p>
    @endif

    <div class="footer">
        TontineTD — Document généré automatiquement — {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>