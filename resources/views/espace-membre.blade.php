<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Mon Espace</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0e1a; --surface: #111827; --surface2: #1a2235; --border: #1f2d45; --accent: #f0a500; --accent2: #00c2a8; --text: #e8edf5; --muted: #6b7a99; --danger: #e05252; --success: #3ecf8e; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        .sidebar { width: 240px; min-height: 100vh; background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; padding: 2rem 1.2rem; position: fixed; top: 0; left: 0; bottom: 0; }
        .logo { font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--accent); margin-bottom: 0.3rem; }
        .logo span { color: var(--accent2); }
        .logo-sub { font-size: 0.72rem; color: var(--muted); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2rem; }
        .membre-info { background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; margin-bottom: 2rem; }
        .membre-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem; color: var(--text); }
        .membre-email { font-size: 0.75rem; color: var(--muted); margin-top: 0.2rem; }
        .nav-label { font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; color: var(--muted); margin-bottom: 0.8rem; }
        .nav-item { display: flex; align-items: center; padding: 0.7rem 1rem; border-radius: 8px; color: var(--muted); font-size: 0.9rem; font-weight: 500; transition: all 0.2s; cursor: pointer; border: none; background: none; width: 100%; text-align: left; margin-bottom: 2px; }
        .nav-item:hover, .nav-item.active { background: var(--surface2); color: var(--text); }
        .nav-item.active { color: var(--accent); }
        .notif-badge { background: var(--danger); color: #fff; border-radius: 999px; font-size: 0.65rem; padding: 1px 6px; margin-left: auto; font-weight: 700; }
        .sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--border); }
        .btn-logout { width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid rgba(224,82,82,0.3); background: rgba(224,82,82,0.1); color: var(--danger); font-family: 'Syne', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; }
        .btn-logout:hover { background: var(--danger); color: #fff; }

        .main { margin-left: 240px; flex: 1; padding: 2rem 2.5rem; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .page-title { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; }
        .page-title span { color: var(--accent); }
        .badge-date { background: var(--surface2); border: 1px solid var(--border); padding: 0.45rem 1rem; border-radius: 999px; font-size: 0.8rem; color: var(--muted); }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 1.3rem 1.5rem; }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 700; }
        .stat-card.accent .stat-value { color: var(--accent); }
        .stat-card.teal .stat-value { color: var(--accent2); }
        .stat-card.green .stat-value { color: var(--success); }

        .section { display: none; }
        .section.active { display: block; }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; }
        .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); }
        .panel-title { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; }
        .panel-body { padding: 1.5rem; }
        .export-btns { display: flex; gap: 0.5rem; }

        .btn { padding: 0.55rem 1.2rem; border-radius: 8px; border: none; font-family: 'Syne', sans-serif; font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--accent); color: #0a0e1a; }
        .btn-primary:hover { opacity: 0.85; }
        .btn-pdf { background: rgba(224,82,82,0.15); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }
        .btn-pdf:hover { background: var(--danger); color: #fff; }
        .btn-excel { background: rgba(62,207,142,0.15); color: var(--success); border: 1px solid rgba(62,207,142,0.3); }
        .btn-excel:hover { background: var(--success); color: #fff; }
        .btn-edit { background: rgba(240,165,0,0.15); color: var(--accent); border: 1px solid rgba(240,165,0,0.3); padding: 0.3rem 0.7rem; font-size: 0.75rem; }
        .btn-edit:hover { background: var(--accent); color: #0a0e1a; }
        .btn-lu { background: rgba(240,165,0,0.15); color: var(--accent); border: 1px solid rgba(240,165,0,0.3); padding: 0.3rem 0.7rem; font-size: 0.75rem; }
        .btn-lu:hover { background: var(--accent); color: #0a0e1a; }

        .form-row { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
        .form-control { background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; padding: 0.65rem 0.9rem; color: var(--text); font-size: 0.9rem; outline: none; width: 200px; }
        .form-control:focus { border-color: var(--accent); }

        .alert-success { background: rgba(62,207,142,0.1); border: 1px solid rgba(62,207,142,0.3); color: var(--success); padding: 0.8rem 1.2rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        thead tr { border-bottom: 1px solid var(--border); }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--surface2); }

        .notif-item { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.non-lu { background: rgba(240,165,0,0.05); border-left: 3px solid var(--accent); }
        .notif-titre { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.3rem; }
        .notif-msg { font-size: 0.82rem; color: var(--muted); }
        .notif-date { font-size: 0.72rem; color: var(--muted); margin-top: 0.3rem; }
        .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); flex-shrink: 0; margin-top: 6px; }
        .empty { padding: 2rem; text-align: center; color: var(--muted); font-size: 0.9rem; }

        .badge-membre { background: rgba(240,165,0,0.15); color: var(--accent); border: 1px solid rgba(240,165,0,0.3); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-admin { background: rgba(0,194,168,0.15); color: var(--accent2); border: 1px solid rgba(0,194,168,0.3); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; width: 90%; max-width: 420px; }
        .modal-title { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--accent); }
        .modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }
        .btn-secondary { background: var(--surface2); color: var(--muted); border: 1px solid var(--border); padding: 0.65rem 1.5rem; border-radius: 8px; font-family: 'Syne', sans-serif; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="logo">Tontine<span>TD</span></div>
    <div class="logo-sub">Espace membre</div>

    <div class="membre-info">
        <div class="membre-name">{{ $membre->nom }} {{ $membre->prenom }}</div>
        <div class="membre-email">{{ $membre->email }}</div>
    </div>

    <div class="nav-label">Menu</div>
    <button class="nav-item active" onclick="showSection('accueil', this)">Accueil</button>
    <button class="nav-item" onclick="showSection('cotisations', this)">Mes cotisations</button>
    <button class="nav-item" onclick="showSection('notifications', this)">
        Notifications
        @if($membre->notificationsNonLues()->count() > 0)
        <span class="notif-badge">{{ $membre->notificationsNonLues()->count() }}</span>
        @endif
    </button>

    <div class="sidebar-footer">
        <form action="/logout" method="post">
            @csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <div class="page-title" id="pageTitle">Mon <span>espace</span></div>
        <div class="badge-date" id="currentDate"></div>
    </div>

    <div class="stats-grid">
        <div class="stat-card accent">
            <div class="stat-label">Mes cotisations</div>
            <div class="stat-value">{{ $membre->cotisations->count() }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Total cotisé</div>
            <div class="stat-value">{{ number_format($membre->cotisations->sum('montant'), 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-card teal">
            <div class="stat-label">Notifications</div>
            <div class="stat-value">{{ $membre->notificationsNonLues()->count() }}</div>
        </div>
    </div>

    <!-- ACCUEIL -->
    <div class="section active" id="section-accueil">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Télécharger mes transactions</div>
                <div class="export-btns">
                    <a href="/mon-espace/export/pdf" class="btn btn-pdf">Télécharger PDF</a>
                    <a href="/mon-espace/export/excel" class="btn btn-excel">Télécharger Excel</a>
                </div>
            </div>
            <div class="empty">Cliquez sur un bouton pour télécharger votre relevé de transactions.</div>
        </div>
    </div>

    <!-- COTISATIONS -->
    <div class="section" id="section-cotisations">

        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Ajouter une cotisation</div>
            </div>
            <div class="panel-body">
                <form method="POST" action="/mon-espace/cotisation">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Montant (F CFA)</label>
                            <input type="number" name="montant" class="form-control" placeholder="Ex: 5000" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date</label>
                            <input type="date" name="date_cotisation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Mes cotisations</div>
                <div class="export-btns">
                    <a href="/mon-espace/export/pdf" class="btn btn-pdf">PDF</a>
                    <a href="/mon-espace/export/excel" class="btn btn-excel">Excel</a>
                </div>
            </div>
            <div class="table-wrap">
                @if($membre->cotisations->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Ajouté par</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($membre->cotisations as $c)
                        <tr>
                            <td>#{{ $c->id }}</td>
                            <td>{{ number_format($c->montant, 0, ',', ' ') }} F CFA</td>
                            <td>{{ \Carbon\Carbon::parse($c->date_cotisation)->format('d/m/Y') }}</td>
                            <td>
                                @if(isset($c->ajout_par) && $c->ajout_par === 'membre')
                                <span class="badge-membre">Moi</span>
                                @else
                                <span class="badge-admin">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($c->ajout_par) && $c->ajout_par === 'membre')
                                <button class="btn btn-edit" onclick="openEditCotisation({{ $c->id }}, {{ $c->montant }}, '{{ $c->date_cotisation }}')">Modifier</button>
                                @else
                                <span style="color:var(--muted);font-size:0.78rem;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty">Aucune cotisation enregistrée.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- NOTIFICATIONS -->
    <div class="section" id="section-notifications">
        <div class="panel">
            <div class="panel-header"><div class="panel-title">Mes notifications</div></div>
            @if($membre->notifications->count() > 0)
                @foreach($membre->notifications as $notif)
                <div class="notif-item {{ !$notif->lu ? 'non-lu' : '' }}">
                    <div style="flex:1">
                        <div class="notif-titre">{{ $notif->titre }}</div>
                        <div class="notif-msg">{{ $notif->message }}</div>
                        <div class="notif-date">{{ \Carbon\Carbon::parse($notif->created_at)->locale('fr')->diffForHumans() }}</div>
                    </div>
                    @if(!$notif->lu)
                    <div style="display:flex;align-items:center;gap:0.5rem">
                        <div class="notif-dot"></div>
                        <form action="/mon-espace/notif/{{ $notif->id }}/lu" method="post">
                            @csrf
                            <button type="submit" class="btn btn-lu">Marquer lu</button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            @else
            <div class="empty">Aucune notification pour le moment.</div>
            @endif
        </div>
    </div>
</main>

<!-- MODAL MODIFIER COTISATION -->
<div class="modal-overlay" id="modalEditCotisation">
    <div class="modal">
        <div class="modal-title">Modifier la cotisation</div>
        <form id="formEditCotisation" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Montant (F CFA)</label>
                <input type="number" name="montant" id="edit_montant" class="form-control" style="width:100%;" required>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Date</label>
                <input type="date" name="date_cotisation" id="edit_date" class="form-control" style="width:100%;" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    const titles = {
        accueil: 'Mon <span>espace</span>',
        cotisations: 'Mes <span>cotisations</span>',
        notifications: 'Mes <span>notifications</span>',
    };

    function showSection(name, btn) {
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('section-' + name).classList.add('active');
        if (btn) btn.classList.add('active');
        document.getElementById('pageTitle').innerHTML = titles[name] || name;
    }

    function openEditCotisation(id, montant, date) {
        document.getElementById('formEditCotisation').action = '/mon-espace/cotisation/' + id;
        document.getElementById('edit_montant').value = montant;
        document.getElementById('edit_date').value = date;
        document.getElementById('modalEditCotisation').classList.add('open');
    }

    function closeEditModal() {
        document.getElementById('modalEditCotisation').classList.remove('open');
    }

    document.getElementById('modalEditCotisation').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
</body>
</html>