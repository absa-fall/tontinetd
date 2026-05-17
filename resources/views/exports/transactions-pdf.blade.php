<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transactions - {{ $membre->nom }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; }
        p { margin: 3px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f0a500; color: #fff; padding: 8px 10px; text-align: left; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .total { margin-top: 15px; text-align: right; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Relevé de transactions</h1>
    <p>Membre : <strong>{{ $membre->nom }} {{ $membre->prenom }}</strong></p>
    <p>Email : {{ $membre->email }}</p>
    <p>Date : {{ now()->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Montant</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membre->cotisations as $c)
            <tr>
                <td>#{{ $c->id }}</td>
                <td>{{ number_format($c->montant, 0, ',', ' ') }} F CFA</td>
                <td>{{ \Carbon\Carbon::parse($c->date_cotisation)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total cotisé : {{ number_format($membre->cotisations->sum('montant'), 0, ',', ' ') }} F CFA
    </div>
</body>
</html>