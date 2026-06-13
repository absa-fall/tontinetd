<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte en attente — TontineTD</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: #f4f7f4; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .box { background: #fff; border-radius: 20px; padding: 3rem 2.5rem; text-align: center; max-width: 420px; width: 90%; box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
        .icon { font-size: 3.5rem; margin-bottom: 1.2rem; }
        h2 { font-family: 'Playfair Display', serif; color: #f0a500; font-size: 1.6rem; margin-bottom: 1rem; }
        p { color: #6b8c7a; font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem; }
        .badge { display: inline-block; background: #fff8e6; border: 1px solid rgba(240,165,0,0.3); color: #b07800; padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
        .btn-logout { display: inline-block; margin-top: 0.5rem; padding: 0.7rem 2rem; background: #0d3d2b; color: #fff; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; }
        .btn-logout:hover { background: #1a6645; }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">⏳</div>
        <div class="badge">En attente de validation</div>
        <h2>Compte en attente</h2>
        <p>Votre compte a bien été créé. L'administrateur doit valider votre inscription avant que vous puissiez accéder à votre espace membre.</p>
        <p>Vous recevrez une notification dès que votre compte sera approuvé ou refusé.</p>
        <a href="/logout" class="btn-logout">Se déconnecter</a>
    </div>
</body>
</html>