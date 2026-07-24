<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Identification — Inscription Officielle — SchoolFoot.ci</title>
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

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      font-family: "Nunito", sans-serif;
      background: var(--black);
      color: var(--white);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    .text-orange { color: var(--orange) !important; }

    /* BANNIÈRE OFFICIELLE */
    #scam-banner {
      background: linear-gradient(90deg, #b30000, #d40000, #b30000);
      background-size: 200% 100%;
      animation: bannerPulse 3s ease-in-out infinite;
      padding: 9px 16px;
      text-align: center;
      font-family: "Barlow Condensed", sans-serif;
      font-size: clamp(0.75rem, 2.2vw, 0.95rem);
      font-weight: 700;
      letter-spacing: 0.04em;
      color: #fff;
      text-transform: uppercase;
      border-bottom: 2px solid var(--orange);
    }

    @keyframes bannerPulse {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    /* EN-TÊTE */
    .top-bar {
      padding: 18px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-family: "Barlow Condensed", sans-serif;
    }

    .top-bar .logo {
      font-family: "Bebas Neue", sans-serif;
      font-size: 1.5rem;
      color: var(--orange);
      text-decoration: none;
    }

    .nav-actions { display: flex; gap: 10px; align-items: center; }

    .btn-nav {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--white);
      text-decoration: none;
      font-weight: 700;
      letter-spacing: .04em;
      font-size: .85rem;
      padding: 7px 14px;
      border-radius: 50px;
      border: 1px solid rgba(255,255,255,.25);
      transition: border-color .2s, color .2s;
    }

    .btn-nav:hover { border-color: var(--orange); color: var(--orange-light); }

    /* CONTENU */
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      position: relative;
    }

    main::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top, var(--pitch) 0%, var(--black) 70%);
      z-index: -1;
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
      font-size: clamp(2rem, 5vw, 3rem);
      line-height: 1;
      color: var(--white);
      margin-bottom: 16px;
    }

    .divider-line {
      width: 60px; height: 4px;
      background: linear-gradient(90deg, var(--orange), var(--green-light));
      border-radius: 2px;
      margin-bottom: 24px;
    }

    .form-wrap { width: 100%; max-width: 520px; }

    .form-card {
      background: #1c1f22;
      border: 1px solid rgba(255, 255, 255, 0.09);
      border-radius: 20px;
      padding: clamp(24px, 5vw, 46px);
      box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
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

    .form-control {
      background: #272b30 !important;
      border: 1.5px solid #3a3f47 !important;
      color: #f0f2f5 !important;
      border-radius: 10px !important;
      padding: 12px 14px !important;
      font-family: "Nunito", sans-serif;
      font-size: 0.95rem;
    }

    .form-control:focus {
      border-color: var(--orange) !important;
      box-shadow: 0 0 0 .2rem rgba(244, 124, 32, .2) !important;
    }

    .form-control::placeholder { color: #74787f; }

    .input-group-text {
      background: #272b30;
      border: 1.5px solid #3a3f47;
      border-right: none;
      color: var(--white);
      font-weight: 700;
      border-radius: 10px 0 0 10px !important;
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 10px 10px 0 !important;
    }

    .input-group:focus-within .input-group-text { border-color: var(--orange); }

    .btn-submit {
      background: linear-gradient(135deg, var(--orange), var(--orange-dark));
      color: #fff;
      font-family: "Bebas Neue", sans-serif;
      font-size: 1.4rem;
      letter-spacing: 0.1em;
      border: none;
      border-radius: 10px;
      padding: 16px 40px;
      width: 100%;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 8px 30px rgba(244, 124, 32, 0.3);
      margin-top: 8px;
    }

    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(244, 124, 32, 0.45); }

    .invalid-msg { font-size: 0.85rem; color: #ff7070; margin-top: 5px; }

    .steps-hint {
      display: flex;
      justify-content: space-between;
      gap: 8px;
      margin-bottom: 28px;
      flex-wrap: wrap;
    }

    .step-pill {
      flex: 1;
      min-width: 110px;
      text-align: center;
      padding: 8px 6px;
      border-radius: 8px;
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.08);
      font-family: "Barlow Condensed", sans-serif;
      font-size: 0.72rem;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: #9a9d94;
    }

    .step-pill.active {
      background: rgba(244,124,32,.15);
      border-color: var(--orange);
      color: var(--orange-light);
      font-weight: 700;
    }
  </style>
</head>

<body>

  <!-- BANNIÈRE OFFICIELLE -->
  <div id="scam-banner">
    🛡️ Les frais (65 000 FCFA) se paient UNIQUEMENT sur cette plateforme. Ne payez jamais un tiers ! 🛡️
  </div>

  <!-- EN-TÊTE -->
  <div class="top-bar">
    <a href="{{ route('accueil') }}" class="logo">SchoolFoot.ci</a>
    <div class="nav-actions">
      <a href="{{ route('accueil') }}" class="btn-nav"><i class="bi bi-house-door"></i> Accueil</a>
      @if (session('espace_candidat_id'))
        <a href="{{ route('espace.dashboard') }}" class="btn-nav"><i class="bi bi-person-badge"></i> Mon espace</a>
      @else
        <a href="{{ route('espace.connexion') }}" class="btn-nav"><i class="bi bi-person-badge"></i> Espace candidat</a>
      @endif
    </div>
  </div>

  <!-- IDENTIFICATION -->
  <main>
    <div class="form-wrap">
      <div class="text-center mb-4">
        <p class="section-label">Étape 1 / 4</p>
        <h1 class="section-title">Retrouve<br /><span class="text-orange">ton dossier</span></h1>
        <div class="divider-line mx-auto"></div>
      </div>

      <div class="form-card">
        <div class="steps-hint">
          <div class="step-pill active">1. Identification</div>
          <div class="step-pill">2. Confirmation</div>
          <div class="step-pill">3. Parents</div>
          <div class="step-pill">4. Paiement</div>
        </div>

        <p style="color: rgba(248,249,245,0.6); font-size: 0.92rem; margin-bottom: 22px;">
          Entre le numéro de téléphone utilisé lors de ta préinscription pour continuer.
        </p>

        @if ($errors->any())
          <div class="mb-3">
            @foreach ($errors->all() as $error)
              <p class="invalid-msg mb-1">{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('finalisation.verifier') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="telephone">Numéro de téléphone</label>
            <div class="input-group">
              <span class="input-group-text">+225</span>
              <input type="tel" class="form-control tel-input" id="telephone" name="telephone" inputmode="numeric"
                     maxlength="10" pattern="[0-9]{10}" placeholder="07XXXXXXXX"
                     value="{{ old('telephone') }}" required>
            </div>
          </div>
          <button type="submit" class="btn-submit">Continuer →</button>
        </form>
      </div>
    </div>
  </main>

  <script>
    document.querySelectorAll('.tel-input').forEach(function (el) {
      el.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
      });
    });
  </script>

</body>

</html>
