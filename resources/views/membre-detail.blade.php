<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Membre - {{ $membre->nom }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0e1a; --surface: #111827; --surface2: #1a2235; --border: #1f2d45; --accent: #f0a500; --accent2: #00c2a8; --text: #e8edf5; --muted: #6b7a99; --danger: #e05252; --success: #3ecf8e; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; padding: 2rem 2.5rem; }

        .back-btn { display: inline-block; margin-bottom: 1.5rem; padding: 0.5rem 1.2rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--muted); text-decoration: none; font-size: 0.85rem; }
        .back-btn:hover { color: var(--text); }

        .page-title { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; margin-bottom: 2rem; }
        .page-title span { color: var(--accent); }

        .info-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .info-card h2 { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; margin-bottom: 1.2rem; color: var(--accent); }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .info-item label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); display: block; margin-bottom: 0.3rem; }
        .info-item span { font-size: 0.95rem; font-weight: 500; }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 1.3rem 1.5rem; }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 700; }
        .stat-card.accent .stat-value { color: var(--accent); }
        .stat-card.green .stat-value { color: var(--success); }
        .stat-card.teal .stat-value { color: var(--accent2); }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; }
        .panel-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); border-bottom: 1px solid var(--border); }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--surface2); }
        .empty { padding: 2rem; text-align: center; color: var(--muted); font-size: 0.9rem; }

        .badge { padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-lu { background: rgba(62,207,142,0.15); color: var(--success); border: 1px solid rgba(62,207,142,0.3); }
        .badge-nonlu { background: rgba(224,82,82,0.15); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }
    </style>
</head>
<body>

    <a href="/membres" class="back-btn">← Retour à la liste</a>

    <div class="page-title">Profil de <span>{{ $membre->nom }} {{ $membre->prenom }}</span></div>

    <!-- INFOS PERSONNELLES -->
    <div class="info-card">
        <h2>Informations personnelles</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Nom complet</label>
                <span>{{ $membre->nom }} {{ $membre->prenom }}</span>
            </div>
            <div class="info-item">
                <label>Email</label>
                <span>{{ $membre->email }}</span>
            </div>
            <div class="info-item">
                <label>Téléphone</label>
                <span>{{ $membre->telephone }}</span>
            </div>
            <div class="info-item">
                <label>Adresse</label>
                <span>{{ $membre->adresse }}</span>
            </div>
            <div class="info-item">
                <label>Date de naissance</label>
                <span>{{ \Carbon\Carbon::parse($membre->date_naissance)->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <label>Membre depuis</label>
                <span>{{ \Carbon\Carbon::parse($membre->created_at)->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card accent">
            <div class="stat-label">Cotisations</div>
            <div class="stat-value">{{ $membre->cotisations->count() }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Total cotisé</div>
            <div class="stat-value">{{ number_format($membre->cotisations->sum('montant'), 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-card teal">
            <div class="stat-label">Notifications</div>
            <div class="stat-value">{{ $membre->notifications->count() }}</div>
        </div>
    </div>

    <!-- COTISATIONS -->
    <div class="panel">
        <div class="panel-header">Cotisations du membre</div>
        @if($membre->cotisations->count() > 0)
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
        @else
        <div class="empty">Aucune cotisation enregistrée.</div>
        @endif
    </div>

    <!-- NOTIFICATIONS -->
    <div class="panel">
        <div class="panel-header">Notifications du membre</div>
        @if($membre->notifications->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($membre->notifications as $n)
                <tr>
                    <td>{{ $n->titre }}</td>
                    <td>{{ $n->message }}</td>
                    <td>{{ \Carbon\Carbon::parse($n->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $n->lu ? 'badge-lu' : 'badge-nonlu' }}">
                            {{ $n->lu ? 'Lu' : 'Non lu' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty">Aucune notification.</div>
        @endif
    </div>

</body>
</html>