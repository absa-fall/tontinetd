<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b;
            --green-mid: #1a6645;
            --green-bright: #3ecf8e;
            --gold: #f0a500;
            --white: #ffffff;
            --bg: #f4f7f4;
            --surface: #ffffff;
            --border: #e2ece8;
            --text: #1a2e22;
            --muted: #6b8c7a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

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

        .left-content { position: relative; z-index: 1; }

        .left-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 3rem;
        }

        .logo-icon {
            width: 44px; height: 44px;
            background: var(--gold);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--green-dark);
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
        }

        .left-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            line-height: 1.2;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .left-title em { font-style: italic; color: var(--gold); }

        .left-sub {
            color: rgba(255,255,255,0.65);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .left-features { display: flex; flex-direction: column; gap: 1rem; }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
        }

        .feature-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--green-bright);
            flex-shrink: 0;
        }

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
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .form-sub {
            font-size: 0.88rem;
            color: var(--muted);
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-bottom: 1.1rem;
        }

        .form-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--green-mid);
            box-shadow: 0 0 0 3px rgba(26,102,69,0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            border-radius: 10px;
            border: none;
            background: var(--green-dark);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: var(--green-mid);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13,61,43,0.2);
        }

        .error-box {
            background: #fff0f0;
            border: 1px solid #fcc;
            color: #c0392b;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .form-footer a {
            color: var(--green-mid);
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<!-- LEFT -->
<div class="left-panel">
    <div class="left-content">
        <div class="left-logo">
            <div class="logo-icon">T</div>
            <div class="logo-text">TontineTD</div>
        </div>
        <h1 class="left-title">Bienvenue sur<br><em>votre tontine</em></h1>
        <p class="left-sub">Gérez vos cotisations, suivez vos tours et restez connecté avec votre groupement.</p>
        <div class="left-features">
            <div class="feature-item"><div class="feature-dot"></div>Suivi des cotisations en temps réel</div>
            <div class="feature-item"><div class="feature-dot"></div>Notifications de tours automatiques</div>
            <div class="feature-item"><div class="feature-dot"></div>Export PDF & Excel de vos transactions</div>
            <div class="feature-item"><div class="feature-dot"></div>Paiement Wave & Orange Money</div>
        </div>
    </div>
</div>

<!-- RIGHT -->
<div class="right-panel">
    <div class="form-card">
        <div class="form-title">Se connecter</div>
        <div class="form-sub">Accédez à votre espace membre</div>

        @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form action="/login" method="post">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="votre@email.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Se connecter</button>
</form>

<div style="text-align:center;margin-top:1.2rem;font-size:0.85rem;">
    Mot de passe oublié ? <a href="/#contact" style="color:var(--green-mid);font-weight:600;text-decoration:none;">Voir les contacts de l'administrateur</a>
</div>
        <div class="form-footer">
            Pas encore de compte ? <a href="/register">S'inscrire</a>
        </div>
    </div>
</div>

</body>
</html>