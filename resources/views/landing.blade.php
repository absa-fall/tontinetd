<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TontineTD — Gérez votre tontine</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark: #0d3d2b; --green-mid: #1a6645; --green-light: #2d9e68;
            --green-bright: #3ecf8e; --gold: #f0a500; --gold-light: #ffd166;
            --white: #ffffff; --text-dark: #0a1f14;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--green-dark); color: var(--white); overflow-x: hidden; }

        /* NAVBAR */
        nav { position: fixed; top: 0; left: 0; right: 0; z-index: 200; display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 4rem; background: rgba(13,61,43,0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(62,207,142,0.15); }
        .nav-logo { display: flex; align-items: center; gap: 0.6rem; font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--white); text-decoration: none; }
        .nav-logo-icon { width: 36px; height: 36px; background: var(--gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: var(--text-dark); font-weight: 900; }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; list-style: none; }
        .nav-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: var(--white); }
        .btn-nav-login { background: var(--gold); color: var(--text-dark) !important; padding: 0.6rem 1.5rem; border-radius: 999px; font-weight: 700 !important; transition: all 0.2s; }
        .btn-nav-login:hover { background: var(--gold-light) !important; transform: translateY(-1px); }
        .btn-nav-register { background: rgba(62,207,142,0.15); color: var(--green-bright) !important; padding: 0.6rem 1.2rem; border-radius: 999px; font-weight: 600 !important; border: 1px solid rgba(62,207,142,0.3); transition: all 0.2s; }
        .btn-nav-register:hover { background: rgba(62,207,142,0.25) !important; }

        /* HERO FULLSCREEN SLIDER */
        .hero { min-height: 100vh; position: relative; overflow: hidden; display: flex; align-items: center; }
        .slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1.2s ease; background-size: cover; background-position: center; }
        .slide.active { opacity: 1; }
        .slide-1 { background-image: url("{{ asset('images/hero1.jpg') }}"); }
        .slide-2 { background-image: url("{{ asset('images/hero2.jpg') }}"); }
        .slide-3 { background-image: url("{{ asset('images/hero3.jpg') }}"); }
        .slide-4 { background-image: url("{{ asset('images/photo1.png') }}"); }
        .slide-5 { background-image: url("{{ asset('images/photo2.png') }}"); }
        .slide-6 { background-image: url("{{ asset('images/photo3.png') }}"); }
        .slide-7 { background-image: url("{{ asset('images/photo4.png') }}"); }
        .slide::after { content: ''; position: absolute; inset: 0; background: linear-gradient(105deg, rgba(10,31,20,0.90) 0%, rgba(13,61,43,0.78) 40%, rgba(13,61,43,0.35) 70%, transparent 100%); }

        .hero-content { position: relative; z-index: 10; max-width: 640px; padding: 8rem 4rem 4rem; }
        .hero-tag { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(62,207,142,0.12); border: 1px solid rgba(62,207,142,0.3); padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.82rem; color: var(--green-bright); margin-bottom: 1.5rem; animation: fadeUp 0.6s ease both; }
        .hero-tag::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--green-bright); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(1.3); } }
        .hero-title { font-family: 'Playfair Display', serif; font-size: clamp(2.8rem, 5vw, 4.2rem); line-height: 1.1; margin-bottom: 0.8rem; animation: fadeUp 0.6s 0.1s ease both; text-shadow: 0 2px 20px rgba(0,0,0,0.3); }
        .hero-title em { font-style: italic; color: var(--gold); }
        .hero-sub { font-size: 1rem; color: rgba(255,255,255,0.8); line-height: 1.7; margin-bottom: 2.5rem; max-width: 480px; animation: fadeUp 0.6s 0.2s ease both; text-shadow: 0 1px 8px rgba(0,0,0,0.3); }
        .hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; animation: fadeUp 0.6s 0.3s ease both; }
        .btn-primary { background: var(--gold); color: var(--text-dark); padding: 0.9rem 2.2rem; border-radius: 999px; font-weight: 700; font-size: 0.95rem; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 20px rgba(240,165,0,0.35); }
        .btn-primary:hover { background: var(--gold-light); transform: translateY(-2px); box-shadow: 0 10px 30px rgba(240,165,0,0.4); color: var(--text-dark); }
        .btn-outline { background: rgba(255,255,255,0.1); color: var(--white); padding: 0.9rem 2.2rem; border-radius: 999px; font-weight: 600; font-size: 0.95rem; text-decoration: none; border: 1px solid rgba(255,255,255,0.35); transition: all 0.3s; backdrop-filter: blur(8px); }
        .btn-outline:hover { background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.7); }
        .btn-green { background: rgba(62,207,142,0.15); color: var(--green-bright); padding: 0.9rem 2.2rem; border-radius: 999px; font-weight: 600; font-size: 0.95rem; text-decoration: none; border: 1px solid rgba(62,207,142,0.3); transition: all 0.3s; }
        .btn-green:hover { background: var(--green-bright); color: var(--text-dark); }
        .hero-stats { display: flex; gap: 2.5rem; margin-top: 3.5rem; animation: fadeUp 0.6s 0.4s ease both; }
        .stat-num { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--gold); }
        .stat-label { font-size: 0.78rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; }

        /* Floating card */
        .float-card { position: absolute; bottom: 5rem; right: 4rem; z-index: 10; background: rgba(10,14,26,0.85); backdrop-filter: blur(12px); border: 1px solid rgba(62,207,142,0.25); border-radius: 14px; padding: 1rem 1.4rem; display: flex; align-items: center; gap: 0.8rem; animation: fadeUp 0.6s 0.5s ease both; }
        .float-card-icon { display: flex; align-items: center; justify-content: center; }
        .float-card-title { font-size: 0.7rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
        .float-card-value { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--gold); font-weight: 700; }

        /* Slider dots */
        .slider-dots { position: absolute; bottom: 2.5rem; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; gap: 0.6rem; }
        .dot { width: 8px; height: 8px; border-radius: 999px; background: rgba(255,255,255,0.35); cursor: pointer; transition: all 0.3s; }
        .dot.active { width: 24px; background: var(--gold); }
        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }

        /* FEATURES */
        .features { padding: 6rem 4rem; background: #0a2d1f; position: relative; }
        .features::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(62,207,142,0.3), transparent); }
        .section-tag { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: var(--green-bright); margin-bottom: 1rem; }
        .section-title { font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 3vw, 2.8rem); margin-bottom: 3.5rem; max-width: 500px; }
        .section-title em { font-style: italic; color: var(--gold); }
        .features-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .feature-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(62,207,142,0.12); border-radius: 16px; padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; }
        .feature-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--green-bright), var(--gold)); opacity: 0; transition: opacity 0.3s; }
        .feature-card:hover { background: rgba(62,207,142,0.06); border-color: rgba(62,207,142,0.25); transform: translateY(-4px); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(240,165,0,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.2rem; }
        .feature-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; margin-bottom: 0.6rem; }
        .feature-desc { font-size: 0.85rem; color: rgba(255,255,255,0.55); line-height: 1.7; }

        /* CONTACT */
        .contact { padding: 6rem 4rem; background: var(--green-dark); position: relative; }
        .contact::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(240,165,0,0.3), transparent); }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        .contact-info h2 { font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 3vw, 2.5rem); margin-bottom: 1rem; }
        .contact-info h2 em { font-style: italic; color: var(--gold); }
        .contact-info p { color: rgba(255,255,255,0.6); font-size: 0.95rem; line-height: 1.7; margin-bottom: 2rem; }
        .contact-item { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .contact-icon { width: 44px; height: 44px; border-radius: 10px; background: rgba(240,165,0,0.12); border: 1px solid rgba(240,165,0,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .contact-text { font-size: 0.9rem; color: rgba(255,255,255,0.8); }
        .contact-text span { display: block; font-size: 0.75rem; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.2rem; }
        .contact-right { background: rgba(255,255,255,0.03); border: 1px solid rgba(62,207,142,0.12); border-radius: 20px; padding: 2.5rem; }

        /* CTA */
        .cta { padding: 6rem 4rem; text-align: center; position: relative; overflow: hidden; }
        .cta::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 80% 60% at 50% 50%, rgba(45,158,104,0.2) 0%, transparent 70%); }
        .cta-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3.5rem); margin-bottom: 1rem; position: relative; }
        .cta-title em { font-style: italic; color: var(--gold); }
        .cta-sub { color: rgba(255,255,255,0.6); font-size: 1rem; margin-bottom: 2.5rem; position: relative; }
        .cta-btns { display: flex; gap: 1rem; justify-content: center; position: relative; flex-wrap: wrap; }

        /* FOOTER */
        footer { padding: 2rem 4rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; color: rgba(255,255,255,0.3); }
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
        <li><a href="#contact">Nous contacter</a></li>
        <li><a href="/register" class="btn-nav-register">S'inscrire</a></li>
        <li><a href="/login" class="btn-nav-login">Se connecter</a></li>
    </ul>
</nav>

<!-- HERO FULLSCREEN SLIDER -->
<section class="hero">
    <div class="slide slide-1 active"></div>
    <div class="slide slide-2"></div>
    <div class="slide slide-3"></div>
    <div class="slide slide-4"></div>
    <div class="slide slide-5"></div>
    <div class="slide slide-6"></div>
    <div class="slide slide-7"></div>

    <div class="hero-content">
        <div class="hero-tag">Solution n°1 des tontines au Sénégal</div>
        <h1 class="hero-title">Gérez votre tontine<br><em>en toute simplicité</em></h1>
        <p class="hero-sub">Cotisations, tours, membres — tout en un seul endroit. Suivi automatique, transparence totale pour votre groupement.</p>
        <div class="hero-btns">
            <a href="/register" class="btn-primary">Rejoindre maintenant</a>
            <a href="/login" class="btn-outline">Se connecter</a>
            <a href="#contact" class="btn-green">Nous contacter</a>
        </div>
        <div class="hero-stats">
            <div class="stat-item"><div class="stat-num">100%</div><div class="stat-label">Transparent</div></div>
            <div class="stat-item"><div class="stat-num">0 F</div><div class="stat-label">Commission</div></div>
            <div class="stat-item"><div class="stat-num">24/7</div><div class="stat-label">Accessible</div></div>
        </div>
    </div>

    <!-- Floating card -->
    <div class="float-card">
        <div class="float-card-icon">
            <!-- Icône argent/portefeuille -->
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="#f0a500">
                <path d="M21 7H3a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-1 9h-3a2 2 0 0 1 0-4h3v4zM3 5h15l-1.5-2H4.5L3 5z"/>
            </svg>
        </div>
        <div>
            <div class="float-card-title">Total collecté</div>
            <div class="float-card-value">52 500 F CFA</div>
        </div>
    </div>

    <!-- Dots -->
    <div class="slider-dots">
        <span class="dot active" onclick="goToSlide(0)"></span>
        <span class="dot" onclick="goToSlide(1)"></span>
        <span class="dot" onclick="goToSlide(2)"></span>
        <span class="dot" onclick="goToSlide(3)"></span>
        <span class="dot" onclick="goToSlide(4)"></span>
        <span class="dot" onclick="goToSlide(5)"></span>
        <span class="dot" onclick="goToSlide(6)"></span>
    </div>
</section>

<!-- FEATURES -->
<section class="features" id="fonctionnalites">
    <div class="section-tag">Fonctionnalités</div>
    <h2 class="section-title">Tout ce dont vous avez <em>besoin</em></h2>
    <div class="features-grid">

        <!-- Cotisations -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="#f0a500">
                    <path d="M21 7H3a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-1 9h-3a2 2 0 0 1 0-4h3v4zM3 5h15l-1.5-2H4.5L3 5z"/>
                </svg>
            </div>
            <div class="feature-title">Suivi des cotisations</div>
            <div class="feature-desc">Enregistrez chaque cotisation et consultez l'historique complet en temps réel.</div>
        </div>

        <!-- Tours -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="#f0a500">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 8h14v11H5V8zm2 3h3v3H7z"/>
                </svg>
            </div>
            <div class="feature-title">Planification des tours</div>
            <div class="feature-desc">Programmez les tours et notifiez automatiquement chaque membre concerné.</div>
        </div>

        <!-- Export PDF/Excel -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="#f0a500">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zM8 13h8v2H8zm0 4h5v2H8z"/>
                </svg>
            </div>
            <div class="feature-title">Export PDF & Excel</div>
            <div class="feature-desc">Téléchargez vos relevés de transactions en PDF ou Excel en un seul clic.</div>
        </div>

        <!-- Wave/Orange Money -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="#f0a500">
                    <path d="M17 1H7a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm-5 20a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5-4H7V4h10v13z"/>
                </svg>
            </div>
            <div class="feature-title">Wave & Orange Money</div>
            <div class="feature-desc">Payez vos cotisations directement via Wave, Orange Money ou en cash.</div>
        </div>

        <!-- Sécurité -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="#f0a500">
                    <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3-9H9V6a3 3 0 0 1 6 0v2z"/>
                </svg>
            </div>
            <div class="feature-title">Espace sécurisé</div>
            <div class="feature-desc">Chaque membre accède uniquement à ses propres données via son compte.</div>
        </div>

    </div>
</section>

<!-- CONTACT -->
<section class="contact" id="contact">
    <div class="contact-grid">
        <div class="contact-info">
            <div class="section-tag">Nous contacter</div>
            <h2>Besoin d'aide ou <em>d'informations ?</em></h2>
            <p>Contactez l'administrateur pour rejoindre un groupe de tontine existant ou pour toute question sur la plateforme.</p>
            <div class="contact-item">
                <div class="contact-icon">
                    <!-- Email -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="#f0a500">
                        <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </div>
                <div class="contact-text"><span>Email</span>admin@tontinetd.sn</div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <!-- Téléphone -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="#f0a500">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                    </svg>
                </div>
                <div class="contact-text"><span>Téléphone / WhatsApp</span>+221 77 000 00 00</div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <!-- Localisation -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="#f0a500">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/>
                    </svg>
                </div>
                <div class="contact-text"><span>Localisation</span>Dakar, Sénégal</div>
            </div>
        </div>
        <div class="contact-right">
            <div class="section-tag">Ou inscrivez-vous</div>
            <h2 class="section-title" style="margin-bottom:1rem;">Rejoignez votre <em>tontine</em></h2>
            <p style="color:rgba(255,255,255,0.6);font-size:0.95rem;line-height:1.7;margin-bottom:1.5rem;">Créez votre compte membre directement en ligne et accédez à votre espace personnel.</p>
            <a href="/register" class="btn-primary">Créer mon compte →</a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <h2 class="cta-title">Prêt à gérer votre<br><em>tontine simplement ?</em></h2>
    <p class="cta-sub">Rejoignez votre groupement dès maintenant</p>
    <div class="cta-btns">
        <a href="/register" class="btn-primary">S'inscrire gratuitement</a>
        <a href="/login" class="btn-outline">Se connecter</a>
        
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div>©️ 2026 TontineTD — Tous droits réservés</div>
    <div>Fait avec ❤️ au Sénégal</div>
</footer>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        dots[currentSlide].classList.remove('active');
        currentSlide = n;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    setInterval(() => goToSlide((currentSlide + 1) % slides.length), 4000);
</script>
</body>
</html>