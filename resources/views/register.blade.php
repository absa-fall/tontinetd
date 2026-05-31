<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Inscription</title>
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
            --danger: #c0392b;
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
            width: 38%;
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
            background: radial-gradient(ellipse 80% 60% at 30% 40%, rgba(45,158,104,0.4) 0%, transparent 60%);
        }

        .left-content { position: relative; z-index: 1; }

        .left-logo { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 3rem; }

        .logo-icon {
            width: 44px; height: 44px;
            background: var(--gold);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem; font-weight: 700;
            color: var(--green-dark);
        }

        .logo-text { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--white); }

        .left-title { font-family: 'Playfair Display', serif; font-size: 2.2rem; line-height: 1.2; color: var(--white); margin-bottom: 1rem; }
        .left-title em { font-style: italic; color: var(--gold); }
        .left-sub { color: rgba(255,255,255,0.65); font-size: 0.9rem; line-height: 1.7; margin-bottom: 2rem; }

        .left-features { display: flex; flex-direction: column; gap: 0.8rem; }
        .feature-item { display: flex; align-items: center; gap: 0.8rem; color: rgba(255,255,255,0.8); font-size: 0.88rem; }
        .feature-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--green-bright); flex-shrink: 0; }

        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg);
            overflow-y: auto;
        }

        .form-card {
            width: 100%;
            max-width: 520px;
            background: var(--surface);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 40px rgba(13,61,43,0.08);
            border: 1px solid var(--border);
            margin: 2rem 0;
        }

        .form-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--text); margin-bottom: 0.4rem; }
        .form-sub { font-size: 0.88rem; color: var(--muted); margin-bottom: 2rem; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .form-group.full { grid-column: 1 / -1; }

        .form-label { font-size: 0.78rem; font-weight: 600; color: var(--text); text-transform: uppercase; letter-spacing: 0.5px; }

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

        .error-box { background: #fff0f0; border: 1px solid #fcc; color: var(--danger); padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.2rem; }

        .field-error { font-size: 0.78rem; color: var(--danger); margin-top: 0.2rem; }

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

        .btn-submit:hover { background: var(--green-mid); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13,61,43,0.2); }

        .form-footer { text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--muted); }
        .form-footer a { color: var(--green-mid); text-decoration: none; font-weight: 600; }
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
        <h1 class="left-title">Rejoignez votre<br><em>tontine en ligne</em></h1>
        <p class="left-sub">Créez votre compte et accédez à votre espace personnel pour gérer vos cotisations.</p>
        <div class="left-features">
            <div class="feature-item"><div class="feature-dot"></div>Inscription rapide et gratuite</div>
            <div class="feature-item"><div class="feature-dot"></div>Accès immédiat à votre espace</div>
            <div class="feature-item"><div class="feature-dot"></div>Paiement Wave & Orange Money</div>
            <div class="feature-item"><div class="feature-dot"></div>Notifications en temps réel</div>
        </div>
    </div>
</div>

<!-- RIGHT -->
<div class="right-panel">
    <div class="form-card">
        <div class="form-title">Créer un compte</div>
        <div class="form-sub">Rejoignez votre groupement de tontine</div>

        @if($errors->any() && !$errors->has('nom') && !$errors->has('prenom') && !$errors->has('email') && !$errors->has('telephone') && !$errors->has('adresse') && !$errors->has('date_naissance') && !$errors->has('password'))
        <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form action="/register" method="post">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" placeholder="Diallo" value="{{ old('nom') }}" required>
                    @error('nom')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" placeholder="Mamadou" value="{{ old('prenom') }}" required>
                    @error('prenom')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="votre@email.com" value="{{ old('email') }}" required>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" placeholder="77 000 00 00" value="{{ old('telephone') }}" required>
                    @error('telephone')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
                    @error('date_naissance')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" placeholder="Dakar, Sénégal" value="{{ old('adresse') }}" required>
                    @error('adresse')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 caractères" required>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmer</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer" required>
                </div>
            </div>
            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>

        <div class="form-footer">
            Déjà un compte ? <a href="/login">Se connecter</a>
        </div>
    </div>
</div>

</body>
</html>