<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres - TontineTD</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0e1a; --surface: #111827; --surface2: #1a2235; --border: #1f2d45; --accent: #f0a500; --accent2: #00c2a8; --text: #e8edf5; --muted: #6b7a99; --danger: #e05252; --success: #3ecf8e; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; padding: 2rem 2.5rem; }

        .page-title { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; margin-bottom: 2rem; }
        .page-title span { color: var(--accent); }

        .layout { display: grid; grid-template-columns: 380px 1fr; gap: 2rem; align-items: start; }

        .form-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem; }
        .form-card h2 { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; margin-bottom: 1.2rem; color: var(--accent); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: 0.4rem; }
        .form-group input { width: 100%; padding: 0.75rem 1rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 0.9rem; outline: none; transition: border-color 0.2s; }
        .form-group input:focus { border-color: var(--accent); }
        .form-group input::placeholder { color: var(--muted); }
        .btn-submit { width: 100%; padding: 0.85rem; background: var(--accent); color: #0a0e1a; border: none; border-radius: 8px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: opacity 0.2s; margin-top: 0.5rem; }
        .btn-submit:hover { opacity: 0.85; }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        th { padding: 0.8rem 1rem; text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); border-bottom: 1px solid var(--border); }
        td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--surface2); }

        .membre-link { color: var(--accent); text-decoration: none; font-weight: 600; font-family: 'Syne', sans-serif; }
        .membre-link:hover { text-decoration: underline; }

        .btn-voir { padding: 0.35rem 0.9rem; background: rgba(240,165,0,0.15); color: var(--accent); border: 1px solid rgba(240,165,0,0.3); border-radius: 6px; font-size: 0.78rem; font-family: 'Syne', sans-serif; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .btn-voir:hover { background: var(--accent); color: #0a0e1a; }

        .back-btn { display: inline-block; margin-bottom: 1.5rem; padding: 0.5rem 1.2rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; color: var(--muted); text-decoration: none; font-size: 0.85rem; }
        .back-btn:hover { color: var(--text); }

        .alert-success { background: rgba(62,207,142,0.1); border: 1px solid rgba(62,207,142,0.3); color: var(--success); padding: 0.8rem 1.2rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-password { background: rgba(240,165,0,0.1); border: 1px solid rgba(240,165,0,0.4); color: var(--accent); padding: 0.8rem 1.2rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600; }
    </style>
</head>
<body>

    <a href="/dashboard" class="back-btn">← Tableau de bord</a>

    <div class="page-title">Gestion des <span>Membres</span></div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="layout">

        <!-- FORMULAIRE AJOUT -->
        <div class="form-card">
            <h2>Ajouter un membre</h2>
            <form action="/membre" method="post">
                @csrf
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Nom" required>
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Prénom" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" placeholder="Ex: 77 123 45 67" required>
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" name="adresse" placeholder="Adresse" required>
                </div>
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" required>
                </div>
                <button type="submit" class="btn-submit">Ajouter le membre</button>
            </form>
        </div>

        <!-- TABLEAU MEMBRES -->
        <div class="panel">
            <div class="panel-header">Liste des membres ({{ count($membres) }})</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Date Naissance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($membres as $membre)
                        <tr>
                            <td>#{{ $membre->id }}</td>
                            <td>
                                <a href="/admin/membre/{{ $membre->id }}" class="membre-link">
                                    {{ $membre->nom }}
                                </a>
                            </td>
                            <td>{{ $membre->prenom }}</td>
                            <td>{{ $membre->email }}</td>
                            <td>{{ $membre->telephone }}</td>
                            <td>{{ $membre->adresse }}</td>
                            <td>{{ \Carbon\Carbon::parse($membre->date_naissance)->format('d/m/Y') }}</td>
                            <td>
                                <a href="/admin/membre/{{ $membre->id }}" class="btn-voir">Voir profil</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>