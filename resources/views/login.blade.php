<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0e1a; --surface: #111827; --surface2: #1a2235; --border: #1f2d45; --accent: #f0a500; --accent2: #00c2a8; --text: #e8edf5; --muted: #6b7a99; --danger: #e05252; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; }
        .logo { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--accent); text-align: center; margin-bottom: 0.3rem; }
        .logo span { color: var(--accent2); }
        .subtitle { text-align: center; color: var(--muted); font-size: 0.85rem; margin-bottom: 2rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .form-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
        .form-control { background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 0.9rem; outline: none; transition: border-color 0.2s; width: 100%; }
        .form-control:focus { border-color: var(--accent); }
        .btn { width: 100%; padding: 0.8rem; border-radius: 10px; border: none; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer; background: var(--accent); color: #0a0e1a; transition: all 0.2s; margin-top: 0.5rem; }
        .btn:hover { background: #ffc034; transform: translateY(-1px); }
        .error { background: rgba(224,82,82,0.1); border: 1px solid rgba(224,82,82,0.3); color: var(--danger); padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1rem; }
        .admin-link { text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: var(--muted); }
        .admin-link a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Tontine<span>TD</span></div>
        <div class="subtitle">Connectez-vous à votre espace membre</div>

        @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="/login" method="post">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <div class="admin-link">
            Administrateur ? <a href="/">Accès dashboard</a>
        </div>
    </div>
</body>
</html>