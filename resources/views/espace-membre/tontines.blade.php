<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Tontines disponibles</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --green-bright: #3ecf8e;
            --gold: #f0a500; --white: #ffffff; --bg: #f4f7f4;
            --surface: #ffffff; --border: #e2ece8; --text: #1a2e22; --muted: #6b8c7a;
            --danger: #e05252; --danger-light: #fff0f0;
            --success: #1a6645; --success-light: #e8f5ee;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        .sidebar { width: 250px; min-height: 100vh; background: var(--green-dark); display: flex; flex-direction: column; padding: 2rem 1.2rem; position: fixed; top: 0; left: 0; bottom: 0; }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--white); margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem; }
        .logo-icon { width: 34px; height: 34px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: var(--green-dark); flex-shrink: 0; }
        .logo-sub { font-size: 0.72rem; color: rgba(255,255,255,0.4); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2rem; padding-left: 0.2rem; }
        .nav-item { display: flex; align-items: center; padding: 0.7rem 1rem; border-radius: 8px; color: rgba(255,255,255,0.6); font-size: 0.9rem; font-weight: 500; transition: all 0.2s; text-decoration: none; margin-bottom: 4px; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: var(--white); }
        .nav-item.active { background: rgba(255,255,255,0.12); color: var(--white); border-left: 3px solid var(--gold); }
        .sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; }
        .btn-logout:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        .main { margin-left: 250px; flex: 1; padding: 2rem 2.5rem; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; }
        .page-title span { color: var(--green-mid); }

        .alert-success { background: var(--success-light); border: 1px solid rgba(26,102,69,0.2); color: var(--success); padding: 0.8rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-error { background: var(--danger-light); border: 1px solid rgba(224,82,82,0.2); color: var(--danger); padding: 0.8rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }

        .tontine-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .tontine-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .tontine-name { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--green-dark); }
        .tontine-info { font-size: 0.85rem; color: var(--muted); margin-bottom: 1rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .tontine-info span { display: flex; align-items: center; gap: 0.3rem; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }
        .membres-title { font-size: 0.75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.6rem; }
        .membres-list { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1rem; }
        .membre-tag { display: inline-flex; align-items: center; gap: 0.3rem; background: var(--bg); border: 1px solid var(--border); border-radius: 999px; padding: 0.3rem 0.8rem; font-size: 0.8rem; }
        .badge-gerant { background: rgba(240,165,0,0.12); color: #b07800; border: 1px solid rgba(240,165,0,0.3); padding: 0.15rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
        .badge-full { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(224,82,82,0.2); padding: 0.2rem 0.8rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-count { background: var(--success-light); color: var(--success); border: 1px solid rgba(26,102,69,0.2); padding: 0.2rem 0.8rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-attente { background: rgba(240,165,0,0.12); color: #b07800; padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600; border: 1px solid rgba(240,165,0,0.3); }
        .badge-membre-ok { background: var(--success-light); color: var(--success); padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600; }
        .badge-refuse { background: var(--danger-light); color: var(--danger); padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600; }

        .btn { padding: 0.55rem 1.2rem; border-radius: 8px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: var(--green-dark); color: var(--white); }
        .btn-primary:hover { background: var(--green-mid); }
        .btn-secondary { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }

        .empty { text-align: center; padding: 3rem; color: var(--muted); }
        .back-link { display: inline-flex; align-items: center; gap: 0.4rem; color: var(--muted); font-size: 0.85rem; text-decoration: none; margin-bottom: 1.5rem; }
        .back-link:hover { color: var(--green-mid); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo"><div class="logo-icon">T</div>TontineTD</div>
    <div class="logo-sub">Espace membre</div>
    <a href="/mon-espace" class="nav-item">Mon espace</a>
    <a href="/tontines/publiques" class="nav-item active">Tontines disponibles</a>
    <div class="sidebar-footer">
        <form action="/logout" method="post">
            @csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>
    </div>
</aside>

<main class="main">
    <pre style="background:#fff;padding:1rem;font-size:0.75rem;">
{{ $tontines->count() }} tontines trouvées
@foreach($tontines as $t)
  - {{ $t->nom }} | statut: {{ $t->statut ?? 'null' }} | type: {{ $t->type ?? 'null' }}
@endforeach
</pre>
    <div class="topbar">
        <div class="page-title">Tontines <span>disponibles</span></div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @forelse($tontines as $tontine)
    @php
        $dejaInscrit  = in_array($tontine->id, $tontinesduMembre);
        $pivotStatut  = null;
        if ($dejaInscrit) {
            $pivotMembre = $tontine->membres->firstWhere('id', $membreId);
            $pivotStatut = $pivotMembre ? $pivotMembre->pivot->statut : null;
        }
        $gerant = $tontine->membresApprouves->firstWhere('pivot.role', 'admin');
    @endphp

    <div class="tontine-card">
        <div class="tontine-header">
            <div class="tontine-name">{{ $tontine->nom }}</div>
            @if($tontine->estPleine())
                <span class="badge-full">Complète</span>
            @else
                <span class="badge-count">{{ $tontine->membresApprouves->count() }} / {{ $tontine->nombre_max_membres }} membres</span>
            @endif
        </div>

        <div class="tontine-info">
            <span> {{ number_format($tontine->montant, 0, ',', ' ') }} FCFA</span>
            <span> {{ ucfirst($tontine->frequence) }}</span>
            <span> {{ \Carbon\Carbon::parse($tontine->date_debut)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($tontine->date_fin)->format('d/m/Y') }}</span>
        </div>

        @if($tontine->description)
        <p style="font-size:0.88rem;color:var(--muted);margin-bottom:1rem;">{{ $tontine->description }}</p>
        @endif

        <hr class="divider">

        {{-- Membres --}}
        <div class="membres-title">Membres ({{ $tontine->membresApprouves->count() }})</div>
        @if($tontine->membresApprouves->isEmpty())
            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:1rem;">Aucun membre pour l'instant.</p>
        @else
        <div class="membres-list">
            @foreach($tontine->membresApprouves as $m)
            <span class="membre-tag">
                {{ $m->prenom }} {{ $m->nom }}
                @if($m->pivot->role === 'admin')
                    <span class="badge-gerant">Gérant</span>
                @endif
            </span>
            @endforeach
        </div>
        @endif

        {{-- Bouton action --}}
        @if($dejaInscrit && $pivotStatut === 'approuve')
            <span class="badge-membre-ok">✓ Vous êtes membre</span>
        @elseif($dejaInscrit && $pivotStatut === 'en_attente')
            <span class="badge-attente">⏳ Demande en cours d'examen...</span>
        @elseif($dejaInscrit && $pivotStatut === 'refuse')
            <span class="badge-refuse">✗ Demande refusée</span>
        @elseif($tontine->estPleine())
            <button class="btn btn-secondary" disabled>Tontine complète</button>
        @else
            <form method="POST" action="/tontine/{{ $tontine->id }}/demander-adhesion">
                @csrf
                <button type="submit" class="btn btn-primary">Demander à rejoindre</button>
            </form>
        @endif
    </div>
    @empty
        <div class="empty">Aucune tontine disponible pour le moment.</div>
    @endforelse
</main>

</body>
</html>