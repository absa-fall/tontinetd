<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Détails de la tontine</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --gold: #f0a500; --white: #ffffff; --bg: #f4f7f4;
            --surface: #ffffff; --border: #e2ece8; --text: #1a2e22; --muted: #6b8c7a;
            --success: #1a6645; --success-light: #e8f5ee; --wave: #1ba4e0; --wave-light: #e8f5fc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); padding: 2rem; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; }
        .title span { color: var(--green-mid); }
        .btn-back { padding: 0.6rem 1.4rem; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); text-decoration: none; font-weight: 600; font-size: 0.85rem; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.2rem 1.5rem; }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; }
        .panel-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); background: #fafcfa; }
        .panel-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; }

        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        thead tr { border-bottom: 2px solid var(--border); background: #fafcfa; }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); font-weight: 600; }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }

        .badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-green { background: var(--success-light); color: var(--green-mid); }
        .badge-yellow { background: rgba(240,165,0,0.12); color: #b07800; }
        .badge-wave { background: var(--wave-light); color: var(--wave); }
        .badge-admin { background: var(--success-light); color: var(--success); border: 1px solid rgba(26,102,69,0.2); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }

        .empty { padding: 2rem; text-align: center; color: var(--muted); font-size: 0.9rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="title">{{ $tontine->nom }} — <span>Détails</span></div>
        <a href="javascript:history.back()" class="btn-back">Retour</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Montant</div>
            <div class="stat-value">{{ number_format($tontine->montant, 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Membres</div>
            <div class="stat-value">{{ $tontine->membres->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Tours effectués</div>
            <div class="stat-value">{{ $nbToursTermines }} / {{ $tontine->tours->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total cotisations</div>
            <div class="stat-value">{{ $tontine->cotisations->count() }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><div class="panel-title">Membres</div></div>
        @if($tontine->membres->count() > 0)
        <table>
            <thead><tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th></tr></thead>
            <tbody>
                @foreach($tontine->membres as $m)
                <tr>
                    <td>{{ $m->nom }}</td>
                    <td>{{ $m->prenom }}</td>
                    <td>{{ $m->email }}</td>
                    <td>
                        @if($m->pivot->role === 'admin')
                            <span class="badge-admin">Gérant</span>
                        @else
                            <span class="badge badge-yellow">Membre</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty">Aucun membre.</div>
        @endif
    </div>

    <div class="panel">
        <div class="panel-header"><div class="panel-title">Tours</div></div>
        @if($tontine->tours->count() > 0)
        <table>
            <thead><tr><th>Date</th><th>Bénéficiaire</th><th>État</th><th>Mode réception</th></tr></thead>
            <tbody>
                @foreach($tontine->tours as $tour)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') }}</td>
                    <td>{{ $tour->membre->nom ?? '-' }} {{ $tour->membre->prenom ?? '' }}</td>
                    <td>
                        @if($tour->etat === 'terminer')
                            <span class="badge badge-green">Terminé</span>
                        @else
                            <span class="badge badge-wave">En attente</span>
                        @endif
                    </td>
                    <td>{{ $tour->mode_reception ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty">Aucun tour.</div>
        @endif
    </div>

    <div class="panel">
        <div class="panel-header"><div class="panel-title">Cotisations</div></div>
        @if($tontine->cotisations->count() > 0)
        <table>
            <thead><tr><th>Membre</th><th>Montant</th><th>Date</th></tr></thead>
            <tbody>
                @foreach($tontine->cotisations as $c)
                <tr>
                    <td>{{ $c->membre->nom ?? '-' }} {{ $c->membre->prenom ?? '' }}</td>
                    <td>{{ number_format($c->montant, 0, ',', ' ') }} F</td>
                    <td>{{ \Carbon\Carbon::parse($c->date_cotisation)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty">Aucune cotisation.</div>
        @endif
    </div>
</div>
</body>
</html>