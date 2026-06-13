<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte refusé — TontineTD</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: #f4f7f4; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .box { background: #fff; border-radius: 20px; padding: 3rem 2.5rem; text-align: center; max-width: 420px; width: 90%; box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
        .icon { font-size: 3.5rem; margin-bottom: 1.2rem; }
        h2 { font-family: 'Playfair Display', serif; color: #e05252; font-size: 1.6rem; margin-bottom: 1rem; }
        p { color: #6b8c7a; font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem; }
        .badge { display: inline-block; background: #fff0f0; border: 1px solid rgba(224,82,82,0.3); color: #e05252; padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
        .btn-logout { display: inline-block; margin-top: 0.5rem; padding: 0.7rem 2rem; background: #0d3d2b; color: #fff; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; }
        .btn-logout:hover { background: #1a6645; }
        .contact { margin-top: 1.2rem; font-size: 0.85rem; color: #6b8c7a; }
        .contact a { color: #1a6645; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">❌</div>
        <div class="badge">Compte refusé</div>
        <h2>Inscription refusée</h2>
        <p>Votre demande d'inscription a été refusée par l'administrateur.</p>
        <p>Si vous pensez qu'il s'agit d'une erreur, contactez l'administrateur pour plus d'informations.</p>
        <div class="contact">Besoin d'aide ? <a href="mailto:admin@tontinetd.sn">admin@tontinetd.sn</a></div>
        <a href="/logout" class="btn-logout">Se déconnecter</a>
    </div>
</body>
</html>