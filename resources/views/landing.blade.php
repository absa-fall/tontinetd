<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>TontineTD — Gérez votre tontine</title>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
   <style>
       :root {
           --green-dark: #0d3d2b;
           --green-mid: #1a6645;
           --green-light: #2d9e68;
           --green-bright: #3ecf8e;
           --gold: #f0a500;
           --gold-light: #ffd166;
           --cream: #fdf6e3;
           --white: #ffffff;
           --text-dark: #0a1f14;
       }

       * { margin: 0; padding: 0; box-sizing: border-box; }

       body {
           font-family: 'DM Sans', sans-serif;
           background: var(--green-dark);
           color: var(--white);
           overflow-x: hidden;
       }

       body::before {
           content: '';
           position: fixed;
           inset: 0;
           background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
           pointer-events: none;
           z-index: 1000;
           opacity: 0.4;
       }

       /* NAVBAR */
       nav {
           position: fixed;
           top: 0; left: 0; right: 0;
           z-index: 100;
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 1.2rem 4rem;
           background: rgba(13, 61, 43, 0.85);
           backdrop-filter: blur(12px);
           border-bottom: 1px solid rgba(62, 207, 142, 0.15);
       }

       .nav-logo {
           display: flex;
           align-items: center;
           gap: 0.6rem;
           font-family: 'Playfair Display', serif;
           font-size: 1.4rem;
           font-weight: 700;
           color: var(--white);
           text-decoration: none;
       }

       .nav-logo-icon {
           width: 36px;
           height: 36px;
           background: var(--gold);
           border-radius: 8px;
           display: flex;
           align-items: center;
           justify-content: center;
           font-size: 1rem;
           color: var(--text-dark);
           font-weight: 900;
       }

       .nav-links {
           display: flex;
           align-items: center;
           gap: 2rem;
           list-style: none;
       }

       .nav-links a {
           color: rgba(255,255,255,0.7);
           text-decoration: none;
           font-size: 0.9rem;
           font-weight: 500;
           transition: color 0.2s;
       }

       .nav-links a:hover { color: var(--white); }

       .btn-nav-login {
           background: var(--gold);
           color: var(--text-dark);
           padding: 0.6rem 1.5rem;
           border-radius: 999px;
           font-weight: 700;
           font-size: 0.88rem;
           text-decoration: none;
           transition: all 0.2s;
       }

       .btn-nav-login:hover {
           background: var(--gold-light);
           transform: translateY(-1px);
           box-shadow: 0 8px 24px rgba(240,165,0,0.3);
           color: var(--text-dark);
       }

       /* HERO */
       .hero {
           min-height: 100vh;
           display: flex;
           align-items: center;
           position: relative;
           overflow: hidden;
           padding: 8rem 4rem 4rem;
       }

       .hero::before {
           content: '';
           position: absolute;
           inset: 0;
           background:
               radial-gradient(ellipse 80% 80% at 70% 50%, rgba(45, 158, 104, 0.35) 0%, transparent 60%),
               radial-gradient(ellipse 60% 80% at 20% 80%, rgba(13, 61, 43, 0.8) 0%, transparent 70%),
               linear-gradient(135deg, #0d3d2b 0%, #1a6645 40%, #0f4d35 100%);
           z-index: 0;
       }

       .hero-circle-1 {
           position: absolute;
           width: 600px; height: 600px;
           border-radius: 50%;
           border: 1px solid rgba(62, 207, 142, 0.1);
           top: 50%; right: -100px;
           transform: translateY(-50%);
           z-index: 1;
           animation: rotateSlow 30s linear infinite;
       }

       .hero-circle-2 {
           position: absolute;
           width: 400px; height: 400px;
           border-radius: 50%;
           border: 1px solid rgba(240, 165, 0, 0.12);
           top: 50%; right: 50px;
           transform: translateY(-50%);
           z-index: 1;
           animation: rotateSlow 20s linear infinite reverse;
       }

       @keyframes rotateSlow {
           from { transform: translateY(-50%) rotate(0deg); }
           to { transform: translateY(-50%) rotate(360deg); }
       }

       .hero-visual {
           position: absolute;
           right: 6%; top: 50%;
           transform: translateY(-50%);
           width: 420px; height: 520px;
           z-index: 2;
           animation: floatY 6s ease-in-out infinite;
       }

       @keyframes floatY {
           0%, 100% { transform: translateY(-50%); }
           50% { transform: translateY(calc(-50% - 12px)); }
       }

       .hero-visual svg { width: 100%; height: 100%; filter: drop-shadow(0 20px 60px rgba(0,0,0,0.4)); }

       .hero-content {
           position: relative;
           z-index: 3;
           max-width: 600px;
       }

       .hero-tag {
           display: inline-flex;
           align-items: center;
           gap: 0.5rem;
           background: rgba(62, 207, 142, 0.12);
           border: 1px solid rgba(62, 207, 142, 0.3);
           padding: 0.4rem 1rem;
           border-radius: 999px;
           font-size: 0.82rem;
           color: var(--green-bright);
           margin-bottom: 1.5rem;
           animation: fadeUp 0.6s ease both;
       }

       .hero-tag::before {
           content: '';
           width: 6px; height: 6px;
           border-radius: 50%;
           background: var(--green-bright);
           animation: pulse 2s infinite;
       }

       @keyframes pulse {
           0%, 100% { opacity: 1; transform: scale(1); }
           50% { opacity: 0.5; transform: scale(1.3); }
       }

       .hero-title {
           font-family: 'Playfair Display', serif;
           font-size: clamp(2.8rem, 5vw, 4.2rem);
           line-height: 1.1;
           margin-bottom: 0.5rem;
           animation: fadeUp 0.6s 0.1s ease both;
       }

       .hero-title em { font-style: italic; color: var(--gold); }

       .hero-sub {
           font-size: 1rem;
           color: rgba(255,255,255,0.65);
           line-height: 1.7;
           margin-bottom: 2.5rem;
           max-width: 480px;
           animation: fadeUp 0.6s 0.2s ease both;
       }

       .hero-btns {
           display: flex;
           gap: 1rem;
           flex-wrap: wrap;
           animation: fadeUp 0.6s 0.3s ease both;
       }

       .btn-primary {
           background: var(--gold);
           color: var(--text-dark);
           padding: 0.9rem 2.2rem;
           border-radius: 999px;
           font-weight: 700;
           font-size: 0.95rem;
           text-decoration: none;
           transition: all 0.3s;
           box-shadow: 0 4px 20px rgba(240,165,0,0.25);
       }

       .btn-primary:hover {
           background: var(--gold-light);
           transform: translateY(-2px);
           box-shadow: 0 10px 30px rgba(240,165,0,0.4);
           color: var(--text-dark);
       }

       .btn-outline {
           background: transparent;
           color: var(--white);
           padding: 0.9rem 2.2rem;
           border-radius: 999px;
           font-weight: 600;
           font-size: 0.95rem;
           text-decoration: none;
           border: 1px solid rgba(255,255,255,0.3);
           transition: all 0.3s;
       }

       .btn-outline:hover {
           background: rgba(255,255,255,0.08);
           border-color: rgba(255,255,255,0.6);
       }

       .hero-stats {
           display: flex;
           gap: 2.5rem;
           margin-top: 3.5rem;
           animation: fadeUp 0.6s 0.4s ease both;
       }

       .stat-num {
           font-family: 'Playfair Display', serif;
           font-size: 2rem;
           font-weight: 700;
           color: var(--gold);
       }

       .stat-label {
           font-size: 0.78rem;
           color: rgba(255,255,255,0.5);
           text-transform: uppercase;
           letter-spacing: 1px;
       }

       @keyframes fadeUp {
           from { opacity: 0; transform: translateY(24px); }
           to { opacity: 1; transform: translateY(0); }
       }

       /* FEATURES */
       .features {
           padding: 6rem 4rem;
           background: #0a2d1f;
           position: relative;
       }

       .features::before {
           content: '';
           position: absolute;
           top: 0; left: 0; right: 0;
           height: 1px;
           background: linear-gradient(90deg, transparent, rgba(62,207,142,0.3), transparent);
       }

       .section-tag {
           font-size: 0.75rem;
           text-transform: uppercase;
           letter-spacing: 3px;
           color: var(--green-bright);
           margin-bottom: 1rem;
       }

       .section-title {
           font-family: 'Playfair Display', serif;
           font-size: clamp(1.8rem, 3vw, 2.8rem);
           margin-bottom: 3.5rem;
           max-width: 500px;
       }

       .section-title em { font-style: italic; color: var(--gold); }

       .features-grid {
           display: grid;
           grid-template-columns: repeat(3, 1fr);
           gap: 1.5rem;
       }

       .feature-card {
           background: rgba(255,255,255,0.04);
           border: 1px solid rgba(62,207,142,0.12);
           border-radius: 16px;
           padding: 2rem;
           transition: all 0.3s;
           position: relative;
           overflow: hidden;
       }

       .feature-card::before {
           content: '';
           position: absolute;
           top: 0; left: 0; right: 0;
           height: 2px;
           background: linear-gradient(90deg, var(--green-bright), var(--gold));
           opacity: 0;
           transition: opacity 0.3s;
       }

       .feature-card:hover {
           background: rgba(62,207,142,0.06);
           border-color: rgba(62,207,142,0.25);
           transform: translateY(-4px);
       }

       .feature-card:hover::before { opacity: 1; }

       .feature-icon {
           width: 48px; height: 48px;
           border-radius: 12px;
           background: rgba(240,165,0,0.12);
           display: flex;
           align-items: center;
           justify-content: center;
           margin-bottom: 1.2rem;
           font-size: 1.4rem;
       }

       .feature-title {
           font-family: 'Playfair Display', serif;
           font-size: 1.1rem;
           margin-bottom: 0.6rem;
       }

       .feature-desc {
           font-size: 0.85rem;
           color: rgba(255,255,255,0.55);
           line-height: 1.7;
       }

       /* CTA */
       .cta {
           padding: 6rem 4rem;
           text-align: center;
           position: relative;
           overflow: hidden;
       }

       .cta::before {
           content: '';
           position: absolute;
           inset: 0;
           background: radial-gradient(ellipse 80% 60% at 50% 50%, rgba(45,158,104,0.2) 0%, transparent 70%);
       }

       .cta-title {
           font-family: 'Playfair Display', serif;
           font-size: clamp(2rem, 4vw, 3.5rem);
           margin-bottom: 1rem;
           position: relative;
       }

       .cta-title em { font-style: italic; color: var(--gold); }

       .cta-sub {
           color: rgba(255,255,255,0.6);
           font-size: 1rem;
           margin-bottom: 2.5rem;
           position: relative;
       }

       .cta-btns {
           display: flex;
           gap: 1rem;
           justify-content: center;
           position: relative;
       }

       /* FOOTER */
       footer {
           padding: 2rem 4rem;
           border-top: 1px solid rgba(255,255,255,0.06);
           display: flex;
           align-items: center;
           justify-content: space-between;
           font-size: 0.8rem;
           color: rgba(255,255,255,0.3);
       }

       footer a { color: var(--green-bright); text-decoration: none; }
   </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
   <a href="/" class="nav-logo">
       <div class="nav-logo-icon">T</div>
       TontineTD
   </a>
   <ul class="nav-links">
       <li><a href="#fonctionnalites">Fonctionnalités</a></li>
       <li><a href="#comment">Comment ça marche</a></li>
       <li><a href="/login" class="btn-nav-login">Se connecter</a></li>
   </ul>
</nav>

<!-- HERO -->
<section class="hero">
   <div class="hero-circle-1"></div>
   <div class="hero-circle-2"></div>

   <div class="hero-visual">
       <svg viewBox="0 0 420 520" xmlns="http://www.w3.org/2000/svg">
           <defs>
               <radialGradient id="glow" cx="50%" cy="50%" r="50%">
                   <stop offset="0%" stop-color="#3ecf8e" stop-opacity="0.3"/>
                   <stop offset="100%" stop-color="#0d3d2b" stop-opacity="0"/>
               </radialGradient>
               <linearGradient id="figGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                   <stop offset="0%" stop-color="#2d9e68"/>
                   <stop offset="100%" stop-color="#1a6645"/>
               </linearGradient>
               <linearGradient id="coinGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                   <stop offset="0%" stop-color="#f0a500"/>
                   <stop offset="100%" stop-color="#ffd166"/>
               </linearGradient>
           </defs>
           <ellipse cx="210" cy="260" rx="200" ry="240" fill="url(#glow)"/>
           <ellipse cx="210" cy="160" rx="75" ry="85" fill="url(#figGrad)" opacity="0.9"/>
           <path d="M100 520 Q100 320 210 280 Q320 320 320 520 Z" fill="url(#figGrad)" opacity="0.85"/>
           <ellipse cx="185" cy="135" rx="20" ry="25" fill="rgba(255,255,255,0.12)"/>
           <g transform="translate(310, 180)">
               <circle r="28" fill="url(#coinGrad)" opacity="0.95"/>
               <text x="0" y="6" text-anchor="middle" font-size="18" fill="#0a1f14" font-weight="bold">F</text>
           </g>
           <g transform="translate(80, 220)">
               <circle r="20" fill="url(#coinGrad)" opacity="0.7"/>
               <text x="0" y="5" text-anchor="middle" font-size="13" fill="#0a1f14" font-weight="bold">F</text>
           </g>
           <g transform="translate(340, 300)">
               <circle r="14" fill="url(#coinGrad)" opacity="0.5"/>
           </g>
           <circle cx="210" cy="260" r="180" fill="none" stroke="rgba(62,207,142,0.12)" stroke-width="1"/>
           <circle cx="210" cy="260" r="130" fill="none" stroke="rgba(240,165,0,0.1)" stroke-width="1" stroke-dasharray="8 6"/>
           <g transform="translate(30, 340)">
               <rect width="150" height="70" rx="12" fill="rgba(13,61,43,0.9)" stroke="rgba(62,207,142,0.3)" stroke-width="1"/>
               <text x="15" y="24" font-size="9" fill="rgba(255,255,255,0.5)" font-family="sans-serif">TOTAL COLLECTÉ</text>
               <text x="15" y="48" font-size="20" fill="#f0a500" font-family="serif" font-weight="bold">52 500 F</text>
           </g>
           <g transform="translate(240, 70)">
               <rect width="130" height="38" rx="19" fill="rgba(62,207,142,0.15)" stroke="rgba(62,207,142,0.4)" stroke-width="1"/>
               <circle cx="20" cy="19" r="5" fill="#3ecf8e"/>
               <text x="32" y="24" font-size="11" fill="#3ecf8e" font-family="sans-serif">Tontine active</text>
           </g>
       </svg>
   </div>

   <div class="hero-content">
       <div class="hero-tag">Solution n°1 des tontines au Sénégal</div>

       <h1 class="hero-title">
           Gérez votre tontine<br>
           <em>en toute simplicité</em>
       </h1>

       <p class="hero-sub">
           Cotisations, tours, membres — tout en un seul endroit.
           Suivi automatique, transparence totale pour votre groupement.
       </p>

       <div class="hero-btns">
           <a href="/login" class="btn-primary">Accéder à mon espace</a>
           <a href="#fonctionnalites" class="btn-outline">Découvrir</a>
       </div>

       <div class="hero-stats">
           <div class="stat-item">
               <div class="stat-num">100%</div>
               <div class="stat-label">Transparent</div>
           </div>
           <div class="stat-item">
               <div class="stat-num">0 F</div>
               <div class="stat-label">Commission</div>
           </div>
           <div class="stat-item">
               <div class="stat-num">24/7</div>
               <div class="stat-label">Accessible</div>
           </div>
       </div>
   </div>
</section>

<!-- FEATURES -->
<section class="features" id="fonctionnalites">
   <div class="section-tag">Fonctionnalités</div>
   <h2 class="section-title">Tout ce dont vous avez <em>besoin</em></h2>

   <div class="features-grid">
       <div class="feature-card">
           <div class="feature-icon">👥</div>
           <div class="feature-title">Gestion des membres</div>
           <div class="feature-desc">Ajoutez, modifiez et suivez tous les membres de votre groupement facilement.</div>
       </div>
       <div class="feature-card">
           <div class="feature-icon">💰</div>
           <div class="feature-title">Suivi des cotisations</div>
           <div class="feature-desc">Enregistrez chaque cotisation et consultez l'historique complet en temps réel.</div>
       </div>
       <div class="feature-card">
           <div class="feature-icon">📅</div>
           <div class="feature-title">Planification des tours</div>
           <div class="feature-desc">Programmez les tours et notifiez automatiquement chaque membre concerné.</div>
       </div>
       <div class="feature-card">
           <div class="feature-icon">📄</div>
           <div class="feature-title">Export PDF & Excel</div>
           <div class="feature-desc">Téléchargez vos relevés de transactions en PDF ou Excel en un seul clic.</div>
       </div>
       <div class="feature-card">
           <div class="feature-icon">🔔</div>
           <div class="feature-title">Notifications</div>
           <div class="feature-desc">Chaque membre reçoit une notification lors d'un nouveau tour programmé.</div>
       </div>
       <div class="feature-card">
           <div class="feature-icon">🔒</div>
           <div class="feature-title">Espace sécurisé</div>
           <div class="feature-desc">Chaque membre accède uniquement à ses propres données via son compte.</div>
       </div>
   </div>
</section>

<!-- CTA -->
<section class="cta" id="comment">
   <h2 class="cta-title">Prêt à gérer votre<br><em>tontine simplement ?</em></h2>
   <p class="cta-sub">Rejoignez votre groupement dès maintenant</p>
   <div class="cta-btns">
       <a href="/login" class="btn-primary">Se connecter</a>
   </div>
</section>

<!-- FOOTER -->
<footer>
   <div>© 2026 TontineTD — Tous droits réservés</div>
   <div>Fait avec ❤️ au Sénégal</div>
</footer>

</body>
</html>