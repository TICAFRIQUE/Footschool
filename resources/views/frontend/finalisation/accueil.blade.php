<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription Officielle — SchoolFoot.ci</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --green: #1a7a2e;
      --green-light: #23a63d;
      --green-dark: #0e4d1c;
      --orange: #f47c20;
      --orange-light: #ff9940;
      --orange-dark: #c05e08;
      --white: #f8f9f5;
      --black: #0d0d0d;
      --grey: #2a2a2a;
      --pitch: #163d1a;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      font-family: "Nunito", sans-serif;
      background: var(--black);
      color: var(--white);
      overflow-x: hidden;
    }

    .text-orange { color: var(--orange) !important; }

    /* BANNIÈRE OFFICIELLE */
    #scam-banner {
      position: fixed;
      top: 0; left: 0; right: 0; z-index: 1000;
      background: linear-gradient(90deg, #b30000, #d40000, #b30000);
      background-size: 200% 100%;
      animation: bannerPulse 3s ease-in-out infinite;
      padding: 9px 16px;
      text-align: center;
      font-family: "Barlow Condensed", sans-serif;
      font-size: clamp(0.78rem, 2.2vw, 1rem);
      font-weight: 700;
      letter-spacing: 0.04em;
      color: #fff;
      text-transform: uppercase;
      border-bottom: 2px solid var(--orange);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    #scam-banner .shield { font-size: 1.1em; }

    @keyframes bannerPulse {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    /* HERO */
    #hero {
      position: relative;
      min-height: 92vh;
      padding-top: 48px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background:
        linear-gradient(to bottom, rgba(10,30,12,0.72) 0%, rgba(10,30,12,0.55) 60%, rgba(10,30,12,0.86) 100%),
        url('{{ asset('assets/images/baniere.jpg') }}') center center / cover no-repeat;
    }

    #hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.03) 0px, rgba(255, 255, 255, 0.03) 1px, transparent 1px, transparent 80px),
        repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.03) 0px, rgba(255, 255, 255, 0.03) 1px, transparent 1px, transparent 80px);
      pointer-events: none;
    }

    .pitch-circle {
      position: absolute;
      width: min(520px, 90vw);
      height: min(520px, 90vw);
      border-radius: 50%;
      border: 3px solid rgba(255, 255, 255, 0.07);
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }

    .pitch-circle::before {
      content: "";
      position: absolute;
      inset: 20px;
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .hero-content { position: relative; z-index: 2; text-align: center; padding: 20px 16px; }

    .hero-tag {
      display: inline-block;
      background: var(--orange);
      color: var(--black);
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 800;
      font-size: clamp(0.7rem, 2vw, 0.85rem);
      letter-spacing: 0.15em;
      text-transform: uppercase;
      padding: 5px 14px;
      border-radius: 2px;
      margin-bottom: 20px;
      animation: fadeSlideDown 0.7s ease both;
    }

    .hero-title {
      font-family: "Bebas Neue", sans-serif;
      font-size: clamp(3.2rem, 12vw, 7rem);
      line-height: 0.95;
      letter-spacing: 0.02em;
      color: var(--white);
      text-shadow: 0 0 60px rgba(244, 124, 32, 0.25);
      animation: fadeSlideDown 0.8s 0.1s ease both;
    }

    .hero-title span { color: var(--orange); }

    .hero-sub {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 600;
      font-size: clamp(1rem, 3vw, 1.35rem);
      letter-spacing: 0.03em;
      color: rgba(248, 249, 245, 0.8);
      margin-top: 16px;
      max-width: 620px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeSlideDown 0.8s 0.2s ease both;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(244, 124, 32, 0.15);
      border: 1.5px solid var(--orange);
      border-radius: 50px;
      padding: 8px 22px;
      margin-top: 26px;
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: clamp(0.85rem, 2.2vw, 1rem);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: var(--orange-light);
      animation: fadeSlideDown 0.8s 0.35s ease both;
    }

    .hero-cta { margin-top: 32px; animation: fadeSlideDown 0.8s 0.45s ease both; }

    .btn-main {
      background: var(--orange);
      color: var(--black);
      font-family: "Bebas Neue", sans-serif;
      font-size: clamp(1.1rem, 3vw, 1.4rem);
      letter-spacing: 0.1em;
      border: none;
      border-radius: 4px;
      padding: 14px 40px;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
      box-shadow: 0 8px 30px rgba(244, 124, 32, 0.35);
      display: inline-block;
      text-decoration: none;
    }

    .btn-main:hover { background: var(--orange-light); transform: translateY(-3px); color: var(--black); }

    .hero-links { margin-top: 20px; animation: fadeSlideDown 0.8s 0.55s ease both; }
    .hero-links a { color: var(--white); opacity: 0.8; font-size: 0.9rem; text-decoration: underline; text-underline-offset: 3px; }
    .hero-links a:hover { opacity: 1; color: var(--orange-light); }

    .scroll-hint {
      position: absolute;
      bottom: 24px; left: 50%;
      transform: translateX(-50%);
      z-index: 2;
      display: flex; flex-direction: column; align-items: center; gap: 6px;
      opacity: 0.45;
      animation: bounce 2s ease-in-out infinite;
    }

    .scroll-hint span { font-family: "Barlow Condensed", sans-serif; font-size: 0.7rem; letter-spacing: 0.15em; text-transform: uppercase; }
    .scroll-arrow { font-size: 1.4rem; }

    @keyframes bounce {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(8px); }
    }

    @keyframes fadeSlideDown {
      from { opacity: 0; transform: translateY(-22px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ÉTAPES */
    #etapes {
      background: #111214;
      padding: 80px 0;
      position: relative;
      overflow: hidden;
    }

    .section-label {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 800;
      font-size: 0.78rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--orange);
      margin-bottom: 10px;
    }

    .section-title {
      font-family: "Bebas Neue", sans-serif;
      font-size: clamp(2.2rem, 5.5vw, 3.8rem);
      line-height: 1;
      color: var(--white);
      margin-bottom: 18px;
    }

    .divider-line {
      width: 60px; height: 4px;
      background: linear-gradient(90deg, var(--orange), var(--green-light));
      border-radius: 2px;
      margin-bottom: 24px;
    }

    .etape-card {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 26px 22px;
      height: 100%;
      transition: border-color 0.3s, transform 0.3s;
    }

    .etape-card:hover { border-color: var(--orange); transform: translateY(-4px); }

    .etape-num {
      font-family: "Bebas Neue", sans-serif;
      font-size: 2.2rem;
      color: var(--orange);
      line-height: 1;
      margin-bottom: 10px;
    }

    .etape-card h4 {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 1.05rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: var(--orange-light);
      margin-bottom: 8px;
    }

    .etape-card p { font-size: 0.88rem; line-height: 1.7; color: rgba(248, 249, 245, 0.65); }

    /* CONTACT */
    #contact {
      background: var(--pitch);
      padding: 70px 0;
      position: relative;
      overflow: hidden;
    }

    #contact::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 50% 0%, rgba(244, 124, 32, 0.1) 0%, transparent 60%);
      pointer-events: none;
    }

    .contact-card {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 14px;
      padding: 24px 20px;
      height: 100%;
      text-align: center;
      transition: border-color 0.3s, transform 0.3s;
    }

    .contact-card:hover { border-color: var(--orange); transform: translateY(-4px); }

    .contact-card .icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: rgba(244, 124, 32, 0.15);
      color: var(--orange-light);
      font-size: 1.3rem;
      margin-bottom: 14px;
    }

    .contact-card h4 {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 0.85rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--white);
      margin-bottom: 8px;
    }

    .contact-card p, .contact-card a {
      font-size: 0.92rem;
      color: rgba(248, 249, 245, 0.75);
      text-decoration: none;
      margin: 0;
    }

    .contact-card a:hover { color: var(--orange-light); }

    footer#footer {
      background: var(--black);
      border-top: 1px solid rgba(255,255,255,.08);
      padding: 28px 0;
      text-align: center;
    }

    .legal-text {
      font-size: 0.72rem;
      color: rgba(248, 249, 245, 0.4);
      max-width: 760px;
      margin: 10px auto 0;
      line-height: 1.6;
    }
  </style>
</head>

<body>

  <!-- BANNIÈRE OFFICIELLE -->
  <div id="scam-banner">
    <span class="shield">🛡️</span>
    Inscription officielle — Les frais (65 000 FCFA) se paient UNIQUEMENT sur cette plateforme. Ne payez jamais un tiers !
    <span class="shield">🛡️</span>
  </div>

  <!-- HERO -->
  <section id="hero">
    <div class="pitch-circle"></div>
    <div class="hero-content">
      <div class="hero-tag">Phase 2 &bull; Inscription Officielle</div>
      <h1 class="hero-title">Finalise ton<br /><span>Inscription</span></h1>
      <p class="hero-sub">
        Tu as déjà été préinscrit ? Confirme tes informations, complète ton dossier
        et règle les frais d'inscription pour valider définitivement ta place.
      </p>
      <div class="hero-badge">🔒 Paiement sécurisé — 65 000 FCFA — Officiel &amp; Unique</div>
      <div class="hero-cta">
        <a href="{{ route('finalisation.connexion') }}" class="btn-main">📋 &nbsp;Commencer mon inscription</a>
      </div>
      <div class="hero-links">
        @if (session('espace_candidat_id'))
          <a href="{{ route('espace.dashboard') }}">Mon espace candidat</a>
        @else
          <a href="{{ route('espace.connexion') }}">Espace candidat</a>
        @endif
      </div>
    </div>
    <div class="scroll-hint">
      <span>Découvrir</span>
      <span class="scroll-arrow">↓</span>
    </div>
  </section>

  <!-- ÉTAPES -->
  <section id="etapes">
    <div class="container">
      <div class="text-center mb-5">
        <p class="section-label">Comment ça marche</p>
        <h2 class="section-title">4 étapes pour <span class="text-orange">t'inscrire</span></h2>
        <div class="divider-line mx-auto"></div>
      </div>
      <div class="row g-3">
        <div class="col-sm-6 col-lg-3">
          <div class="etape-card">
            <div class="etape-num">01</div>
            <h4>Identification</h4>
            <p>Entre ton numéro de téléphone utilisé lors de ta préinscription.</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="etape-card">
            <div class="etape-num">02</div>
            <h4>Confirmation</h4>
            <p>Vérifie et confirme que tes informations sont exactes.</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="etape-card">
            <div class="etape-num">03</div>
            <h4>Parents / Tuteurs</h4>
            <p>Renseigne les coordonnées de ton père et de ta mère.</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="etape-card">
            <div class="etape-num">04</div>
            <h4>Paiement</h4>
            <p>Règle les 65 000 FCFA d'inscription pour valider ta place.</p>
          </div>
        </div>
      </div>
      <div class="text-center mt-5">
        <a href="{{ route('finalisation.connexion') }}" class="btn-main">📋 &nbsp;Commencer mon inscription</a>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact">
    <div class="container">
      <div class="text-center mb-5">
        <p class="section-label">Une question ?</p>
        <h2 class="section-title">Contacte-<span class="text-orange">nous</span></h2>
        <div class="divider-line mx-auto"></div>
      </div>
      <div class="row g-3 justify-content-center">
        <div class="col-sm-6 col-lg-4">
          <div class="contact-card">
            <span class="icon"><i class="bi bi-whatsapp"></i></span>
            <h4>WhatsApp</h4>
            <a href="tel:+2250715094421">+225 07 15 09 44 21</a>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="contact-card">
            <span class="icon"><i class="bi bi-envelope-fill"></i></span>
            <h4>Email</h4>
            <a href="mailto:info@schoolfoot.ci">info@schoolfoot.ci</a>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="contact-card">
            <span class="icon"><i class="bi bi-geo-alt-fill"></i></span>
            <h4>Localisation</h4>
            <p>Angré Les Oscars, Abidjan</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer id="footer">
    <div class="container">
      <p class="text-center mb-0">Besoin d'aide ? <a href="mailto:info@schoolfoot.ci" style="color: var(--orange-light);">info@schoolfoot.ci</a></p>
      <div class="legal-text">
        &copy; 2026 SchoolFoot — Tous droits réservés. &bull; Les frais d'inscription officiels ne se règlent que sur cette plateforme, jamais à une personne. &bull; Une question ? Contacte-nous avant tout paiement en cas de doute.
      </div>
    </div>
  </footer>

</body>

</html>
