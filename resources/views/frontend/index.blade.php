<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SchoolFoot.ci — Détection de Talents Footballistiques</title>

  <!-- Bootstrap 5 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800&family=Nunito:wght@400;600;700&display=swap"
    rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* ============================================================
       ROOT VARIABLES & RESET
    ============================================================ */
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

    /* ============================================================
       ANTI-SCAM STICKY BANNER
    ============================================================ */
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

    /* ============================================================
       HERO SECTION
    ============================================================ */
    #hero {
      position: relative;
      min-height: 100vh;
      padding-top: 48px;
      /* space for banner */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow: hidden;

    }

    /* Grass lines pattern */
    #hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        repeating-linear-gradient(90deg,
          rgba(255, 255, 255, 0.03) 0px,
          rgba(255, 255, 255, 0.03) 1px,
          transparent 1px,
          transparent 80px),
        repeating-linear-gradient(0deg,
          rgba(255, 255, 255, 0.03) 0px,
          rgba(255, 255, 255, 0.03) 1px,
          transparent 1px,
          transparent 80px);
      pointer-events: none;
    }

    /* diagonal grass stripes */
    #hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(135deg,
          rgba(26, 122, 46, 0.18) 0px,
          rgba(26, 122, 46, 0.18) 40px,
          rgba(14, 77, 28, 0.18) 40px,
          rgba(14, 77, 28, 0.18) 80px);
      pointer-events: none;
    }

    /* center circle decoration */
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
      transition:
        transform 0.15s,
        box-shadow 0.15s,
        background 0.15s;
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

    /* scroll indicator */
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

    /* ============================================================
       PRESENTATION SECTION
    ============================================================ */
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
      transition:
        border-color 0.3s,
        transform 0.3s;
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

    /* ============================================================
       FORM SECTION
    ============================================================ */
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
      background: radial-gradient(circle,
          rgba(26, 122, 46, 0.1) 0%,
          transparent 65%);
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
      background: radial-gradient(circle,
          rgba(244, 124, 32, 0.09) 0%,
          transparent 65%);
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
      transition:
        border-color 0.2s,
        box-shadow 0.2s,
        background 0.2s;
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

    /* ---- Checkboxes langues ---- */
    .check-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

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

    /* ---- Radio boutons niveau ---- */
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
      padding: 10px 10px;
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

    /* Submit Button */
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
      transition:
        transform 0.2s,
        box-shadow 0.2s;
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

    /* Error messages */
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
      animation: spin 0.6s 0.3s ease both;
    }

    @keyframes spin {
      from {
        transform: rotate(-20deg) scale(0.8);
      }

      to {
        transform: rotate(0deg) scale(1);
      }
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

    /* ============================================================
       FOOTER
    ============================================================ */
    #footer {
      background: #080808;
      padding: 48px 0 24px;
      border-top: 1px solid rgba(255, 255, 255, 0.07);
    }

    .footer-logo {
      font-family: "Bebas Neue", sans-serif;
      font-size: 2.2rem;
      letter-spacing: 0.05em;
      color: var(--white);
    }

    .footer-logo span {
      color: var(--orange);
    }

    .footer-tagline {
      font-family: "Barlow Condensed", sans-serif;
      font-size: 0.75rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: rgba(248, 249, 245, 0.35);
      margin-top: 4px;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.9rem;
      color: rgba(248, 249, 245, 0.65);
      margin-bottom: 10px;
    }

    .contact-item .icon {
      font-size: 1rem;
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

    /* ============================================================
       UTILITIES
    ============================================================ */
    .text-orange {
      color: var(--orange) !important;
    }

    .text-green {
      color: var(--green-light) !important;
    }

    /* Responsive tweaks */
    @media (max-width: 576px) {
      .lang-btn label {
        font-size: 0.72rem;
        padding: 8px 6px;
      }
    }
  </style>
</head>

<body>
  <!-- ============================================================
     ANTI-SCAM STICKY BANNER
============================================================ -->
  <div id="scam-banner">
    <span class="shield">🛡️</span>
    Préinscription 100% GRATUITE &mdash; Ne payez RIEN à personne ! Toute
    demande d'argent est une arnaque.
    <span class="shield">🛡️</span>
  </div>

  <!-- ============================================================
     HERO SECTION
============================================================ -->
  <section id="hero" style="background:
    linear-gradient(to bottom,
        rgba(10, 30, 12, 0.72) 0%,
        rgba(10, 30, 12, 0.55) 60%,
        rgba(10, 30, 12, 0.82) 100%),
    url('{{ asset('assets/images/baniere.jpg') }}') center center / cover no-repeat;">
    <div class="pitch-circle"></div>

    <div class="hero-content">
      <div class="hero-tag">Côte d'Ivoire &bull; Saison &bull; 2026</div>

      <h1 class="hero-title">School<span>Foot</span></h1>

      <p class="hero-sub">
        La 1ère Téléréalité de Détection de Talents Footballistiques en Côte
        d'Ivoire
      </p>

      <div class="hero-free-badge">
        <span>✅</span>
        Préinscription 100% Gratuite &amp; Ouverte à Tous
      </div>

      <div class="hero-cta">
        <a href="#inscription" class="btn-main">
          ⚽ &nbsp;Je m'inscris maintenant
        </a>
      </div>
    </div>

    <div class="scroll-hint">
      <span>Découvrir</span>
      <span class="scroll-arrow">↓</span>
    </div>
  </section>

  <!-- ============================================================
     PRESENTATION SECTION
============================================================ -->
  <section id="presentation">
    <div class="container">
      <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
          <p class="section-label">C'est quoi SchoolFoot ?</p>
          <h2 class="section-title">
            Ton talent<br />mérite d'être<br /><span class="text-orange">vu.</span>
          </h2>
          <div class="divider-line"></div>
          <p class="pres-text">
            SchoolFoot est la toute première émission de téléréalité dédiée à
            la détection de jeunes talents du football non
            professionnel(Football du quartier). Si tu maîtrises le
            <strong>Grand Poto</strong>, le <strong>Petit Poto</strong> ou le
            <strong>Maracana</strong>, et que tu rêves d'un avenir dans le
            football professionnel, cette plateforme est faite pour toi.
            <br /><br />
            Pas besoin de réseau, pas besoin d'agent. Juste ton talent et ta
            détermination.
            <strong class="text-green">L'inscription est 100% gratuite.</strong>
          </p>
        </div>

        <div class="col-lg-6">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="pres-card">
                <span class="icon">🏆</span>
                <h4>Détection Officielle</h4>
                <p>
                  Des recruteurs professionnels évaluent ton jeu en direct
                  devant les caméras.
                </p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="pres-card">
                <span class="icon">📺</span>
                <h4>Téléréalité</h4>
                <p>
                  Ton parcours diffusé à la télévision. Deviens une star du
                  football ivoirien.
                </p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="pres-card">
                <span class="icon">🎯</span>
                <h4>17 à 22 Ans</h4>
                <p>
                  Ouvert aux joueurs de Grand Poto, Petit Poto et Maracana
                  sans réseau pro.
                </p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="pres-card">
                <span class="icon">🆓</span>
                <h4>Gratuit &amp; Sécurisé</h4>
                <p>
                  Aucun frais, aucun intermédiaire. Préinscription directe et
                  officielle.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- -------------------------------
    INSCRIPTION SECTION
  ------------------------------- --->
  <section id="inscription">
    <div class="container">
      <div class="text-center mb-5">
        <p class="section-label">Formulaire Officiel</p>
        <h2 class="section-title">
          Préinscription<br /><span class="text-orange">Gratuite</span>
        </h2>
        <div class="divider-line mx-auto"></div>
        <p style="color: rgba(248, 249, 245, 0.5); font-size: 0.93rem; max-width: 520px; margin: 0 auto;">
          Remplis tous les champs ci-dessous. Un membre de notre équipe te contactera sous 72h.
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

                <!-- Nom -->
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-nom">
                    <label class="form-label" for="nom">Nom <span class="required-star">*</span></label>
                    <input
                      type="text"
                      class="form-control"
                      id="nom"
                      name="nom"
                      placeholder="Ex : KOUASSI" />
                    <div class="invalid-msg">Veuillez entrer votre nom.</div>
                  </div>
                </div>

                <!-- Prénoms -->
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-prenom">
                    <label class="form-label" for="prenom">Prénoms <span class="required-star">*</span></label>
                    <input
                      type="text"
                      class="form-control"
                      id="prenom"
                      name="prenom"
                      placeholder="Ex : Jean-Paul" />
                    <div class="invalid-msg">Veuillez entrer vos prénoms.</div>
                  </div>
                </div>

                <!-- Date de naissance + Lieu sur une seule ligne -->
                <div class="col-12">
                  <label class="form-label">
                    Date &amp; Lieu de Naissance <span class="required-star">*</span>
                  </label>
                  <div class="row g-2">

                    <!-- Jour -->
                    <div class="col-6 col-sm-2">
                      <div class="field-wrap" id="wrap-jour">
                        <select class="form-select" id="jour" name="jour" aria-label="Jour de naissance">
                          <option value="">Jour</option>
                          <!-- Options générées dynamiquement -->
                        </select>
                        <div class="invalid-msg">Jour requis.</div>
                      </div>
                    </div>

                    <!-- Mois -->
                    <div class="col-6 col-sm-3">
                      <div class="field-wrap" id="wrap-mois">
                        <select class="form-select" id="mois" name="mois" aria-label="Mois de naissance">
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

                    <!-- Année -->
                    <div class="col-6 col-sm-3">
                      <div class="field-wrap" id="wrap-annee">
                        <select class="form-select" id="annee" name="annee" aria-label="Année de naissance">
                          <option value="">Année</option>
                          <!-- Options générées dynamiquement -->
                        </select>
                        <div class="invalid-msg">Année requise.</div>
                      </div>
                    </div>

                    <!-- Lieu de naissance -->
                    <div class="col-6 col-sm-4">
                      <div class="field-wrap" id="wrap-lieu-naissance">
                        <input
                          type="text"
                          class="form-control"
                          id="lieuNaissance"
                          name="lieuNaissance"
                          placeholder="Lieu de naissance" />
                        <div class="invalid-msg">Lieu de naissance requis.</div>
                      </div>
                    </div>

                  </div>
                  <div class="invalid-msg" id="dob-age-msg" style="display: none; margin-top: 6px">
                    ⚠️ Tu dois avoir entre 17 et 22 ans pour participer.
                  </div>
                </div>

                <!-- Téléphone candidat -->
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-candidat-tel">
                    <label class="form-label" for="candidatTel">
                      Votre numéro de téléphone <span class="required-star">*</span>
                    </label>
                    <input
                      type="tel"
                      class="form-control"
                      id="candidatTel"
                      name="candidatTel"
                      placeholder="Ex : 05 XX XX XX XX" />
                    <div class="invalid-msg">Entrez votre numéro personnel (min. 10 chiffres).</div>
                  </div>
                </div>

                <!-- Ville -->
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-ville">
                    <label class="form-label" for="ville">
                      Ville d'inscription <span class="required-star">*</span>
                    </label>
                    <select class="form-select" id="ville" name="ville">
                      <option value="">Sélectionner une ville…</option>
                      @foreach($data['villes'] as $ville)
                      <option value="{{ $ville }}">{{ $ville }}</option>
                      @endforeach
                    </select>
                    <div class="invalid-msg">Veuillez sélectionner votre ville.</div>
                  </div>
                </div>

              </div><!-- /.row identité -->

              <!-- ÉDUCATION -->
              <p class="form-section-title">📚 Éducation</p>
              <div class="row g-3">
                <div class="col-12">
                  <div class="field-wrap" id="wrap-niveau">
                    <label class="form-label" for="niveau">
                      Niveau d'études actuel / Dernier diplôme <span class="required-star">*</span>
                    </label>
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
                  <div class="field-wrap" id="wrap-langues">
                    <label class="form-label">Espagnol</label>
                    <div class="check-btn">
                      <input type="checkbox" id="lang-es" name="langues[]" value="Espagnol" />
                      <label for="lang-es">Je parle Espagnol</label>
                    </div>
                    <div class="invalid-msg">Cochez au moins une langue.</div>
                  </div>
                </div>
              </div>

              <!-- Niveaux de maîtrise -->
              <div class="row g-4 mt-4">
                <div class="col-md-4">
                  <div class="field-wrap">
                    <label class="form-label">Niveau en Français</label>
                    <div class="lang-group">
                      <div class="lang-btn">
                        <input type="radio" name="niveau_fr" id="fr-deb" value="Débutant" />
                        <label for="fr-deb">Débutant</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="niveau_fr" id="fr-int" value="Intermédiaire" />
                        <label for="fr-int">Intermédiaire</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="niveau_fr" id="fr-av" value="Avancé" />
                        <label for="fr-av">Avancé</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="field-wrap">
                    <label class="form-label">Niveau en Anglais</label>
                    <div class="lang-group">
                      <div class="lang-btn">
                        <input type="radio" name="niveau_en" id="en-deb" value="Débutant" />
                        <label for="en-deb">Débutant</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="niveau_en" id="en-int" value="Intermédiaire" />
                        <label for="en-int">Intermédiaire</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="niveau_en" id="en-av" value="Avancé" />
                        <label for="en-av">Avancé</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="field-wrap">
                    <label class="form-label">Niveau en Espagnol</label>
                    <div class="lang-group">
                      <div class="lang-btn">
                        <input type="radio" name="niveau_es" id="es-deb" value="Débutant" />
                        <label for="es-deb">Débutant</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="niveau_es" id="es-int" value="Intermédiaire" />
                        <label for="es-int">Intermédiaire</label>
                      </div>
                      <div class="lang-btn">
                        <input type="radio" name="niveau_es" id="es-av" value="Avancé" />
                        <label for="es-av">Avancé</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- CONTACT D'URGENCE -->
              <p class="form-section-title">📞 Contact d'Urgence</p>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-urgence-nom">
                    <label class="form-label" for="urgenceNom">
                      Nom &amp; Prénom du proche <span class="required-star">*</span>
                    </label>
                    <input
                      type="text"
                      class="form-control"
                      id="urgenceNom"
                      name="urgenceNom"
                      placeholder="Ex : BROU Adjoua Marie" />
                    <div class="invalid-msg">Veuillez entrer le nom du contact d'urgence.</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="field-wrap" id="wrap-urgence-tel">
                    <label class="form-label" for="urgenceTel">
                      Téléphone du proche <span class="required-star">*</span>
                    </label>
                    <input
                      type="tel"
                      class="form-control"
                      id="urgenceTel"
                      name="urgenceTel"
                      placeholder="Ex : 07 XX XX XX XX" />
                    <div class="invalid-msg">Entrez un numéro valide (min. 10 chiffres).</div>
                  </div>
                </div>
              </div>

              <!-- RAPPEL GRATUIT -->
              <div class="d-flex align-items-center gap-2 mt-4 p-3"
                style="background: rgba(35, 166, 61, 0.08); border: 1px solid rgba(35, 166, 61, 0.22); border-radius: 10px;">
                <span style="font-size: 1.3rem">🛡️</span>
                <p style="font-size: 0.82rem; color: rgba(248, 249, 245, 0.65); margin: 0; line-height: 1.5;">
                  <strong style="color: #6dffa0">Rappel :</strong> La préinscription est 100% gratuite.
                  SchoolFoot ne vous demandera <strong>jamais</strong> de payer pour participer.
                  Signalez toute demande d'argent.
                </p>
              </div>

              <button type="submit" class="btn-submit mt-4">⚽ &nbsp;Envoyer Ma Préinscription</button>

              <p style="text-align: center; font-size: 0.75rem; color: rgba(248, 249, 245, 0.28); margin-top: 14px;">
                <span class="required-star">*</span> Champs obligatoires. Vos données sont traitées de manière confidentielle.
              </p>

            </form>
          </div><!-- /.form-card -->
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
     FOOTER
============================================================ -->
  <footer id="footer">
    <div class="container">
      <!-- <div class="row g-4">
          <div class="col-md-5">
            <div class="footer-logo">School<span>Foot</span></div>
            <div class="footer-tagline">
              La téléréalité du football ivoirien
            </div>
            <p
              style="
                color: rgba(248, 249, 245, 0.4);
                font-size: 0.83rem;
                margin-top: 14px;
                line-height: 1.7;
              "
            >
              Première émission officielle de détection de jeunes talents
              footballistiques en Côte d'Ivoire. Saison 1.
            </p>
          </div>

          <div class="col-md-4">
            <p
              style="
                font-family: &quot;Barlow Condensed&quot;, sans-serif;
                font-weight: 700;
                font-size: 0.75rem;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--orange);
                margin-bottom: 14px;
              "
            >
              Contacts Officiels
            </p>
            <div class="contact-item">
              <span class="icon">📧</span>
              <span
                ><a
                  href="/cdn-cgi/l/email-protection"
                  class="__cf_email__"
                  data-cfemail="36555958425755427645555e59595a5059594218555f"
                  >[email&#160;protected]</a
                ></span
              >
            </div>
            <div class="contact-item">
              <span class="icon">📱</span>
              <span>WhatsApp Officiel : +225 XX XX XX XX XX</span>
            </div>
            <div class="contact-item">
              <span class="icon">📍</span>
              <span>Abidjan, Côte d'Ivoire</span>
            </div>
          </div>

          <div class="col-md-3">
            <p
              style="
                font-family: &quot;Barlow Condensed&quot;, sans-serif;
                font-weight: 700;
                font-size: 0.75rem;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--orange);
                margin-bottom: 14px;
              "
            >
              Anti-Arnaque
            </p>
            <div
              style="
                background: rgba(179, 0, 0, 0.12);
                border: 1px solid rgba(179, 0, 0, 0.35);
                border-radius: 10px;
                padding: 14px;
              "
            >
              <p
                style="
                  font-size: 0.8rem;
                  color: rgba(248, 249, 245, 0.6);
                  line-height: 1.6;
                  margin: 0;
                "
              >
                ⚠️ Tout recrutement <strong>payant</strong> n'est
                <strong>pas</strong> de SchoolFoot.ci. Ne payez jamais pour
                participer.
              </p>
            </div>
          </div>
        </div> -->

      <div class="legal-text">
        &copy; 2026 SchoolFoot — Tous droits réservés. &bull; Les données
        collectées sont utilisées uniquement dans le cadre de la
        préinscription au programme SchoolFoot. Conformément à la
        réglementation en vigueur, vous disposez d'un droit d'accès, de
        modification et de suppression de vos données. &bull; Site non
        commercial — Préinscription gratuite.
      </div>
    </div>
  </footer>

  <!-- ============================================================
     SUCCESS OVERLAY
============================================================ -->
  <div id="success-overlay">
    <div class="success-box">
      <span class="success-icon">⚽</span>
      <h2>Félicitations !</h2>
      <p>
        Ta préinscription a bien été enregistrée.<br />
        Notre équipe te contactera dans les <strong>72 heures</strong> sur le
        numéro que tu as indiqué.<br /><br />
        <span style="color: #6dffa0; font-weight: 700">Rappel : SchoolFoot ne te demandera JAMAIS de payer.</span>
      </p>
      <button class="btn-close-success" id="closeSuccess">Fermer ✓</button>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script
    data-cfasync="false"
    src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /* ============================================================
     FORM VALIDATION & SUBMISSION
  ============================================================ */

    /* ======================================================
     POPULATE JOUR & ANNEE SELECTS
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
      const currentYear = 2009; // Les candidats doivent avoir au moins 17 ans en 2026, donc nés en 2009 ou avant
      for (let y = currentYear; y >= 2004; y--) {
        const o = document.createElement("option");
        o.value = y;
        o.textContent = y;
        anneeSel.appendChild(o);
      }
    })();

    /* ======================================================
     FORM VALIDATION & SUBMISSION
  ====================================================== */
    const form = document.getElementById("inscriptionForm");

    function setError(wrapId, show) {
      const el = document.getElementById(wrapId);
      if (!el) return;
      el.classList.toggle("field-error", show);
    }

    function validPhone(val) {
      return val.replace(/\D/g, "").length >= 8;
    }

    function getChecked(name) {
      return Array.from(
        document.querySelectorAll('input[name="' + name + '"]:checked')
      ).map((i) => i.value);
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

    function validateForm() {
      let valid = true;

      /* Nom */
      const nom = document.getElementById("nom").value.trim();
      setError("wrap-nom", !nom);
      if (!nom) valid = false;

      /* Prénoms */
      const prenom = document.getElementById("prenom").value.trim();
      setError("wrap-prenom", !prenom);
      if (!prenom) valid = false;

      /* Date de naissance */
      const jour = document.getElementById("jour").value;
      const mois = document.getElementById("mois").value;
      const annee = document.getElementById("annee").value;
      setError("wrap-jour", !jour);
      setError("wrap-mois", !mois);
      setError("wrap-annee", !annee);
      if (!jour) valid = false;
      if (!mois) valid = false;
      if (!annee) valid = false;

      /* Lieu de naissance */
      const lieu = document.getElementById("lieuNaissance").value.trim();
      setError("wrap-lieu-naissance", !lieu);
      if (!lieu) valid = false;

      /* Validation âge 17-22 ans */
      const ageMsg = document.getElementById("dob-age-msg");
      if (jour && mois && annee) {
        const age = calcAge();
        if (age === null || age < 17 || age > 22) {
          ageMsg.style.display = "block";
          setError("wrap-annee", true);
          valid = false;
        } else {
          ageMsg.style.display = "none";
        }
      }

      /* Téléphone candidat */
      const candidatTel = document.getElementById("candidatTel").value.trim();
      setError("wrap-candidat-tel", !validPhone(candidatTel));
      if (!validPhone(candidatTel)) valid = false;

      /* Ville */
      const ville = document.getElementById("ville").value;
      setError("wrap-ville", !ville);
      if (!ville) valid = false;

      /* Niveau études */
      const niveau = document.getElementById("niveau").value;
      setError("wrap-niveau", !niveau);
      if (!niveau) valid = false;

      /* Langues cochées */
      const langues = getChecked("langues[]");
      setError("wrap-langues", langues.length === 0);
      if (langues.length === 0) valid = false;

      /* Contact urgence nom */
      const urgenceNom = document.getElementById("urgenceNom").value.trim();
      setError("wrap-urgence-nom", !urgenceNom);
      if (!urgenceNom) valid = false;

      /* Contact urgence tel */
      const urgenceTel = document.getElementById("urgenceTel").value.trim();
      setError("wrap-urgence-tel", !validPhone(urgenceTel));
      if (!validPhone(urgenceTel)) valid = false;

      return valid;
    }

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
          // ✅ Succès
          showToast("success", data.message || "✅ Ta préinscription a bien été envoyée ! Notre équipe te contactera sous 72h.");
          form.reset();
          form.querySelectorAll(".field-error").forEach(el => el.classList.remove("field-error"));
          document.getElementById("dob-age-msg").style.display = "none";
          form.scrollIntoView({
            behavior: "smooth",
            block: "start"
          });

        } else if (response.status === 422) {
          // ❌ Erreurs de validation Laravel
          const errors = data.errors || {};
          Object.keys(errors).forEach(field => {
            // Mapping champ → wrap
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
              langues: "wrap-langues",
              urgenceNom: "wrap-urgence-nom",
              urgenceTel: "wrap-urgence-tel",
            };
            if (map[field]) setError(map[field], true);
          });
          showToast("error", "Vérifie les champs en rouge.");

        } else {
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

    /* Toast notification */
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

    /* Animations toast */
    const style = document.createElement("style");
    style.textContent = `
  @keyframes slideIn  { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
  @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }
  .spinner {
    display: inline-block; width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff;
    border-radius: 50%; animation: spin 0.7s linear infinite;
    vertical-align: middle; margin-right: 6px;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
`;
    document.head.appendChild(style);

    /* Clear error on interaction */
    form.querySelectorAll("input, select").forEach(function(input) {
      ["input", "change"].forEach(function(evt) {
        input.addEventListener(evt, function() {
          const wrap = this.closest(".field-wrap");
          if (wrap) wrap.classList.remove("field-error");
          document.getElementById("dob-age-msg").style.display = "none";
        });
      });
    });
  </script>
</body>

</html>