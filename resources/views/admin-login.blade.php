<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --green-bright: #3ecf8e;
            --gold: #f0a500; --gold-light: #ffd166;
            --white: #ffffff; --bg: #f4f7f4;
            --surface: #ffffff; --border: #e2ece8;
            --text: #1a2e22; --muted: #6b8c7a;
            --danger: #e05252;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* LEFT PANEL */
        .left-panel {
            width: 45%;
            background: var(--green-dark);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 30% 40%, rgba(45,158,104,0.4) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 80%, rgba(13,61,43,0.6) 0%, transparent 70%);
        }

        /* Grid pattern overlay */
        .left-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .left-content { position: relative; z-index: 1; }

        .left-logo { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 3rem; text-decoration: none; }
        .logo-icon { width: 48px; height: 48px; background: var(--gold); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 700; color: var(--green-dark); box-shadow: 0 4px 20px rgba(240,165,0,0.3); }
        .logo-text { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--white); }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(240,165,0,0.12);
            border: 1px solid rgba(240,165,0,0.3);
            padding: 0.4rem 1rem;
            border-radius: 999px;
            font-size: 0.78rem;
            color: var(--gold);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        .admin-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--gold); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(1.3); } }

        .left-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; line-height: 1.2; color: var(--white); margin-bottom: 1rem; }
        .left-title em { font-style: italic; color: var(--gold); }
        .left-sub { color: rgba(255,255,255,0.6); font-size: 0.95rem; line-height: 1.7; margin-bottom: 2.5rem; }

        .admin-features { display: flex; flex-direction: column; gap: 1rem; }
        .feature-item { display: flex; align-items: center; gap: 0.8rem; color: rgba(255,255,255,0.75); font-size: 0.9rem; }
        .feature-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }

        /* RIGHT PANEL */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg);
        }

        .form-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 40px rgba(13,61,43,0.08);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green-bright), var(--gold));
        }

        .form-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--text); margin-bottom: 0.4rem; }
        .form-sub { font-size: 0.88rem; color: var(--muted); margin-bottom: 2rem; }

        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.2rem; }
        .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text); text-transform: uppercase; letter-spacing: 0.5px; }

        .form-control {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s;
            width: 100%;
        }

        .form-control:focus { border-color: var(--green-mid); box-shadow: 0 0 0 3px rgba(26,102,69,0.08); }

        .error-box { background: #fff0f0; border: 1px solid rgba(224,82,82,0.2); color: var(--danger); padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.2rem; }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            border-radius: 10px;
            border: none;
            background: var(--green-dark);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-submit:hover { background: var(--green-mid); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13,61,43,0.2); }

        .security-note {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-top: 1.5rem;
            padding: 0.8rem 1rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .back-link { display: block; text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--muted); text-decoration: none; transition: color 0.2s; }
        .back-link:hover { color: var(--green-mid); }
    </style>
</head>
<body>

<!-- LEFT -->
<div class="left-panel">
    <div class="left-content">
        <a href="/" class="left-logo">
            <div class="logo-icon">T</div>
            <div class="logo-text">TontineTD</div>
        </a>
        <div class="admin-badge">Espace Administrateur</div>
        <h1 class="left-title">Tableau de bord<br><em>administrateur</em></h1>
        <p class="left-sub">Gérez tous les membres, tontines, cotisations et tours depuis votre espace dédié.</p>
        <div class="admin-features">
            <div class="feature-item"><div class="feature-dot"></div>Gestion complète des membres</div>
            <div class="feature-item"><div class="feature-dot"></div>Création et suivi des tontines</div>
            <div class="feature-item"><div class="feature-dot"></div>Export PDF & Excel global</div>
            <div class="feature-item"><div class="feature-dot"></div>Notifications aux membres</div>
        </div>
    </div>
</div>

<!-- RIGHT -->
<div class="right-panel">
    <div class="form-card">
        <div class="form-title">Connexion admin</div>
        <div class="form-sub">Accès réservé aux administrateurs</div>

        @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/login" method="post">
            @csrf
            <div class="form-group">
                <label class="form-label">Email administrateur</label>
                <input type="email" name="email" class="form-control" placeholder="admin@tontinetd.sn" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Accéder au dashboard</button>
        </form>

        <div class="security-note">
             Accès sécurisé — Réservé aux administrateurs autorisés
        </div>

        <a href="/login" class="back-link">← Retour espace membre</a>
    </div>
</div>

</body>
</html>