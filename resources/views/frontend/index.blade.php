<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SchoolFoot.ci — Détection de Talents Footballistiques</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: "Nunito", sans-serif;
      background: var(--black);
      color: var(--white);
      overflow-x: hidden;
    }

    /* ANTI-SCAM BANNER */
    #scam-banner {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
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

    #scam-banner .shield {
      font-size: 1.1em;
    }

    @keyframes bannerPulse {

      0%,
      100% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }
    }

    /* HERO */
    #hero {
      position: relative;
      min-height: 100vh;
      padding-top: 48px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow: hidden;
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

    #hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(135deg, rgba(26, 122, 46, 0.18) 0px, rgba(26, 122, 46, 0.18) 40px, rgba(14, 77, 28, 0.18) 40px, rgba(14, 77, 28, 0.18) 80px);
      pointer-events: none;
    }

    .pitch-circle {
      position: absolute;
      width: min(520px, 90vw);
      height: min(520px, 90vw);
      border-radius: 50%;
      border: 3px solid rgba(255, 255, 255, 0.07);
      top: 50%;
      left: 50%;
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

    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      padding: 20px 16px;
    }

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
      font-size: clamp(4rem, 16vw, 10rem);
      line-height: 0.9;
      letter-spacing: 0.02em;
      color: var(--white);
      text-shadow: 0 0 60px rgba(244, 124, 32, 0.25);
      animation: fadeSlideDown 0.8s 0.1s ease both;
    }

    .hero-title span {
      color: var(--orange);
    }

    .hero-sub {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 600;
      font-size: clamp(1.05rem, 3.5vw, 1.55rem);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: rgba(248, 249, 245, 0.75);
      margin-top: 12px;
      animation: fadeSlideDown 0.8s 0.2s ease both;
    }

    .hero-free-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(35, 166, 61, 0.15);
      border: 1.5px solid var(--green-light);
      border-radius: 50px;
      padding: 8px 22px;
      margin-top: 28px;
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: clamp(0.9rem, 2.5vw, 1.1rem);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #6dffa0;
      animation: fadeSlideDown 0.8s 0.35s ease both;
    }

    .hero-cta {
      margin-top: 36px;
      animation: fadeSlideDown 0.8s 0.45s ease both;
    }

    .btn-main {
      background: var(--orange);
      color: var(--black);
      font-family: "Bebas Neue", sans-serif;
      font-size: clamp(1.2rem, 3.5vw, 1.55rem);
      letter-spacing: 0.1em;
      border: none;
      border-radius: 4px;
      padding: 14px 44px;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
      box-shadow: 0 8px 30px rgba(244, 124, 32, 0.35);
      display: inline-block;
      text-decoration: none;
    }

    .btn-main:hover {
      background: var(--orange-light);
      transform: translateY(-3px);
      box-shadow: 0 14px 40px rgba(244, 124, 32, 0.5);
      color: var(--black);
    }

    .btn-main:active {
      transform: translateY(0);
    }

    .hero-secondary-links {
      margin-top: 18px;
      display: flex;
      gap: 18px;
      justify-content: center;
      flex-wrap: wrap;
      animation: fadeSlideDown 0.8s 0.55s ease both;
    }

    .hero-secondary-links a {
      color: var(--white);
      opacity: 0.85;
      font-size: 0.92rem;
      text-decoration: underline;
      text-underline-offset: 3px;
    }

    .hero-secondary-links a:hover {
      opacity: 1;
      color: var(--orange-light);
    }

    .scroll-hint {
      position: absolute;
      bottom: 28px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      opacity: 0.45;
      animation: bounce 2s ease-in-out infinite;
    }

    .scroll-hint span {
      font-family: "Barlow Condensed", sans-serif;
      font-size: 0.7rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
    }

    .scroll-arrow {
      font-size: 1.4rem;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateX(-50%) translateY(0);
      }

      50% {
        transform: translateX(-50%) translateY(8px);
      }
    }

    @keyframes fadeSlideDown {
      from {
        opacity: 0;
        transform: translateY(-22px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* PRESENTATION */
    #presentation {
      background: var(--grey);
      padding: 80px 0;
      position: relative;
      overflow: hidden;
    }

    #presentation::before {
      content: "⚽";
      position: absolute;
      font-size: 22rem;
      opacity: 0.03;
      top: -40px;
      right: -60px;
      pointer-events: none;
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
      font-size: clamp(2.4rem, 6vw, 4.5rem);
      line-height: 1;
      color: var(--white);
      margin-bottom: 20px;
    }

    .divider-line {
      width: 60px;
      height: 4px;
      background: linear-gradient(90deg, var(--orange), var(--green-light));
      border-radius: 2px;
      margin-bottom: 24px;
    }

    .pres-text {
      font-size: clamp(0.95rem, 2.2vw, 1.08rem);
      line-height: 1.85;
      color: rgba(248, 249, 245, 0.78);
      max-width: 680px;
    }

    .pres-card {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 28px 24px;
      height: 100%;
      transition: border-color 0.3s, transform 0.3s;
    }

    .pres-card:hover {
      border-color: var(--orange);
      transform: translateY(-4px);
    }

    .pres-card .icon {
      font-size: 2.2rem;
      margin-bottom: 12px;
      display: block;
    }

    .pres-card h4 {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 1.15rem;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      color: var(--orange-light);
      margin-bottom: 8px;
    }

    .pres-card p {
      font-size: 0.9rem;
      line-height: 1.7;
      color: rgba(248, 249, 245, 0.65);
    }

    /* FORM */
    #inscription {
      background: #111214;
      padding: 80px 0;
      position: relative;
      overflow: hidden;
    }

    #inscription::before {
      content: "";
      position: absolute;
      top: -200px;
      left: -200px;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(26, 122, 46, 0.1) 0%, transparent 65%);
      pointer-events: none;
    }

    #inscription::after {
      content: "";
      position: absolute;
      bottom: -120px;
      right: -120px;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(244, 124, 32, 0.09) 0%, transparent 65%);
      pointer-events: none;
    }

    .form-card {
      background: #1c1f22;
      border: 1px solid rgba(255, 255, 255, 0.09);
      border-radius: 20px;
      padding: clamp(24px, 5vw, 50px);
      box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
    }

    .form-section-title {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 0.72rem;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: var(--orange);
      padding: 7px 14px;
      background: rgba(244, 124, 32, 0.12);
      border-left: 3px solid var(--orange);
      border-radius: 0 6px 6px 0;
      margin: 32px 0 20px;
    }

    .form-section-title:first-of-type {
      margin-top: 0;
    }

    .form-label {
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 0.8rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #c8cdd6;
      margin-bottom: 7px;
      display: block;
    }

    .form-control,
    .form-select {
      background: #272b30 !important;
      border: 1.5px solid #3a3f47 !important;
      color: #f0f2f5 !important;
      border-radius: 10px !important;
      padding: 12px 14px !important;
      font-family: "Nunito", sans-serif;
      font-size: 0.95rem;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--orange) !important;
      box-shadow: 0 0 0 3px rgba(244, 124, 32, 0.18) !important;
      outline: none !important;
      background: #2e3338 !important;
    }

    .form-control::placeholder {
      color: #55606e !important;
    }

    .form-select option {
      background: #272b30;
      color: #f0f2f5;
    }

    /* Checkboxes */
    .check-btn input[type="checkbox"] {
      display: none;
    }

    .check-btn label {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      border-radius: 10px;
      border: 1.5px solid #3a3f47;
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 0.9rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #8a95a3;
      cursor: pointer;
      transition: all 0.2s;
      background: #272b30;
      user-select: none;
    }

    .check-btn label::before {
      content: "";
      width: 16px;
      height: 16px;
      border-radius: 4px;
      border: 2px solid #4a5260;
      background: transparent;
      flex-shrink: 0;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .check-btn input[type="checkbox"]:checked+label {
      border-color: var(--orange);
      color: var(--white);
      background: rgba(244, 124, 32, 0.12);
      box-shadow: 0 4px 14px rgba(244, 124, 32, 0.15);
    }

    .check-btn input[type="checkbox"]:checked+label::before {
      background: var(--orange);
      border-color: var(--orange);
      content: "✓";
      color: #fff;
      font-size: 0.65rem;
      font-weight: 900;
      line-height: 16px;
      text-align: center;
    }

    .check-btn label:hover {
      border-color: var(--orange-light);
      color: var(--white);
    }

    /* Radios niveau */
    .lang-group {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .lang-btn {
      flex: 1;
      min-width: 80px;
    }

    .lang-btn input[type="radio"] {
      display: none;
    }

    .lang-btn label {
      display: block;
      text-align: center;
      padding: 10px;
      border-radius: 10px;
      border: 1.5px solid #3a3f47;
      font-family: "Barlow Condensed", sans-serif;
      font-weight: 700;
      font-size: 0.85rem;
      letter-spacing: 0.07em;
      text-transform: uppercase;
      color: #8a95a3;
      cursor: pointer;
      transition: all 0.2s;
      background: #272b30;
      white-space: nowrap;
    }

    .lang-btn input[type="radio"]:checked+label {
      background: var(--green-light);
      border-color: var(--green-light);
      color: #fff;
      box-shadow: 0 4px 16px rgba(35, 166, 61, 0.35);
    }

    .lang-btn label:hover {
      border-color: var(--green-light);
      color: var(--white);
      background: rgba(35, 166, 61, 0.1);
    }

    .required-star {
      color: var(--orange);
    }

    /* Submit */
    .btn-submit {
      background: linear-gradient(135deg, var(--orange), var(--orange-dark));
      color: #fff;
      font-family: "Bebas Neue", sans-serif;
      font-size: 1.5rem;
      letter-spacing: 0.1em;
      border: none;
      border-radius: 10px;
      padding: 16px 40px;
      width: 100%;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 8px 30px rgba(244, 124, 32, 0.3);
      margin-top: 10px;
    }

    .btn-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 40px rgba(244, 124, 32, 0.45);
    }

    .btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Erreurs */
    .invalid-msg {
      font-size: 0.78rem;
      color: #ff7070;
      margin-top: 5px;
      display: none;
    }

    .field-error .invalid-msg {
      display: block;
    }

    .field-error .form-control,
    .field-error .form-select {
      border-color: #ff4d4d !important;
    }

    .field-error .lang-group {
      outline: 2px solid #ff4d4d;
      border-radius: 8px;
    }

    /* ✅ CORRECTION : erreur langues sur le wrapper global */
    #wrap-langues.field-error .invalid-msg {
      display: block !important;
    }

    #wrap-langues.field-error .check-btn label {
      border-color: #ff4d4d;
    }

    /* Success overlay */
    #success-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 2000;
      background: rgba(0, 0, 0, 0.88);
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(6px);
    }

    #success-overlay.active {
      display: flex;
    }

    .success-box {
      background: var(--pitch);
      border: 2px solid var(--green-light);
      border-radius: 20px;
      padding: 52px 40px;
      text-align: center;
      max-width: 460px;
      width: 90%;
      animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }

    @keyframes popIn {
      from {
        opacity: 0;
        transform: scale(0.7);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .success-icon {
      font-size: 4.5rem;
      display: block;
      margin-bottom: 16px;
    }

    .success-box h2 {
      font-family: "Bebas Neue", sans-serif;
      font-size: 2.6rem;
      color: var(--green-light);
      letter-spacing: 0.05em;
      margin-bottom: 12px;
    }

    .success-box p {
      color: rgba(248, 249, 245, 0.75);
      font-size: 1rem;
      line-height: 1.7;
      margin-bottom: 24px;
    }

    .btn-close-success {
      background: var(--green-light);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 12px 36px;
      font-family: "Bebas Neue", sans-serif;
      font-size: 1.2rem;
      letter-spacing: 0.1em;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-close-success:hover {
      background: var(--green);
    }

    /* Footer */
    #footer {
      background: #080808;
      padding: 48px 0 24px;
      border-top: 1px solid rgba(255, 255, 255, 0.07);
    }

    .legal-text {
      font-size: 0.75rem;
      color: rgba(248, 249, 245, 0.3);
      border-top: 1px solid rgba(255, 255, 255, 0.06);
      padding-top: 20px;
      margin-top: 32px;
      text-align: center;
      line-height: 1.8;
    }

    /* Utilities */
    .text-orange {
      color: var(--orange) !important;
    }

    .text-green {
      color: var(--green-light) !important;
    }

    @media (max-width: 576px) {
      .lang-btn label {
        font-size: 0.72rem;
        padding: 8px 6px;
      }
    }
  </style>
</head>

<body>

  <!-- ANTI-SCAM BANNER -->
  <div id="scam-banner">
    <span class="shield">🛡️</span>
    Préinscription 100% GRATUITE &mdash; Ne payez jamais un tiers ! Les frais d'inscription officiels se règlent UNIQUEMENT sur cette plateforme.
    <span class="shield">🛡️</span>
  </div>

  <!-- HERO -->
  <section id="hero" style="background:
      linear-gradient(to bottom, rgba(10,30,12,0.72) 0%, rgba(10,30,12,0.55) 60%, rgba(10,30,12,0.82) 100%),
      url('{{ asset('assets/images/baniere.jpg') }}') center center / cover no-repeat;">
    <div class="pitch-circle"></div>
    <div class="hero-content">
      <div class="hero-tag">Côte d'Ivoire &bull; Saison &bull; 2026</div>
      <h1 class="hero-title">School<span>Foot</span></h1>
      <p class="hero-sub">La 1ère Téléréalité de Détection de Talents Footballistiques en Côte d'Ivoire</p>
      <div class="hero-free-badge"><span>✅</span> Préinscription 100% Gratuite &amp; Ouverte à Tous</div>
      <div class="hero-cta">
        <a href="#inscription" class="btn-main">⚽ &nbsp;Je m'inscris maintenant</a>
      </div>
      <div class="hero-secondary-links">
        <a href="{{ route('accueil') }}">← Retour à l'accueil</a>
        <a href="{{ route('finalisation.connexion') }}">Déjà préinscrit ? Finaliser mon inscription</a>
        <a href="{{ route('espace.connexion') }}">Espace candidat</a>
      </div>
    </div>
    <div class="scroll-hint">
      <span>Découvrir</span>
      <span class="scroll-arrow">↓</span>
    </div>
  </section>

  <!-- PRESENTATION -->
  <section id="presentation">
    <div class="container">
      <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
          <p class="section-label">C'est quoi SchoolFoot ?</p>
          <h2 class="section-title">Ton talent<br />mérite d'être<br /><span class="text-orange">vu.</span></h2>
          <div class="divider-line"></div>
          <p class="pres-text">
            SchoolFoot est la toute première émission de téléréalité dédiée à la détection de jeunes talents du football non professionnel (Football du quartier). Si tu maîtrises les <strong>Grands Poteaux</strong>, les <strong>Petits Poteaux</strong> ou le <strong>Maracana</strong>, et que tu rêves d'un avenir dans le football professionnel, cette plateforme est faite pour toi.<br /><br />
            Pas besoin de réseau, pas besoin d'agent. Juste ton talent et ta détermination.
            <strong class="text-green">La préinscription est 100% gratuite.</strong>
          </p>
        </div>
        <div class="col-lg-6">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="pres-card"><span class="icon">🏆</span>
                <h4>Détection Officielle</h4>
                <p>Des recruteurs professionnels évaluent ton jeu en direct devant les caméras.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="pres-card"><span class="icon">📺</span>
                <h4>Téléréalité</h4>
                <p>Ton parcours diffusé à la télévision. Deviens une star du football.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="pres-card"><span class="icon">🎯</span>
                <h4>17 à 22 Ans</h4>
                <p>Ouvert aux joueurs de Grands Poteaux, Petits Poteaux et Maracana sans réseau pro.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="pres-card"><span class="icon">🆓</span>
                <h4>Gratuit &amp; Sécurisé</h4>
                <p>Aucun frais, aucun intermédiaire. Préinscription directe et officielle.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- INSCRIPTION -->
  <section id="inscription">
    <div class="container">
      <div class="text-center mb-5">
        <p class="section-label">Formulaire Officiel</p>
        <h2 class="section-title">Préinscription<br /><span class="text-orange">Gratuite</span></h2>
        <div class="divider-line mx-auto"></div>
        <p style="color: rgba(248,249,245,0.5); font-size: 0.93rem; max-width: 520px; margin: 0 auto;">
          Remplissez tous les champs ci-dessous. Un membre de notre équipe vous contactera à la fin de la période des préinscriptions.
        </p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
          <div class="form-card">

            <form id="inscriptionForm" method="POST" action="{{ route('inscription.store') }}" novalidate>
              @csrf

              <!-- IDENTITÉ -->
              <p class="form-section-title">👤 Identité du Candidat</p>
              <div class="row g-3">

                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-nom">
                    <label class="form-label" for="nom">Nom <span class="required-star">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex : KOUASSI" />
                    <div class="invalid-msg">Veuillez entrer votre nom.</div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-prenom">
                    <label class="form-label" for="prenom">Prénoms <span class="required-star">*</span></label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Ex : Jean-Paul" />
                    <div class="invalid-msg">Veuillez entrer vos prénoms.</div>
                  </div>
                </div>

                <!-- Date & Lieu sur une ligne -->
                <div class="col-12">
                  <label class="form-label">Date &amp; Lieu de Naissance <span class="required-star">*</span></label>
                  <div class="row g-2">
                    <div class="col-6 col-sm-2">
                      <div class="field-wrap" id="wrap-jour">
                        <select class="form-select" id="jour" name="jour" aria-label="Jour">
                          <option value="">Jour</option>
                        </select>
                        <div class="invalid-msg">Jour requis.</div>
                      </div>
                    </div>
                    <div class="col-6 col-sm-3">
                      <div class="field-wrap" id="wrap-mois">
                        <select class="form-select" id="mois" name="mois" aria-label="Mois">
                          <option value="">Mois</option>
                          <option value="01">Janvier</option>
                          <option value="02">Février</option>
                          <option value="03">Mars</option>
                          <option value="04">Avril</option>
                          <option value="05">Mai</option>
                          <option value="06">Juin</option>
                          <option value="07">Juillet</option>
                          <option value="08">Août</option>
                          <option value="09">Septembre</option>
                          <option value="10">Octobre</option>
                          <option value="11">Novembre</option>
                          <option value="12">Décembre</option>
                        </select>
                        <div class="invalid-msg">Mois requis.</div>
                      </div>
                    </div>
                    <div class="col-6 col-sm-3">
                      <div class="field-wrap" id="wrap-annee">
                        <select class="form-select" id="annee" name="annee" aria-label="Année">
                          <option value="">Année</option>
                        </select>
                        <div class="invalid-msg">Année requise.</div>
                      </div>
                    </div>
                    <div class="col-6 col-sm-4">
                      <div class="field-wrap" id="wrap-lieu-naissance">
                        <input type="text" class="form-control" id="lieuNaissance" name="lieuNaissance" placeholder="Lieu de naissance" />
                        <div class="invalid-msg">Lieu de naissance requis.</div>
                      </div>
                    </div>
                  </div>
                  <div class="invalid-msg" id="dob-age-msg" style="display:none; margin-top:6px">
                    ⚠️ Tu dois avoir entre 17 et 22 ans pour participer.
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-candidat-tel">
                    <label class="form-label" for="candidatTel">Votre numéro de téléphone <span class="required-star">*</span></label>
                    <input type="tel" class="form-control" id="candidatTel" name="candidatTel" placeholder="Ex : 05 XX XX XX XX" />
                    <div class="invalid-msg">Entrez votre numéro personnel (min. 8 chiffres).</div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-ville">
                    <label class="form-label" for="ville">Ville d'inscription <span class="required-star">*</span></label>
                    <select class="form-select" id="ville" name="ville">
                      <option value="">Sélectionner une ville…</option>
                      @foreach($data['villes'] as $ville)
                      <option value="{{ $ville }}">{{ $ville }}</option>
                      @endforeach
                    </select>
                    <div class="invalid-msg">Veuillez sélectionner votre ville.</div>
                  </div>
                </div>

              </div>

              <!-- ÉDUCATION -->
              <p class="form-section-title">📚 Éducation</p>
              <div class="row g-3">
                <div class="col-12">
                  <div class="field-wrap" id="wrap-niveau">
                    <label class="form-label" for="niveau">Niveau d'études actuel / Dernier diplôme <span class="required-star">*</span></label>
                    <select class="form-select" id="niveau" name="niveau">
                      <option value="">Sélectionner…</option>
                      <option>Sans diplôme</option>
                      <option>CEP</option>
                      <option>BEPC</option>
                      <option>CAP / BEP</option>
                      <option>Baccalauréat</option>
                      <option>BTS / DUT (Bac+2)</option>
                      <option>Licence (Bac+3)</option>
                      <option>Master / Ingénieur (Bac+5)</option>
                      <option>Doctorat</option>
                    </select>
                    <div class="invalid-msg">Sélectionnez votre niveau d'études.</div>
                  </div>
                </div>
              </div>

              <!-- LANGUES -->
              <p class="form-section-title">🗣️ Compétences Linguistiques</p>

              <!-- ✅ CORRECTION : wrap-langues entoure les 3 colonnes -->
              <div id="wrap-langues">
                <div class="row g-4">
                  <div class="col-md-4">
                    <div class="field-wrap">
                      <label class="form-label">Français <span class="required-star">*</span></label>
                      <div class="check-btn">
                        <input type="checkbox" id="lang-fr" name="langues[]" value="Français" />
                        <label for="lang-fr">Je parle Français</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="field-wrap">
                      <label class="form-label">Anglais</label>
                      <div class="check-btn">
                        <input type="checkbox" id="lang-en" name="langues[]" value="Anglais" />
                        <label for="lang-en">Je parle Anglais</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="field-wrap">
                      <label class="form-label">Espagnol</label>
                      <div class="check-btn">
                        <input type="checkbox" id="lang-es" name="langues[]" value="Espagnol" />
                        <label for="lang-es">Je parle Espagnol</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="invalid-msg" id="langues-error-msg" style="display:none; margin-top:8px;">
                  ⚠️ Cochez au moins une langue.
                </div>
              </div>

              <!-- Niveaux de maîtrise -->
              <div class="row g-4 mt-4">
                <div class="col-md-4">
                  <div class="field-wrap">
                    <label class="form-label">Niveau en Français</label>
                    <div class="lang-group">
                      <div class="lang-btn"><input type="radio" name="niveau_fr" id="fr-deb" value="Débutant" /><label for="fr-deb">Débutant</label></div>
                      <div class="lang-btn"><input type="radio" name="niveau_fr" id="fr-int" value="Intermédiaire" /><label for="fr-int">Intermédiaire</label></div>
                      <div class="lang-btn"><input type="radio" name="niveau_fr" id="fr-av" value="Avancé" /><label for="fr-av">Avancé</label></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="field-wrap">
                    <label class="form-label">Niveau en Anglais</label>
                    <div class="lang-group">
                      <div class="lang-btn"><input type="radio" name="niveau_en" id="en-deb" value="Débutant" /><label for="en-deb">Débutant</label></div>
                      <div class="lang-btn"><input type="radio" name="niveau_en" id="en-int" value="Intermédiaire" /><label for="en-int">Intermédiaire</label></div>
                      <div class="lang-btn"><input type="radio" name="niveau_en" id="en-av" value="Avancé" /><label for="en-av">Avancé</label></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="field-wrap">
                    <label class="form-label">Niveau en Espagnol</label>
                    <div class="lang-group">
                      <div class="lang-btn"><input type="radio" name="niveau_es" id="es-deb" value="Débutant" /><label for="es-deb">Débutant</label></div>
                      <div class="lang-btn"><input type="radio" name="niveau_es" id="es-int" value="Intermédiaire" /><label for="es-int">Intermédiaire</label></div>
                      <div class="lang-btn"><input type="radio" name="niveau_es" id="es-av" value="Avancé" /><label for="es-av">Avancé</label></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- PIEDS FORTS & NUMÉRO DE POSTE -->
              <p class="form-section-title">⚽ Caractéristiques Footballistiques</p>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-pieds-fort">
                    <label class="form-label">Pied fort <span class="required-star">*</span></label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                      <div class="lang-btn">
                        <input type="radio" name="pieds_fort" id="pied-gauche" value="gauche" />
                        <label for="pied-gauche">Gauche</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="pieds_fort" id="pied-droit" value="droit" />
                        <label for="pied-droit">Droit</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="pieds_fort" id="pied-les-deux" value="les deux" />
                        <label for="pied-les-deux">Les deux</label>
                      </div>
                    </div>
                    <div class="invalid-msg">Veuillez sélectionner votre pied fort.</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-numero-poste">
                    <label class="form-label" for="numeroPoste">Numéro de poste de prédilection <span class="required-star">*</span></label>
                    <select class="form-select" id="numeroPoste" name="numero_poste">
                      <option value="">Sélectionnez</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                    </select>
                    <div class="invalid-msg">Veuillez choisir un numéro entre 1 et 11.</div>
                  </div>
                </div>
              </div>

              <!-- CONTACT D'URGENCE -->
              <p class="form-section-title">📞 Contact d'Urgence</p>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-urgence-nom">
                    <label class="form-label" for="urgenceNom">Nom &amp; Prénom du proche <span class="required-star">*</span></label>
                    <input type="text" class="form-control" id="urgenceNom" name="urgenceNom" placeholder="Ex : BROU Adjoua Marie" />
                    <div class="invalid-msg">Veuillez entrer le nom du contact d'urgence.</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-urgence-tel">
                    <label class="form-label" for="urgenceTel">Téléphone du proche <span class="required-star">*</span></label>
                    <input type="tel" class="form-control" id="urgenceTel" name="urgenceTel" placeholder="Ex : 07 XX XX XX XX" />
                    <div class="invalid-msg">Entrez un numéro valide (min. 8 chiffres).</div>
                  </div>
                </div>
              </div>
              <div class="mb-3 mt-2" id="captcha-wrap">
                <label class="form-label">Vérification <span class="required-star">*</span></label>
                <div style="display:flex; gap:10px; align-items:center;">
                  <span id="captcha-question" style="font-weight:700;"></span>
                  <input type="text" id="captcha-input" class="form-control" placeholder="Réponse" style="max-width:120px;">
                </div>
                <div class="invalid-msg" id="captcha-error" style="display:none;">Réponse incorrecte.</div>
              </div>
              <!-- RAPPEL -->
              <div class="d-flex align-items-center gap-2 mt-4 p-3"
                style="background: rgba(35,166,61,0.08); border: 1px solid rgba(35,166,61,0.22); border-radius: 10px;">
                <span style="font-size: 1.3rem">🛡️</span>
                <p style="font-size: 0.82rem; color: rgba(248,249,245,0.65); margin: 0; line-height: 1.5;">
                  <strong style="color: #6dffa0">Rappel :</strong> La préinscription est 100% gratuite.

                </p>
              </div>

              <button type="submit" class="btn-submit mt-4">⚽ &nbsp;Envoyer Ma Préinscription</button>

              <p style="text-align:center; font-size:0.75rem; color:rgba(248,249,245,0.28); margin-top:14px;">
                <span class="required-star">*</span> Champs obligatoires. Vos données sont traitées de manière confidentielle.
              </p>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer id="footer">
    <div class="container">
      <p class="text-center">Pour tous besoins Contactez-nous: <a href="mailto:info@schoolfoot.ci">info@schoolfoot.ci</a></p>
      <div class="legal-text">
        &copy; 2026 SchoolFoot — Tous droits réservés. &bull; Les données collectées sont utilisées uniquement dans le cadre de la préinscription au programme SchoolFoot. Conformément à la réglementation en vigueur, vous disposez d'un droit d'accès, de modification et de suppression de vos données. &bull; Préinscription gratuite — les frais d'inscription officiels ne se règlent que sur cette plateforme.
      </div>
      <div style="text-align: center; margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,0.1); font-size: 13px; color: #666;">
        Développé par <a href="https://ticafrique.ci" target="_blank" style="color: #2c3e50; font-weight: 600; text-decoration: none;">TICAFRIQUE</a>
      </div>
    </div>
  </footer>

  <!-- SUCCESS OVERLAY -->
  <div id="success-overlay">
    <div class="success-box">
      <span class="success-icon">⚽</span>
      <h2>Félicitations !</h2>
      <p>
        Ta préinscription a bien été enregistrée.<br />
        Notre équipe te contactera dans les <strong>72 heures</strong> sur le numéro que tu as indiqué.<br /><br />
        <span style="color: #6dffa0; font-weight: 700">Rappel : cette préinscription est gratuite. Si tu es retenu, les frais d'inscription officiels se paient uniquement sur ce site — jamais à une personne.</span>
      </p>
      <button class="btn-close-success" id="closeSuccess">Fermer ✓</button>
    </div>
  </div>

  <!-- ============================================================
     VIDEO OVERLAY
============================================================ -->
  <div id="video-overlay">
    <div id="video-box">
      <button id="video-close" aria-label="Fermer la vidéo">✕</button>
      <video id="promo-video" playsinline>
        <!-- Remplace src par l'URL de ta vidéo, ex: src="assets/videos/promo.mp4" -->
        <source src="assets/videos/promo.mp4" type="video/mp4" />
        Votre navigateur ne supporte pas la lecture vidéo.
      </video>
      <!-- Affiché si le navigateur bloque l'autoplay avec son -->
      <div id="video-unblock">
        <button id="video-unmute-btn">
          🔊 Activer le son et lancer la vidéo
        </button>
      </div>
    </div>
  </div>
  <style>
    .floating-contact {
      position: fixed;
      right: 20px;
      bottom: 30px;
      width: 55px;
      height: 55px;
      background: #fff;
      color: #0e4d1c;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .floating-contact:hover {
      background: #f0f2f5;
      transform: scale(1.1);
      color: #c05e08;
    }

    /* Tooltip */
    .floating-contact::after {
      content: "Contact";
      position: absolute;
      right: 65px;
      background: #163d1a;
      color: #fff;
      font-size: 12px;
      padding: 5px 8px;
      border-radius: 4px;
      opacity: 0;
      transition: 0.3s;
      white-space: nowrap;
    }

    .floating-contact:hover::after {
      opacity: 1;
    }

    /* VIDEO OVERLAY */
    #video-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 3000;
      background: rgba(0, 0, 0, 0.82);
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(4px);
      animation: fadeIn 0.4s ease both;
    }

    #video-overlay.active {
      display: flex;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    #video-box {
      position: relative;
      width: min(720px, 92vw);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 32px 80px rgba(0, 0, 0, 0.7);
      background: #000;
      animation: popIn 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }

    @keyframes popIn {
      from {
        opacity: 0;
        transform: scale(0.82);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    #promo-video {
      display: block;
      width: 100%;
      max-height: 82vh;
      object-fit: contain;
      background: #000;
    }

    /* Bouton fermer */
    #video-close {
      position: absolute;
      top: 12px;
      right: 14px;
      z-index: 10;
      background: rgba(0, 0, 0, 0.65);
      color: #fff;
      border: 1.5px solid rgba(255, 255, 255, 0.25);
      border-radius: 50%;
      width: 36px;
      height: 36px;
      font-size: 1rem;
      line-height: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, transform 0.15s;
    }

    #video-close:hover {
      background: rgba(220, 53, 69, 0.85);
      transform: scale(1.1);
    }

    /* Fallback bouton son */
    #video-unblock {
      display: none;
      position: absolute;
      inset: 0;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.01);
    }

    #video-unblock.visible {
      display: flex;
    }

    #video-unmute-btn {
      background: var(--orange);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 14px 30px;
      font-family: 'Barlow Condensed', sans-serif;
      font-weight: 700;
      font-size: 1.05rem;
      letter-spacing: 0.06em;
      cursor: pointer;
      box-shadow: 0 8px 28px rgba(244, 124, 32, 0.4);
      transition: transform 0.15s, box-shadow 0.15s;
      animation: pulse 2s ease-in-out infinite;
    }

    #video-unmute-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 12px 36px rgba(244, 124, 32, 0.55);
    }

    @keyframes pulse {

      0%,
      100% {
        box-shadow: 0 8px 28px rgba(244, 124, 32, 0.4);
      }

      50% {
        box-shadow: 0 8px 40px rgba(244, 124, 32, 0.7);
      }
    }
  </style>

  <a href="mailto:info@schoolfoot.ci" class="floating-contact">
    <i class="bi bi-envelope-at-fill"></i>
  </a>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    let captchaAnswer = null;

    function generateCaptcha() {
      const a = Math.floor(Math.random() * 10) + 1;
      const b = Math.floor(Math.random() * 10) + 1;
      captchaAnswer = a + b;
      document.getElementById("captcha-question").textContent = `${a} + ${b} = ?`;
    }

    generateCaptcha();
    /* ======================================================
       POPULATE JOUR & ANNEE
    ====================================================== */
    (function() {
      const jourSel = document.getElementById("jour");
      for (let d = 1; d <= 31; d++) {
        const o = document.createElement("option");
        o.value = String(d).padStart(2, "0");
        o.textContent = String(d).padStart(2, "0");
        jourSel.appendChild(o);
      }
      const anneeSel = document.getElementById("annee");
      for (let y = 2009; y >= 2004; y--) {
        const o = document.createElement("option");
        o.value = y;
        o.textContent = y;
        anneeSel.appendChild(o);
      }
    })();

    /* ======================================================
       HELPERS
    ====================================================== */
    const form = document.getElementById("inscriptionForm");

    function setError(wrapId, show) {
      const el = document.getElementById(wrapId);
      if (!el) return;
      el.classList.toggle("field-error", show);
    }

    // ✅ CORRECTION : fonction dédiée pour les langues
    function setLanguesError(show) {
      const wrap = document.getElementById("wrap-langues");
      const msg = document.getElementById("langues-error-msg");
      if (wrap) wrap.classList.toggle("field-error", show);
      if (msg) msg.style.display = show ? "block" : "none";
    }

    function validPhone(val) {
      return val.replace(/\D/g, "").length >= 8;
    }

    function getChecked(name) {
      return Array.from(document.querySelectorAll('input[name="' + name + '"]:checked')).map(i => i.value);
    }

    function calcAge() {
      const jour = document.getElementById("jour").value;
      const mois = document.getElementById("mois").value;
      const annee = document.getElementById("annee").value;
      if (!jour || !mois || !annee) return null;
      const dob = new Date(parseInt(annee), parseInt(mois) - 1, parseInt(jour));
      const today = new Date();
      let age = today.getFullYear() - dob.getFullYear();
      const m = today.getMonth() - dob.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
      return age;
    }

    /* ======================================================
       VALIDATION
    ====================================================== */
    function validateForm() {
      let valid = true;

      const nom = document.getElementById("nom").value.trim();
      setError("wrap-nom", !nom);
      if (!nom) valid = false;

      const prenom = document.getElementById("prenom").value.trim();
      setError("wrap-prenom", !prenom);
      if (!prenom) valid = false;

      const jour = document.getElementById("jour").value;
      const mois = document.getElementById("mois").value;
      const annee = document.getElementById("annee").value;
      setError("wrap-jour", !jour);
      setError("wrap-mois", !mois);
      setError("wrap-annee", !annee);
      if (!jour) valid = false;
      if (!mois) valid = false;
      if (!annee) valid = false;

      const lieu = document.getElementById("lieuNaissance").value.trim();
      setError("wrap-lieu-naissance", !lieu);
      if (!lieu) valid = false;

      const ageMsg = document.getElementById("dob-age-msg");
      if (jour && mois && annee) {
        const age = calcAge();
        if (age === null || age < 17 || age >= 22) {
          ageMsg.style.display = "block";
          setError("wrap-annee", true);
          valid = false;
        } else {
          ageMsg.style.display = "none";
        }
      }

      const candidatTel = document.getElementById("candidatTel").value.trim();
      setError("wrap-candidat-tel", !validPhone(candidatTel));
      if (!validPhone(candidatTel)) valid = false;

      const ville = document.getElementById("ville").value;
      setError("wrap-ville", !ville);
      if (!ville) valid = false;

      const niveau = document.getElementById("niveau").value;
      setError("wrap-niveau", !niveau);
      if (!niveau) valid = false;

      // ✅ CORRECTION : utilise setLanguesError
      const langues = getChecked("langues[]");
      setLanguesError(langues.length === 0);
      if (langues.length === 0) valid = false;

      const piedsFort = getChecked("pieds_fort");
      setError("wrap-pieds-fort", piedsFort.length === 0);
      if (piedsFort.length === 0) valid = false;

      const numeroPoste = document.getElementById("numeroPoste").value.trim();
      const numeroPosteValid = numeroPoste && !isNaN(numeroPoste) && numeroPoste >= 1 && numeroPoste <= 11;
      setError("wrap-numero-poste", !numeroPosteValid);
      if (!numeroPosteValid) valid = false;

      const urgenceNom = document.getElementById("urgenceNom").value.trim();
      setError("wrap-urgence-nom", !urgenceNom);
      if (!urgenceNom) valid = false;

      const urgenceTel = document.getElementById("urgenceTel").value.trim();
      setError("wrap-urgence-tel", !validPhone(urgenceTel));
      if (!validPhone(urgenceTel)) valid = false;

      // ✅ Validation Captcha
      const captchaInput = document.getElementById("captcha-input").value.trim();
      const captchaValid = parseInt(captchaInput, 10) === captchaAnswer;
      const captchaError = document.getElementById("captcha-error");
      if (!captchaValid) {
        captchaError.style.display = "block";
        setError("captcha-wrap", true);
        valid = false;
      } else {
        captchaError.style.display = "none";
        setError("captcha-wrap", false);
      }

      return valid;
    }

    /* ======================================================
       SOUMISSION AJAX
    ====================================================== */
    form.addEventListener("submit", async function(e) {
      e.preventDefault();

      if (!validateForm()) {
        const firstErr = form.querySelector(".field-error");
        if (firstErr) firstErr.scrollIntoView({
          behavior: "smooth",
          block: "center"
        });
        return;
      }

      const btn = form.querySelector(".btn-submit");
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner"></span> Envoi en cours…';

      try {
        const response = await fetch(form.action, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content ||
              document.querySelector('input[name="_token"]').value,
            "Accept": "application/json",
          },
          body: new FormData(form),
        });

        const data = await response.json();

        if (response.ok) {
          // Reset
          form.reset();
          form.querySelectorAll(".field-error").forEach(el => el.classList.remove("field-error"));
          document.getElementById("dob-age-msg").style.display = "none";
          setLanguesError(false);
          // ✅ Régénère le captcha
          document.getElementById("captcha-error").style.display = "none";
          generateCaptcha();

          // ✅ CORRECTION : affiche l'overlay succès
          document.getElementById("success-overlay").classList.add("active");

        } else if (response.status === 422) {
          const errors = data.errors || {};
          const map = {
            nom: "wrap-nom",
            prenom: "wrap-prenom",
            jour: "wrap-jour",
            mois: "wrap-mois",
            annee: "wrap-annee",
            lieuNaissance: "wrap-lieu-naissance",
            candidatTel: "wrap-candidat-tel",
            ville: "wrap-ville",
            niveau: "wrap-niveau",
            pieds_fort: "wrap-pieds-fort",
            numero_poste: "wrap-numero-poste",
            urgenceNom: "wrap-urgence-nom",
            urgenceTel: "wrap-urgence-tel",
          };
          Object.keys(errors).forEach(field => {
            if (field === "langues" || field.startsWith("langues.")) {
              setLanguesError(true);
            } else if (map[field]) {
              setError(map[field], true);
            }
          });
          // ✅ Régénère le captcha après erreur
          generateCaptcha();
          showToast("error", data.message || "Une erreur est survenue.");

        } else {
          // ✅ Régénère le captcha après erreur
          generateCaptcha();
          showToast("error", "Une erreur est survenue. Réessaie.");
        }

      } catch (err) {
        console.error(err);
        showToast("error", "Erreur réseau. Vérifie ta connexion.");
      } finally {
        btn.disabled = false;
        btn.innerHTML = "⚽ &nbsp;Envoyer Ma Préinscription";
      }
    });

    /* ======================================================
       OVERLAY SUCCÈS — ✅ CORRECTION : listener ajouté
    ====================================================== */
    document.getElementById("closeSuccess").addEventListener("click", function() {
      document.getElementById("success-overlay").classList.remove("active");
      document.getElementById("inscription").scrollIntoView({
        behavior: "smooth"
      });
    });

    /* ======================================================
       TOAST
    ====================================================== */
    function showToast(type, message) {
      const existing = document.getElementById("ajax-toast");
      if (existing) existing.remove();
      const toast = document.createElement("div");
      toast.id = "ajax-toast";
      toast.style.cssText = `
        position: fixed; bottom: 30px; right: 30px; z-index: 9999;
        padding: 16px 24px; border-radius: 12px; font-size: 0.9rem;
        font-weight: 500; max-width: 380px; box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease; line-height: 1.5;
        background: ${type === "success" ? "rgba(35,166,61,0.95)" : "rgba(220,53,69,0.95)"};
        color: #fff; border: 1px solid ${type === "success" ? "#6dffa0" : "#ff6b6b"};
      `;
      toast.innerHTML = message;
      document.body.appendChild(toast);
      setTimeout(() => {
        toast.style.animation = "slideOut 0.3s ease forwards";
        setTimeout(() => toast.remove(), 300);
      }, 5000);
    }

    /* Animations */
    const style = document.createElement("style");
    style.textContent = `
      @keyframes slideIn  { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
      @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }
      .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; vertical-align: middle; margin-right: 6px; }
      @keyframes spin { to { transform: rotate(360deg); } }
    `;
    document.head.appendChild(style);

    /* ======================================================
       CLEAR ERRORS ON INTERACTION
    ====================================================== */
    form.querySelectorAll("input, select").forEach(function(input) {
      ["input", "change"].forEach(function(evt) {
        input.addEventListener(evt, function() {
          const wrap = this.closest(".field-wrap");
          if (wrap) wrap.classList.remove("field-error");
          // ✅ Clear erreur langues si checkbox langue
          if (this.name === "langues[]") setLanguesError(false);
          document.getElementById("dob-age-msg").style.display = "none";
        });
      });
    });
    /* ======================================================
     VIDEO OVERLAY — autoplay avec son, fallback bouton
  ====================================================== */
    (function() {
      const overlay = document.getElementById('video-overlay');
      const video = document.getElementById('promo-video');
      const closeBtn = document.getElementById('video-close');
      const unblock = document.getElementById('video-unblock');
      const unmuteBtn = document.getElementById('video-unmute-btn');

      /* Ouvre l'overlay et tente l'autoplay avec son */
      function openOverlay() {
        overlay.classList.add('active');
        video.muted = false;
        video.currentTime = 0;

        const playPromise = video.play();

        if (playPromise !== undefined) {
          playPromise.catch(function() {
            /* Le navigateur a bloqué l'autoplay avec son → affiche le bouton */
            unblock.classList.add('visible');
          });
        }
      }

      /* Ferme l'overlay et stoppe la vidéo */
      function closeOverlay() {
        video.pause();
        video.currentTime = 0;
        overlay.classList.remove('active');
        unblock.classList.remove('visible');
      }

      /* Déclenche 5 secondes après le chargement */
      setTimeout(openOverlay, 5000);

      /* Bouton Fermer */
      closeBtn.addEventListener('click', closeOverlay);

      /* Clic sur le fond sombre = fermer */
      overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeOverlay();
      });

      /* Touche Échap = fermer */
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeOverlay();
      });

      /* Bouton "Activer le son" */
      unmuteBtn.addEventListener('click', function() {
        video.muted = false;
        video.currentTime = 0;
        video.play();
        unblock.classList.remove('visible');
      });

      /* Ferme automatiquement quand la vidéo se termine */
      video.addEventListener('ended', closeOverlay);
    })();
  </script>

</body>

</html>