<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Mon Espace</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --green-bright: #3ecf8e;
            --gold: #f0a500; --white: #ffffff; --bg: #f4f7f4;
            --surface: #ffffff; --border: #e2ece8; --text: #1a2e22; --muted: #6b8c7a;
            --danger: #e05252; --danger-light: #fff0f0;
            --success: #1a6645; --success-light: #e8f5ee;
            --wave: #1ba4e0; --wave-light: #e8f5fc;
            --orange: #ff6600; --orange-light: #fff3eb;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* SIDEBAR */
        .sidebar { width: 250px; min-height: 100vh; background: var(--green-dark); display: flex; flex-direction: column; padding: 2rem 1.2rem; position: fixed; top: 0; left: 0; bottom: 0; }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--white); margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem; }
        .logo-icon { width: 34px; height: 34px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: var(--green-dark); flex-shrink: 0; }
        .logo-sub { font-size: 0.72rem; color: rgba(255,255,255,0.4); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 1.5rem; padding-left: 0.2rem; }
        .membre-info { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 1rem; margin-bottom: 2rem; }
        .membre-name { font-weight: 700; font-size: 0.92rem; color: var(--white); }
        .membre-email { font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-top: 0.2rem; }
        .nav-label { font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 0.6rem; padding-left: 0.5rem; }
        .nav-item { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 1rem; border-radius: 8px; color: rgba(255,255,255,0.6); font-size: 0.9rem; font-weight: 500; transition: all 0.2s; cursor: pointer; border: none; background: none; width: 100%; text-align: left; margin-bottom: 2px; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: var(--white); }
        .nav-item.active { background: rgba(255,255,255,0.12); color: var(--white); border-left: 3px solid var(--gold); }
        .notif-badge { background: var(--danger); color: #fff; border-radius: 999px; font-size: 0.65rem; padding: 1px 6px; font-weight: 700; }
        .sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; }
        .btn-logout:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        /* MAIN */
        .main { margin-left: 250px; flex: 1; padding: 2rem 2.5rem; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--text); }
        .page-title span { color: var(--green-mid); }
        .badge-date { background: var(--surface); border: 1px solid var(--border); padding: 0.45rem 1rem; border-radius: 999px; font-size: 0.8rem; color: var(--muted); box-shadow: 0 1px 4px rgba(0,0,0,0.05); }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.3rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); position: relative; overflow: hidden; transition: all 0.2s; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.accent::before { background: var(--gold); }
        .stat-card.green::before { background: var(--green-mid); }
        .stat-card.teal::before { background: var(--green-bright); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(13,61,43,0.08); }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; }
        .stat-card.accent .stat-value { color: var(--gold); }
        .stat-card.green .stat-value { color: var(--green-mid); }
        .stat-card.teal .stat-value { color: var(--green-bright); }

        /* SECTIONS */
        .section { display: none; }
        .section.active { display: block; }

        .alert-success { background: var(--success-light); border: 1px solid rgba(26,102,69,0.2); color: var(--success); padding: 0.8rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-error { background: var(--danger-light); border: 1px solid rgba(224,82,82,0.2); color: var(--danger); padding: 0.8rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }

        /* PANELS */
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); background: #fafcfa; }
        .panel-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--text); }
        .panel-body { padding: 1.5rem; }
        .export-btns { display: flex; gap: 0.5rem; }

        /* BUTTONS */
        .btn { padding: 0.55rem 1.2rem; border-radius: 8px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--green-dark); color: var(--white); }
        .btn-primary:hover { background: var(--green-mid); transform: translateY(-1px); }
        .btn-pdf { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(224,82,82,0.2); }
        .btn-pdf:hover { background: var(--danger); color: #fff; }
        .btn-excel { background: var(--success-light); color: var(--success); border: 1px solid rgba(26,102,69,0.2); }
        .btn-excel:hover { background: var(--success); color: #fff; }
        .btn-lu { background: rgba(240,165,0,0.1); color: #b07800; border: 1px solid rgba(240,165,0,0.3); padding: 0.3rem 0.7rem; font-size: 0.75rem; }
        .btn-lu:hover { background: var(--gold); color: var(--green-dark); }
        .btn-secondary { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
        .btn-secondary:hover { border-color: var(--green-mid); color: var(--text); }

        /* FORMS */
        .form-row { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text); text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 0.65rem 0.9rem; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 0.9rem; outline: none; width: 200px; transition: border-color 0.2s; }
        .form-control:focus { border-color: var(--green-mid); box-shadow: 0 0 0 3px rgba(26,102,69,0.08); }
        .form-control-full { background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 0.65rem 0.9rem; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 0.9rem; outline: none; width: 100%; transition: border-color 0.2s; }
        .form-control-full:focus { border-color: var(--green-mid); box-shadow: 0 0 0 3px rgba(26,102,69,0.08); }
        .form-stack { display: flex; flex-direction: column; gap: 1rem; max-width: 400px; }
        .form-stack .form-group { display: flex; flex-direction: column; gap: 0.4rem; }

        /* TABLE */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        thead tr { border-bottom: 2px solid var(--border); background: #fafcfa; }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); font-weight: 600; }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); color: var(--text); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f0f7f3; }

        .badge-membre { background: rgba(240,165,0,0.12); color: #b07800; border: 1px solid rgba(240,165,0,0.2); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-admin { background: var(--success-light); color: var(--success); border: 1px solid rgba(26,102,69,0.2); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-wave { background: var(--wave-light); color: var(--wave); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-orange { background: var(--orange-light); color: var(--orange); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-success { background: var(--success-light); color: var(--success); padding: 0.2rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }

        /* NOTIFICATIONS */
        .notif-item { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.non-lu { background: rgba(240,165,0,0.04); border-left: 3px solid var(--gold); }
        .notif-titre { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.3rem; color: var(--text); }
        .notif-msg { font-size: 0.82rem; color: var(--muted); }
        .notif-date { font-size: 0.72rem; color: var(--muted); margin-top: 0.3rem; }
        .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); flex-shrink: 0; margin-top: 6px; }
        .empty { padding: 2rem; text-align: center; color: var(--muted); font-size: 0.9rem; }

        /* PAYMENT MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(13,61,43,0.35); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 2rem; width: 90%; max-width: 440px; box-shadow: 0 20px 60px rgba(13,61,43,0.15); }
        .modal-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; color: var(--green-dark); }

        .pay-methods-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.8rem; margin-bottom: 1.2rem; }
        .pay-method-card { border: 2px solid var(--border); border-radius: 12px; padding: 1rem; cursor: pointer; text-align: center; transition: all 0.2s; position: relative; background: var(--bg); }
        .pay-method-card:hover { background: var(--success-light); }
        .pay-method-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-weight: 900; font-size: 0.85rem; }
        .pay-method-name { font-weight: 700; font-size: 0.82rem; color: var(--text); }
        .pay-check { display: none; position: absolute; top: 6px; right: 6px; width: 16px; height: 16px; border-radius: 50%; background: var(--green-mid); color: #fff; font-size: 0.6rem; align-items: center; justify-content: center; }
        .pay-form-section { display: none; margin-top: 1rem; }

        .btn-wave { background: var(--wave); color: #fff; width: 100%; padding: 0.75rem; border-radius: 10px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn-wave:hover { background: #1591c7; }
        .btn-orange-pay { background: var(--orange); color: #fff; width: 100%; padding: 0.75rem; border-radius: 10px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn-orange-pay:hover { background: #e55c00; }
        .btn-cash { background: var(--green-dark); color: #fff; width: 100%; padding: 0.75rem; border-radius: 10px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn-cash:hover { background: var(--green-mid); }
        .btn-cancel { width: 100%; padding: 0.65rem; border-radius: 8px; border: 1.5px solid var(--border); background: transparent; color: var(--muted); font-family: 'DM Sans', sans-serif; font-weight: 600; cursor: pointer; font-size: 0.85rem; margin-top: 0.8rem; transition: all 0.2s; }
        .btn-cancel:hover { border-color: var(--danger); color: var(--danger); }

        /* SPINNER */
        .spinner-overlay { display: none; position: fixed; inset: 0; background: rgba(13,61,43,0.5); backdrop-filter: blur(4px); z-index: 2000; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; }
        .spinner-overlay.open { display: flex; }
        .spinner { width: 48px; height: 48px; border: 4px solid var(--border); border-top-color: var(--green-mid); border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner-text { font-size: 0.9rem; color: var(--white); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo"><div class="logo-icon">T</div>TontineTD</div>
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
    <button class="nav-item" onclick="showSection('securite', this)">Sécurité</button>

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
            <div class="panel-header"><div class="panel-title">Ajouter une cotisation</div></div>
            <div class="panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Montant (F CFA)</label>
                        <input type="number" id="cotis_montant" class="form-control" placeholder="Ex: 5000" min="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" id="cotis_date" class="form-control">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="ouvrirModalPaiement()">Ajouter</button>
                </div>
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
                    <thead><tr><th>ID</th><th>Montant</th><th>Date</th><th>Moyen</th><th>Ajouté par</th></tr></thead>
                    <tbody>
                        @foreach($membre->cotisations as $c)
                        <tr>
                            <td>#{{ $c->id }}</td>
                            <td>{{ number_format($c->montant, 0, ',', ' ') }} F CFA</td>
                            <td>{{ \Carbon\Carbon::parse($c->date_cotisation)->format('d/m/Y') }}</td>
                            <td>
                                @if(isset($c->moyen_paiement) && $c->moyen_paiement === 'wave')
                                    <span class="badge-wave">Wave</span>
                                @elseif(isset($c->moyen_paiement) && $c->moyen_paiement === 'orange')
                                    <span class="badge-orange">Orange Money</span>
                                @else
                                    <span class="badge-success">Cash</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($c->ajout_par) && $c->ajout_par === 'membre')
                                <span class="badge-membre">Moi</span>
                                @else
                                <span class="badge-admin">Admin</span>
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

    <!-- SECURITE -->
    <div class="section" id="section-securite">

        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->has('ancien_password'))
        <div class="alert-error">{{ $errors->first('ancien_password') }}</div>
        @endif

        <div class="panel">
            <div class="panel-header"><div class="panel-title">Changer mon mot de passe</div></div>
            <div class="panel-body">
                <form method="POST" action="/changer-password">
                    @csrf
                    <div class="form-stack">
                        <div class="form-group">
                            <label class="form-label">Ancien mot de passe</label>
                            <input type="password" name="ancien_password" class="form-control-full" placeholder="Ancien mot de passe" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control-full" placeholder="Min. 6 caractères" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer</label>
                            <input type="password" name="password_confirmation" class="form-control-full" placeholder="Confirmer" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:fit-content">Modifier le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<!-- MODAL PAIEMENT -->
<div class="modal-overlay" id="modalPaiement">
    <div class="modal">
        <div class="modal-title">Choisir le moyen de paiement</div>

        <div class="pay-methods-grid">
            <div class="pay-method-card" id="card-wave" onclick="selectMethod('wave')">
                <div class="pay-method-icon" style="background:var(--wave-light);color:var(--wave);">W</div>
                <div class="pay-method-name">Wave</div>
                <div class="pay-check" id="check-wave">✓</div>
            </div>
            <div class="pay-method-card" id="card-orange" onclick="selectMethod('orange')">
                <div class="pay-method-icon" style="background:var(--orange-light);color:var(--orange);font-size:0.7rem;">OM</div>
                <div class="pay-method-name">Orange Money</div>
                <div class="pay-check" id="check-orange">✓</div>
            </div>
            <div class="pay-method-card" id="card-cash" onclick="selectMethod('cash')">
                <div class="pay-method-icon" style="background:var(--success-light);color:var(--success);">F</div>
                <div class="pay-method-name">Cash</div>
                <div class="pay-check" id="check-cash">✓</div>
            </div>
        </div>

        <div class="pay-form-section" id="pay-form-wave">
            <div class="form-group" style="margin-bottom:0.8rem;">
                <label class="form-label">Numéro Wave</label>
                <input type="tel" id="pay_wave_numero" class="form-control-full" placeholder="77 000 00 00">
            </div>
            <button onclick="confirmerPaiement('wave')" class="btn-wave">Payer avec Wave</button>
        </div>

        <div class="pay-form-section" id="pay-form-orange">
            <div class="form-group" style="margin-bottom:0.8rem;">
                <label class="form-label">Numéro Orange Money</label>
                <input type="tel" id="pay_orange_numero" class="form-control-full" placeholder="77 000 00 00">
            </div>
            <button onclick="confirmerPaiement('orange')" class="btn-orange-pay">Payer avec Orange Money</button>
        </div>

        <div class="pay-form-section" id="pay-form-cash">
            <button onclick="confirmerPaiement('cash')" class="btn-cash">Confirmer le paiement en Cash</button>
        </div>

        <button onclick="document.getElementById('modalPaiement').classList.remove('open')" class="btn-cancel">Annuler</button>
    </div>
</div>

<!-- SPINNER -->
<div class="spinner-overlay" id="spinner">
    <div class="spinner"></div>
    <div class="spinner-text" id="spinnerText">Traitement en cours...</div>
</div>

<script>
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    const titles = {
        accueil: 'Mon <span>espace</span>',
        cotisations: 'Mes <span>cotisations</span>',
        notifications: 'Mes <span>notifications</span>',
        securite: 'Ma <span>sécurité</span>',
    };

    function showSection(name, btn) {
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('section-' + name).classList.add('active');
        if (btn) btn.classList.add('active');
        document.getElementById('pageTitle').innerHTML = titles[name] || name;
    }

    @if($errors->has('ancien_password'))
        showSection('securite', document.querySelectorAll('.nav-item')[3]);
    @endif
    @if(session('success') && request()->is('*changer*'))
        showSection('securite', document.querySelectorAll('.nav-item')[3]);
    @endif
    @if(session('success'))
        showSection('cotisations', document.querySelectorAll('.nav-item')[1]);
    @endif

    function ouvrirModalPaiement() {
        const montant = document.getElementById('cotis_montant').value;
        const date = document.getElementById('cotis_date').value;
        if (!montant || montant < 100) { alert('Veuillez entrer un montant valide.'); return; }
        if (!date) { alert('Veuillez choisir une date.'); return; }
        ['wave','orange','cash'].forEach(m => {
            document.getElementById('card-' + m).style.borderColor = 'var(--border)';
            document.getElementById('check-' + m).style.display = 'none';
            document.getElementById('pay-form-' + m).style.display = 'none';
        });
        document.getElementById('modalPaiement').classList.add('open');
    }

    function selectMethod(method) {
        ['wave','orange','cash'].forEach(m => {
            document.getElementById('card-' + m).style.borderColor = 'var(--border)';
            document.getElementById('check-' + m).style.display = 'none';
            document.getElementById('pay-form-' + m).style.display = 'none';
        });
        const colors = { wave: 'var(--wave)', orange: 'var(--orange)', cash: 'var(--green-mid)' };
        document.getElementById('card-' + method).style.borderColor = colors[method];
        document.getElementById('check-' + method).style.display = 'flex';
        document.getElementById('pay-form-' + method).style.display = 'block';
    }

    function confirmerPaiement(method) {
        const montant = document.getElementById('cotis_montant').value;
        const date = document.getElementById('cotis_date').value;
        if (method === 'wave' && !document.getElementById('pay_wave_numero').value) { alert('Veuillez entrer votre numéro Wave.'); return; }
        if (method === 'orange' && !document.getElementById('pay_orange_numero').value) { alert('Veuillez entrer votre numéro Orange Money.'); return; }

        document.getElementById('modalPaiement').classList.remove('open');

        if (method === 'cash') { soumettreFormulaire(montant, date, method); return; }

        const spinner = document.getElementById('spinner');
        const spinnerText = document.getElementById('spinnerText');
        spinner.classList.add('open');
        spinnerText.textContent = method === 'wave' ? 'Connexion à Wave...' : 'Connexion à Orange Money...';
        setTimeout(() => spinnerText.textContent = 'Vérification du solde...', 1000);
        setTimeout(() => spinnerText.textContent = 'Traitement du paiement...', 2000);
        setTimeout(() => { spinner.classList.remove('open'); soumettreFormulaire(montant, date, method); }, 3000);
    }

    function soumettreFormulaire(montant, date, method) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/mon-espace/cotisation';
        const fields = { '_token': '{{ csrf_token() }}', 'montant': montant, 'date_cotisation': date, 'moyen_paiement': method };
        Object.entries(fields).forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = name; input.value = value;
            form.appendChild(input);
        });
        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html>