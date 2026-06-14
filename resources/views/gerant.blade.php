<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Espace Gérant</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --green-bright: #3ecf8e;
            --gold: #f0a500; --white: #ffffff; --bg: #f4f7f4;
            --surface: #ffffff; --border: #e2ece8; --text: #1a2e22; --muted: #6b8c7a;
            --danger: #e05252; --danger-light: #fff0f0; --success: #1a6645;
            --success-light: #e8f5ee; --wave: #1ba4e0; --wave-light: #e8f5fc;
            --orange: #ff6600; --orange-light: #fff3eb;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        .sidebar { width: 250px; min-height: 100vh; background: var(--green-dark); display: flex; flex-direction: column; padding: 2rem 1.2rem; position: fixed; top: 0; left: 0; bottom: 0; }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--white); margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem; }
        .logo-icon { width: 34px; height: 34px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: var(--green-dark); flex-shrink: 0; }
        .logo-sub { font-size: 0.72rem; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2.5rem; padding-left: 0.2rem; }
        .gerant-info { background: rgba(240,165,0,0.15); border: 1px solid rgba(240,165,0,0.3); border-radius: 10px; padding: 0.8rem 1rem; margin-bottom: 1.5rem; }
        .gerant-badge { font-size: 0.72rem; color: var(--gold); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .gerant-name { font-size: 0.9rem; color: var(--white); font-weight: 600; margin-top: 0.2rem; }
        .nav-label { font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 0.6rem; margin-top: 1.5rem; padding-left: 0.5rem; }
        .nav-item { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 1rem; border-radius: 8px; color: rgba(255,255,255,0.6); font-size: 0.9rem; font-weight: 500; transition: all 0.2s; cursor: pointer; border: none; background: none; width: 100%; text-align: left; margin-bottom: 2px; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: var(--white); }
        .nav-item.active { background: rgba(255,255,255,0.12); color: var(--white); border-left: 3px solid var(--gold); }
        .notif-badge { background: var(--danger); color: #fff; border-radius: 999px; font-size: 0.65rem; padding: 1px 6px; font-weight: 700; }
        .sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.78rem; color: rgba(255,255,255,0.35); text-align: center; }
        .btn-logout { width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; margin-top: 0.8rem; }
        .btn-logout:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        .main { margin-left: 250px; flex: 1; padding: 2rem 2.5rem; min-height: 100vh; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--text); }
        .page-title span { color: var(--green-mid); }
        .badge-date { background: var(--surface); border: 1px solid var(--border); padding: 0.45rem 1rem; border-radius: 999px; font-size: 0.8rem; color: var(--muted); }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.3rem 1.5rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.04); position: relative; overflow: hidden; cursor: pointer; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--border); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(13,61,43,0.08); }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; color: var(--text); }

        .section { display: none; }
        .section.active { display: block; }

        .alert-success { background: var(--success-light); border: 1px solid rgba(26,102,69,0.2); color: var(--success); padding: 0.8rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-error { background: var(--danger-light); border: 1px solid rgba(224,82,82,0.2); color: var(--danger); padding: 0.8rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); background: #fafcfa; flex-wrap: wrap; gap: 0.5rem; }
        .panel-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--text); }
        .panel-body { padding: 1.5rem; }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text); text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 0.65rem 0.9rem; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 0.9rem; outline: none; transition: border-color 0.2s; width: 100%; }
        .form-control:focus { border-color: var(--green-mid); box-shadow: 0 0 0 3px rgba(26,102,69,0.08); }

        .btn { padding: 0.6rem 1.4rem; border-radius: 8px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--green-dark); color: var(--white); }
        .btn-primary:hover { background: var(--green-mid); transform: translateY(-1px); }
        .btn-danger { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(224,82,82,0.2); padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-danger:hover { background: var(--danger); color: #fff; }
        .btn-edit { background: rgba(240,165,0,0.1); color: #b07800; border: 1px solid rgba(240,165,0,0.3); padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-edit:hover { background: var(--gold); color: var(--green-dark); }
        .btn-profil { background: var(--success-light); color: var(--green-mid); border: 1px solid rgba(26,102,69,0.2); padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-profil:hover { background: var(--green-mid); color: #fff; }
        .btn-secondary { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
        .btn-secondary:hover { color: var(--text); border-color: var(--green-mid); }
        .btn-activate { background: var(--success-light); color: var(--green-mid); border: 1px solid rgba(26,102,69,0.2); padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-activate:hover { background: var(--green-mid); color: #fff; }
        .btn-deactivate { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(224,82,82,0.2); padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-deactivate:hover { background: var(--danger); color: #fff; }
        .btn-row { display: flex; justify-content: flex-end; margin-top: 1rem; }
        .btn-group { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        thead tr { border-bottom: 2px solid var(--border); background: #fafcfa; }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); font-weight: 600; }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); color: var(--text); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f0f7f3; }
        .actions { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

        .badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-green { background: var(--success-light); color: var(--green-mid); }
        .badge-yellow { background: rgba(240,165,0,0.12); color: #b07800; }
        .badge-danger { background: var(--danger-light); color: var(--danger); }

        .notif-item { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 1rem; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.non-lu { background: rgba(240,165,0,0.04); border-left: 3px solid var(--gold); }
        .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); flex-shrink: 0; margin-top: 6px; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(13,61,43,0.4); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; width: 90%; max-width: 580px; box-shadow: 0 20px 60px rgba(13,61,43,0.15); max-height: 90vh; overflow-y: auto; }
        .modal-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--green-dark); }
        .modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }

        .empty { padding: 2rem; text-align: center; color: var(--muted); font-size: 0.9rem; }

        .privilege-box { background: rgba(240,165,0,0.08); border: 1px solid rgba(240,165,0,0.2); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: #b07800; }
        .privilege-box strong { display: block; margin-bottom: 0.4rem; font-size: 0.9rem; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo"><div class="logo-icon">T</div>TontineTD</div>
    <div class="logo-sub">Espace Gérant</div>
    <div class="gerant-info">
        <div class="gerant-badge">⭐ Gérant</div>
        <div class="gerant-name">{{ $gerant->prenom }} {{ $gerant->nom }}</div>
    </div>
    <div class="nav-label">Navigation</div>
    <button class="nav-item active" onclick="showSection('home', this)">Accueil</button>
    <button class="nav-item" onclick="showSection('inscriptions', this)">
        Inscriptions
        @if($membresEnAttente->count() > 0)
        <span class="notif-badge">{{ $membresEnAttente->count() }}</span>
        @endif
    </button>
    <button class="nav-item" onclick="showSection('membres', this)">Membres</button>
    <button class="nav-item" onclick="showSection('tontines', this)">Tontines</button>
    <button class="nav-item" onclick="showSection('cotisations', this)">Cotisations</button>
    <button class="nav-item" onclick="showSection('tours', this)">Tours</button>
    <div class="sidebar-footer">
        TontineTD v1.0 — Laravel 12
        <form action="/logout" method="post">
            @csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <div class="page-title" id="pageTitle">Tableau de <span>bord</span></div>
        <div class="badge-date" id="currentDate"></div>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card" onclick="showSection('membres', document.querySelectorAll('.nav-item')[2])">
            <div class="stat-label">Membres</div>
            <div class="stat-value">{{ count($membres) }}</div>
        </div>
        <div class="stat-card" onclick="showSection('tontines', document.querySelectorAll('.nav-item')[3])">
            <div class="stat-label">Tontines</div>
            <div class="stat-value">{{ count($tontines) }}</div>
        </div>
        <div class="stat-card" onclick="showSection('cotisations', document.querySelectorAll('.nav-item')[4])">
            <div class="stat-label">Cotisations</div>
            <div class="stat-value">{{ count($cotisations) }}</div>
        </div>
        <div class="stat-card" onclick="showSection('inscriptions', document.querySelectorAll('.nav-item')[1])">
            <div class="stat-label">En attente</div>
            <div class="stat-value" style="{{ $membresEnAttente->count() > 0 ? 'color:var(--gold);' : '' }}">{{ $membresEnAttente->count() }}</div>
        </div>
    </div>

    <!-- INSCRIPTIONS -->
    <div class="section" id="section-inscriptions">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Membres en attente de validation</div>
                <div class="btn-group">
                    <span class="badge badge-yellow">{{ $membresEnAttente->count() }} en attente</span>
                    @if($membresEnAttente->count() > 0)
                    <form action="/admin/membres/approuver-tout" method="post" onsubmit="return confirm('Approuver tous ?')">
                        @csrf
                        <button type="submit" class="btn btn-profil">Approuver tout</button>
                    </form>
                    <form action="/admin/membres/refuser-tout" method="post" onsubmit="return confirm('Refuser tous ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger"> Refuser tout</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="table-wrap">
                @if($membresEnAttente->count() > 0)
                <table>
                    <thead>
                        <tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Inscrit le</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($membresEnAttente as $m)
                        <tr>
                            <td>#{{ $m->id }}</td>
                            <td>{{ $m->nom }}</td>
                            <td>{{ $m->prenom }}</td>
                            <td>{{ $m->email }}</td>
                            <td>{{ $m->telephone }}</td>
                            <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y à H:i') }}</td>
                            <td>
                                <div class="actions">
                                    <form action="/admin/membre/{{ $m->id }}/approuver" method="post" onsubmit="return confirm('Approuver ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-profil">✅ Approuver</button>
                                    </form>
                                    <form action="/admin/membre/{{ $m->id }}/refuser" method="post" onsubmit="return confirm('Refuser ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">❌ Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty">✅ Aucune inscription en attente.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- HOME -->
    <div class="section active" id="section-home">
        <div class="privilege-box">
            <strong>⭐ Vos privilèges de gérant</strong>
            Vous pouvez gérer les membres, tontines, cotisations, tours et approuver les inscriptions. Vous ne pouvez pas supprimer un membre ni voir son profil détaillé.
        </div>
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Résumé</div></div>
            <div class="panel-body" style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                <div style="background:var(--bg);border-radius:10px;padding:1rem;border:1px solid var(--border);cursor:pointer;" onclick="showSection('inscriptions', document.querySelectorAll('.nav-item')[1])">
                    <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.3rem;">Inscriptions en attente</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--gold);">{{ $membresEnAttente->count() }}</div>
                </div>
                <div style="background:var(--bg);border-radius:10px;padding:1rem;border:1px solid var(--border);cursor:pointer;" onclick="showSection('membres', document.querySelectorAll('.nav-item')[2])">
                    <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.3rem;">Total membres</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--green-mid);">{{ count($membres) }}</div>
                </div>
                <div style="background:var(--bg);border-radius:10px;padding:1rem;border:1px solid var(--border);cursor:pointer;" onclick="showSection('tontines', document.querySelectorAll('.nav-item')[3])">
                    <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.3rem;">Tontines actives</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--green-dark);">{{ count($tontines) }}</div>
                </div>
                <div style="background:var(--bg);border-radius:10px;padding:1rem;border:1px solid var(--border);cursor:pointer;" onclick="showSection('cotisations', document.querySelectorAll('.nav-item')[4])">
                    <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.3rem;">Total cotisations</div>
                    <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--green-dark);">{{ count($cotisations) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- MEMBRES -->
    <div class="section" id="section-membres">
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Ajouter un membre</div></div>
            <div class="panel-body">
                <form action="/membre" method="post">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Téléphone</label><input type="text" name="telephone" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Adresse</label><input type="text" name="adresse" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date de naissance</label><input type="date" name="date_naissance" class="form-control" required></div>
                    </div>
                    <div class="btn-row"><button type="submit" class="btn btn-primary">Ajouter</button></div>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Liste des membres</div></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Statut</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($membres as $m)
                        <tr>
                            <td>#{{ $m->id }}</td>
                            <td>{{ $m->nom }}</td>
                            <td>{{ $m->prenom }}</td>
                            <td>{{ $m->email }}</td>
                            <td>{{ $m->telephone }}</td>
                            <td>
                                @if($m->statut === 'approuve')
                                    <span class="badge badge-green">Actif</span>
                                @elseif($m->statut === 'refuse')
                                    <span class="badge badge-danger">Désactivé</span>
                                @else
                                    <span class="badge badge-yellow">En attente</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-edit" onclick="openEditMembre({{ $m->id }},'{{ addslashes($m->nom) }}','{{ addslashes($m->prenom) }}','{{ $m->email }}','{{ $m->telephone }}','{{ addslashes($m->adresse) }}','{{ $m->date_naissance }}')">Modifier</button>
                                    @if($m->statut === 'approuve')
                                    <form action="/admin/membre/{{ $m->id }}/desactiver" method="post" onsubmit="return confirm('Désactiver {{ $m->prenom }} ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-deactivate">🚫 Désactiver</button>
                                    </form>
                                    @else
                                    <form action="/admin/membre/{{ $m->id }}/activer" method="post" onsubmit="return confirm('Activer {{ $m->prenom }} ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-activate">✅ Activer</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TONTINES -->
    <div class="section" id="section-tontines">
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Créer une tontine</div></div>
            <div class="panel-body">
                <form action="/tontine" method="post">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Description</label><input type="text" name="description" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Montant (F CFA)</label><input type="number" name="montant" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date début</label><input type="date" name="date_debut" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date fin</label><input type="date" name="date_fin" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Fréquence</label>
                            <select name="frequence" class="form-control">
                                <option value="semaine">Hebdomadaire</option>
                                <option value="mensuelle">Mensuelle</option>
                                <option value="journalier">Journalier</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-row"><button type="submit" class="btn btn-primary">Créer</button></div>
                </form>
            </div>
        </div>
       \<div class="panel">
            <div class="panel-header">
                <div class="panel-title">Liste des tontines</div>
                <div class="btn-group">
                    <form action="/tontines/supprimer-tout" method="post" onsubmit="return confirm('Supprimer toutes les tontines ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">Supprimer tout</button>
                    </form>
                </div>
            </div>
            <div class="table-wrap">
                <form action="/tontines/supprimer-selection" method="post" id="formSelectionTontines">
                    @csrf
                <table>
                    <thead><tr><th></th><th>ID</th><th>Nom</th><th>Montant</th><th>Début</th><th>Fin</th><th>Fréquence</th><th>Membres</th><th>Actions</th></tr></thead>
                    <tbody>
                        @foreach($tontines as $t)
                        <tr>
                            <td><input type="checkbox" name="tontine_ids[]" value="{{ $t->id }}"></td>
                            <td>#{{ $t->id }}</td>
                            <td>{{ $t->nom }}</td>
                            <td>{{ number_format($t->montant, 0, ',', ' ') }} F</td>
                            <td>{{ $t->date_debut }}</td>
                            <td>{{ $t->date_fin }}</td>
                            <td><span class="badge badge-yellow">{{ $t->frequence }}</span></td>
                            <td><span class="badge badge-green">{{ $t->membres->count() }}</span></td>
                            <td><div class="actions">
<td><div class="actions">
                                <a href="/tontine/{{ $t->id }}/details" class="btn btn-profil">Détails</a>
                                <button type="button" class="btn btn-edit" onclick="openEditTontine({{ $t->id }},'{{ addslashes($t->nom) }}','{{ addslashes($t->description) }}',{{ $t->montant }},'{{ $t->date_debut }}','{{ $t->date_fin }}','{{ $t->frequence }}')">Modifier</button>
                            </div></td>
                            </div></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:1rem 1.5rem;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer les tontines sélectionnées ?')">Supprimer la sélection</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COTISATIONS -->
    <div class="section" id="section-cotisations">
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Ajouter une cotisation</div></div>
            <div class="panel-body">
                <form action="/cotisation" method="post">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Tontine</label>
                            <select name="tontine_id" class="form-control" required>
                                <option value="">Choisir</option>
                                @foreach($tontines as $t)
                                <option value="{{ $t->id }}">{{ $t->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Membre</label>
                            <select name="membre_id" class="form-control" required>
                                <option value="">Choisir</option>
                                @foreach($membres as $m)
                                <option value="{{ $m->id }}">{{ $m->nom }} {{ $m->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Montant (F CFA)</label><input type="number" name="montant" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date</label><input type="date" name="date_cotisation" class="form-control" required></div>
                    </div>
                    <div class="btn-row"><button type="submit" class="btn btn-primary">Enregistrer</button></div>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Liste des cotisations</div>
                <div class="btn-group">
                    <form action="/cotisations/supprimer-tout" method="post" onsubmit="return confirm('Supprimer toutes les cotisations ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">Supprimer tout</button>
                    </form>
                </div>
            </div>
            <div class="table-wrap">
                <form action="/cotisations/supprimer-selection" method="post" id="formSelectionCotisations">
                    @csrf
                <table>
                    <thead><tr><th></th><th>ID</th><th>Tontine</th><th>Montant</th><th>Date</th><th>Membre</th><th>Actions</th></tr></thead>
                    <tbody>
                        @foreach($cotisations as $c)
                        <tr>
                            <td><input type="checkbox" name="cotisation_ids[]" value="{{ $c->id }}"></td>
                            <td>#{{ $c->id }}</td>
                            <td>{{ $c->tontine->nom ?? '—' }}</td>
                            <td>{{ number_format($c->montant, 0, ',', ' ') }} F</td>
                            <td>{{ $c->date_cotisation }}</td>
                            <td>{{ $c->membre->nom ?? '—' }} {{ $c->membre->prenom ?? '' }}</td>
                            <td><div class="actions">
                               <button type="button" class="btn btn-edit" onclick="openEditCotisation({{ $c->id }},{{ $c->montant }},'{{ $c->date_cotisation }}',{{ $c->membre_id ?? 0 }},{{ $c->tontine_id ?? 0 }})">Modifier</button>
                            </div></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:1rem 1.5rem;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer les cotisations sélectionnées ?')">Supprimer la sélection</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- TOURS -->
    <div class="section" id="section-tours">
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Ajouter un tour</div></div>
            <div class="panel-body">
                <form action="/tour" method="post">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Tontine</label>
                            <select name="tontine_id" class="form-control" required>
                                <option value="">Choisir</option>
                                @foreach($tontines as $t)
                                <option value="{{ $t->id }}">{{ $t->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Date du tour</label><input type="date" name="date_tour" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">État</label>
                            <select name="etat" class="form-control">
                                <option value="en_attente">En attente</option>
                                <option value="terminer">Terminé</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-row"><button type="submit" class="btn btn-primary">Ajouter</button></div>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Liste des tours</div>
                <div class="btn-group">
                    <form action="/tours/supprimer-tout" method="post" onsubmit="return confirm('Supprimer tous les tours ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">Supprimer tout</button>
                    </form>
                </div>
            </div>
            <div class="table-wrap">
                <form action="/tours/supprimer-selection" method="post" id="formSelectionTours">
                    @csrf
                <table>
                    <thead><tr><th></th><th>ID</th><th>Tontine</th><th>Date</th><th>État</th><th>Actions</th></tr></thead>
                    <tbody>
                        @foreach($tours as $tour)
                        <tr>
                            <td><input type="checkbox" name="tour_ids[]" value="{{ $tour->id }}"></td>
                            <td>#{{ $tour->id }}</td>
                            <td>{{ $tour->tontine->nom ?? '—' }}</td>
                            <td>{{ $tour->date_tour }}</td>
                            <td><span class="badge {{ $tour->etat === 'terminer' ? 'badge-green' : 'badge-yellow' }}">{{ $tour->etat === 'terminer' ? 'Terminé' : 'En attente' }}</span></td>
                            <td><div class="actions">
                              <button type="button" class="btn btn-edit" onclick="openEditTour({{ $tour->id }},{{ $tour->tontine_id ?? 0 }},'{{ $tour->date_tour }}','{{ $tour->etat }}')">Modifier</button>
                            </div></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:1rem 1.5rem;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer les tours sélectionnés ?')">Supprimer la sélection</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- MODALS -->
<div class="modal-overlay" id="modalMembre">
    <div class="modal">
        <div class="modal-title">Modifier le membre</div>
        <form id="formEditMembre" method="post">@csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" id="e_nom" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Prénom</label><input type="text" name="prenom" id="e_prenom" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" id="e_email" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Téléphone</label><input type="text" name="telephone" id="e_tel" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" name="adresse" id="e_adr" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Date naissance</label><input type="date" name="date_naissance" id="e_dn" class="form-control" required></div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMembre')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalTontine">
    <div class="modal">
        <div class="modal-title">Modifier la tontine</div>
        <form id="formEditTontine" method="post">@csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" id="et_nom" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Description</label><input type="text" name="description" id="et_desc" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Montant</label><input type="number" name="montant" id="et_montant" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Date début</label><input type="date" name="date_debut" id="et_debut" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Date fin</label><input type="date" name="date_fin" id="et_fin" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Fréquence</label>
                    <select name="frequence" id="et_freq" class="form-control">
                        <option value="semaine">Hebdomadaire</option>
                        <option value="mensuelle">Mensuelle</option>
                        <option value="journalier">Journalier</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTontine')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalCotisation">
    <div class="modal">
        <div class="modal-title">Modifier la cotisation</div>
        <form id="formEditCotisation" method="post">@csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Tontine</label>
                    <select name="tontine_id" id="ec_tontine" class="form-control" required>
                        @foreach($tontines as $t)
                        <option value="{{ $t->id }}">{{ $t->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Montant</label><input type="number" name="montant" id="ec_montant" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Date</label><input type="date" name="date_cotisation" id="ec_date" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Membre</label>
                    <select name="membre_id" id="ec_membre" class="form-control" required>
                        @foreach($membres as $m)
                        <option value="{{ $m->id }}">{{ $m->nom }} {{ $m->prenom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalCotisation')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalTour">
    <div class="modal">
        <div class="modal-title">Modifier le tour</div>
        <form id="formEditTour" method="post">@csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Tontine</label>
                    <select name="tontine_id" id="etour_tontine" class="form-control" required>
                        @foreach($tontines as $t)
                        <option value="{{ $t->id }}">{{ $t->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Date du tour</label><input type="date" name="date_tour" id="etour_date" class="form-control" required></div>
                <div class="form-group"><label class="form-label">État</label>
                    <select name="etat" id="etour_etat" class="form-control">
                        <option value="en_attente">En attente</option>
                        <option value="terminer">Terminé</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTour')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    const titles = {
        home: 'Tableau de <span>bord</span>',
        inscriptions: 'Inscriptions en <span>attente</span>',
        membres: 'Gestion des <span>membres</span>',
        tontines: 'Gestion des <span>tontines</span>',
        cotisations: 'Gestion des <span>cotisations</span>',
        tours: 'Gestion des <span>tours</span>',
    };

   function showSection(name, btn) {
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        const section = document.getElementById('section-' + name);
        if (section) section.classList.add('active');
        if (!btn) {
            document.querySelectorAll('.nav-item').forEach(n => {
                const onclickAttr = n.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes("'" + name + "'")) {
                    btn = n;
                }
            });
        }
        if (btn) btn.classList.add('active');
        document.getElementById('pageTitle').innerHTML = titles[name] || name;
    }

    @if(session('section'))
        showSection('{{ session('section') }}', null);
    @endif

    function closeModal(id) { document.getElementById(id).classList.remove('open'); }

    function openEditMembre(id, nom, prenom, email, tel, adr, dn) {
        document.getElementById('formEditMembre').action = '/membre/' + id;
        document.getElementById('e_nom').value = nom;
        document.getElementById('e_prenom').value = prenom;
        document.getElementById('e_email').value = email;
        document.getElementById('e_tel').value = tel;
        document.getElementById('e_adr').value = adr;
        document.getElementById('e_dn').value = dn;
        document.getElementById('modalMembre').classList.add('open');
    }

    function openEditTontine(id, nom, desc, montant, debut, fin, freq) {
        document.getElementById('formEditTontine').action = '/tontine/' + id;
        document.getElementById('et_nom').value = nom;
        document.getElementById('et_desc').value = desc;
        document.getElementById('et_montant').value = montant;
        document.getElementById('et_debut').value = debut;
        document.getElementById('et_fin').value = fin;
        document.getElementById('et_freq').value = freq;
        document.getElementById('modalTontine').classList.add('open');
    }

    function openEditCotisation(id, montant, date, membre_id, tontine_id) {
        document.getElementById('formEditCotisation').action = '/cotisation/' + id;
        document.getElementById('ec_montant').value = montant;
        document.getElementById('ec_date').value = date;
        if (membre_id) document.getElementById('ec_membre').value = membre_id;
        if (tontine_id) document.getElementById('ec_tontine').value = tontine_id;
        document.getElementById('modalCotisation').classList.add('open');
    }

    function openEditTour(id, tontine_id, date, etat) {
        document.getElementById('formEditTour').action = '/tour/' + id;
        if (tontine_id) document.getElementById('etour_tontine').value = tontine_id;
        document.getElementById('etour_date').value = date;
        document.getElementById('etour_etat').value = etat;
        document.getElementById('modalTour').classList.add('open');
    }

    document.querySelectorAll('.modal-overlay').forEach(o => {
        o.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
    });
</script>
</body>
</html>