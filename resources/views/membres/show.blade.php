<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Membre - {{ $membre->nom }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --green-bright: #3ecf8e;
            --gold: #f0a500; --white: #ffffff; --bg: #f4f7f4;
            --surface: #ffffff; --border: #e2ece8; --text: #1a2e22; --muted: #6b8c7a;
            --danger: #e05252; --danger-light: #fff0f0; --success: #1a6645;
            --success-light: #e8f5ee;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        .sidebar { width: 250px; min-height: 100vh; background: var(--green-dark); display: flex; flex-direction: column; padding: 2rem 1.2rem; position: fixed; top: 0; left: 0; bottom: 0; }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--white); margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem; }
        .logo-icon { width: 34px; height: 34px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: var(--green-dark); flex-shrink: 0; }
        .logo-sub { font-size: 0.72rem; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2.5rem; padding-left: 0.2rem; }
        .nav-label { font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 0.6rem; margin-top: 1.5rem; padding-left: 0.5rem; }
        .nav-item { display: flex; align-items: center; padding: 0.7rem 1rem; border-radius: 8px; color: rgba(255,255,255,0.6); font-size: 0.9rem; font-weight: 500; text-decoration: none; margin-bottom: 2px; transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: var(--white); }
        .nav-item.active { background: rgba(255,255,255,0.12); color: var(--white); border-left: 3px solid var(--gold); }
        .sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.78rem; color: rgba(255,255,255,0.35); text-align: center; }
        .btn-logout { width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; margin-top: 0.8rem; }
        .btn-logout:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        .main { margin-left: 250px; flex: 1; padding: 2rem 2.5rem; min-height: 100vh; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--text); }
        .page-title span { color: var(--green-mid); }

        .back-btn { display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 1.5rem; padding: 0.5rem 1.2rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--muted); text-decoration: none; font-size: 0.85rem; transition: all 0.2s; }
        .back-btn:hover { border-color: var(--green-mid); color: var(--green-mid); }

        .info-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .info-card h2 { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; margin-bottom: 1.2rem; color: var(--green-mid); }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .info-item label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); display: block; margin-bottom: 0.3rem; }
        .info-item span { font-size: 0.95rem; font-weight: 500; color: var(--text); }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 1.3rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--border); }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .panel-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); background: #fafcfa; font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--text); }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); border-bottom: 1px solid var(--border); font-weight: 600; background: #fafcfa; }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); color: var(--text); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f0f7f3; }
        .empty { padding: 2rem; text-align: center; color: var(--muted); font-size: 0.9rem; }

        .badge { padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; display: inline-block; }
        .badge-lu { background: var(--success-light); color: var(--green-mid); border: 1px solid rgba(26,102,69,0.2); }
        .badge-nonlu { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(224,82,82,0.2); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo"><div class="logo-icon">T</div>TontineTD</div>
    <div class="logo-sub">Administration</div>
    <div class="nav-label">Navigation</div>
    <a href="/dashboard" class="nav-item">Accueil</a>
    <a href="/dashboard" class="nav-item active">Membres</a>
    <div class="sidebar-footer">
        TontineTD v1.0 — Laravel 12
        <form action="/logout" method="post">
            @csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>
    </div>
</aside>

<main class="main">
    <a href="/dashboard" class="back-btn">← Retour au dashboard</a>

    <div class="topbar">
        <div class="page-title">Profil de <span>{{ $membre->nom }} {{ $membre->prenom }}</span></div>
    </div>

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

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Cotisations</div>
            <div class="stat-value">{{ $membre->cotisations->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total cotisé</div>
            <div class="stat-value">{{ number_format($membre->cotisations->sum('montant'), 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Notifications</div>
            <div class="stat-value">{{ $membre->notifications->count() }}</div>
        </div>
    </div>

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
</main>

</body>
</html>