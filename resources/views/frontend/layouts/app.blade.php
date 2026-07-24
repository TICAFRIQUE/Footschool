<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'SchoolFoot.ci') — SchoolFoot.ci</title>
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

    *, *::before, *::after { box-sizing: border-box; }

    body {
      font-family: "Nunito", sans-serif;
      background: var(--black) radial-gradient(circle at top, var(--pitch) 0%, var(--black) 70%);
      color: var(--white);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .brand-bar {
      padding: 18px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-family: "Barlow Condensed", sans-serif;
    }

    .brand-bar { gap: 10px; flex-wrap: wrap; }

    .brand-bar .logo {
      font-family: "Bebas Neue", sans-serif;
      font-size: 1.6rem;
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

    .btn-nav.disabled {
      opacity: .45;
      pointer-events: none;
      cursor: default;
    }

    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .step-card {
      width: 100%;
      max-width: 560px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: clamp(24px, 4vw, 40px);
      backdrop-filter: blur(6px);
    }

    .step-card h1 {
      font-family: "Bebas Neue", sans-serif;
      letter-spacing: .03em;
      color: var(--white);
      font-size: clamp(1.6rem, 3vw, 2.1rem);
      margin-bottom: 4px;
    }

    .step-label {
      font-family: "Barlow Condensed", sans-serif;
      text-transform: uppercase;
      color: var(--orange-light);
      letter-spacing: .08em;
      font-size: .85rem;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .step-card p.subtitle {
      color: #cfd2c9;
      margin-bottom: 24px;
    }

    .form-label { color: #e8e9e3; font-weight: 600; }

    .form-control, .form-select {
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.18);
      color: var(--white);
    }

    .form-control:focus, .form-select:focus {
      background: rgba(255,255,255,.09);
      border-color: var(--orange);
      color: var(--white);
      box-shadow: 0 0 0 .2rem rgba(244, 124, 32, .25);
    }

    .form-control::placeholder { color: #9a9d94; }

    .input-group-text {
      background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.18);
      border-right: none;
      color: var(--white);
      font-weight: 700;
    }

    .input-group:focus-within .input-group-text { border-color: var(--orange); }
    .input-group .form-control { border-left: none; }
    .input-group:focus-within .form-control { border-left: none; }

    .btn-primary-foot {
      background: var(--orange);
      border: none;
      color: var(--black);
      font-weight: 700;
      padding: 10px 22px;
      border-radius: 10px;
    }

    .btn-primary-foot:hover { background: var(--orange-light); color: var(--black); }

    .btn-outline-foot {
      background: transparent;
      border: 1px solid rgba(255,255,255,.3);
      color: var(--white);
      border-radius: 10px;
    }

    .btn-outline-foot:hover { border-color: var(--orange); color: var(--orange-light); }

    .info-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid rgba(255,255,255,.08);
    }

    .info-row span:first-child { color: #b6b8b0; }
    .info-row span:last-child { font-weight: 700; }

    .badge-statut { font-family: "Barlow Condensed", sans-serif; letter-spacing: .04em; }
  </style>
  @stack('styles')
</head>

<body>
  <div class="brand-bar">
    <a href="{{ route('accueil') }}" class="logo">SchoolFoot.ci</a>
    <div class="nav-actions">
      <a href="{{ route('accueil') }}" class="btn-nav"><i class="bi bi-house-door"></i> Accueil</a>
      @if (request()->routeIs('espace.dashboard'))
        <span class="btn-nav disabled"><i class="bi bi-person-badge"></i> Mon espace</span>
      @elseif (session('espace_candidat_id'))
        <a href="{{ route('espace.dashboard') }}" class="btn-nav"><i class="bi bi-person-badge"></i> Mon espace</a>
      @else
        <a href="{{ route('espace.connexion') }}" class="btn-nav"><i class="bi bi-person-badge"></i> Espace candidat</a>
      @endif
    </div>
  </div>

  <main>
    @yield('content')
  </main>

  <script>
    document.querySelectorAll('.tel-input').forEach(function (el) {
      el.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
      });
    });
  </script>
  @stack('scripts')
</body>

</html>
