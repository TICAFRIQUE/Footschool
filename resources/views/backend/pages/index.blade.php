@extends('backend.layouts.master')
@section('title') Tableau de bord @endsection

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    /* ══════════════════════════════════════════════════════════════════
   TOUTES LES VARIABLES ET STYLES SONT SCOPED SOUS .dash-wrap
   → Zéro conflit avec Velzon / Bootstrap / data-theme existant
   → Le dark mode dashboard utilise .dash-dark sur .dash-wrap
     et ne touche JAMAIS à data-theme de <html> (appartient à Velzon)
══════════════════════════════════════════════════════════════════ */

    /* ── Palette tokens — mode CLAIR par défaut ── */
    .dash-wrap {
        --d-brand: #4361ee;
        --d-purple: #7209b7;
        --d-pink: #f72585;
        --d-teal: #4cc9f0;
        --d-green: #06d6a0;
        --d-amber: #ffd166;

        --d-bg0: #ffffff;
        --d-bg1: #f5f6ff;
        --d-bg2: #eaecfb;
        --d-border: rgba(67, 97, 238, 0.12);

        /* Texte — FORCÉS SOMBRES pour garantir la lisibilité en mode clair */
        --d-text: #0d1b4b;
        --d-text2: #4a5280;
        --d-text3: #8891b8;

        --d-sh1: 0 2px 10px rgba(67, 97, 238, .07);
        --d-sh2: 0 6px 24px rgba(67, 97, 238, .13);

        --d-r1: 10px;
        --d-r2: 14px;
        --d-r3: 20px;

        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--d-text);
        padding-bottom: 100px;
    }

    /* ── Mode sombre : classe .dash-dark sur .dash-wrap uniquement ── */
    .dash-wrap.dash-dark {
        --d-bg0: #111827;
        --d-bg1: #1a2336;
        --d-bg2: #222d42;
        --d-border: rgba(99, 132, 255, .13);
        --d-text: #dde3ff;
        --d-text2: #8892b8;
        --d-text3: #4a5278;
        --d-sh1: 0 2px 10px rgba(0, 0, 0, .3);
        --d-sh2: 0 6px 24px rgba(0, 0, 0, .45);
    }

    /* ══ HERO ════════════════════════════════════════════════════ */
    .dash-hero {
        position: relative;
        background: linear-gradient(135deg, #4361ee 0%, #7209b7 55%, #f72585 100%);
        border-radius: var(--d-r3);
        padding: 30px 34px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .dash-hero::before,
    .dash-hero::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .dash-hero::before {
        top: -70px;
        right: -70px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, .06);
    }

    .dash-hero::after {
        bottom: -90px;
        left: 28%;
        width: 360px;
        height: 360px;
        background: rgba(255, 255, 255, .04);
    }

    .hero-inner {
        position: relative;
        z-index: 1;
    }

    /* Tous les textes du hero sont BLANC sur fond dégradé sombre — safe */
    .hero-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(20px, 2.8vw, 28px);
        font-weight: 700;
        color: #ffffff !important;
        /* !important pour battre Velzon */
        margin: 0 0 5px;
        letter-spacing: -.3px;
    }

    .hero-sub {
        font-size: 13.5px;
        color: rgba(255, 255, 255, .72) !important;
        max-width: 460px;
        margin: 0;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255, 255, 255, .16);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #ffffff !important;
        white-space: nowrap;
    }

    .hero-pill .live-dot {
        width: 7px;
        height: 7px;
        background: #06d6a0;
        border-radius: 50%;
        animation: dblink 2s infinite;
    }

    @keyframes dblink {

        0%,
        100% {
            opacity: 1;
            transform: scale(1)
        }

        50% {
            opacity: .5;
            transform: scale(1.3)
        }
    }

    .hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, .13);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: var(--d-r1);
        padding: 7px 13px;
        font-size: 12.5px;
        color: #ffffff !important;
        white-space: nowrap;
    }

    .hero-chip i {
        opacity: .8;
    }

    .hero-dark-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, .13);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: var(--d-r1);
        padding: 7px 13px;
        font-size: 12.5px;
        color: #ffffff !important;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background .2s;
        white-space: nowrap;
    }

    .hero-dark-btn:hover {
        background: rgba(255, 255, 255, .25);
    }

    /* ══ KPI GRID ════════════════════════════════════════════════ */
    .d-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    .d-kpi {
        background: var(--d-bg0);
        border: 1px solid var(--d-border);
        border-radius: var(--d-r2);
        padding: 20px 18px;
        box-shadow: var(--d-sh1);
        position: relative;
        overflow: hidden;
        transition: transform .25s, box-shadow .25s;
        animation: dfadeUp .4s ease both;
    }

    .d-kpi:hover {
        transform: translateY(-3px);
        box-shadow: var(--d-sh2);
    }

    .d-kpi::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--d-accent, var(--d-brand));
        border-radius: var(--d-r2) var(--d-r2) 0 0;
    }

    /* Textes KPI — scoped, jamais blancs en mode clair */
    .d-kpi-label {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .9px;
        text-transform: uppercase;
        color: var(--d-text3);
        margin: 0 0 9px;
    }

    .d-kpi-value {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(24px, 2.8vw, 32px);
        font-weight: 700;
        color: var(--d-text);
        /* ← jamais blanc en mode clair */
        line-height: 1;
        margin: 0 0 9px;
    }

    .d-kpi-footer {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        color: var(--d-text2);
    }

    .d-kpi-icon {
        position: absolute;
        top: 18px;
        right: 16px;
        width: 38px;
        height: 38px;
        border-radius: var(--d-r1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        background: color-mix(in srgb, var(--d-accent, var(--d-brand)) 11%, transparent);
        color: var(--d-accent, var(--d-brand));
    }

    .d-badge {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 999px;
    }

    .d-badge-up {
        background: rgba(6, 214, 160, .12);
        color: #059669;
    }

    .d-badge-down {
        background: rgba(247, 37, 133, .12);
        color: #dc2626;
    }

    /* ══ CARTES GRAPHIQUES ════════════════════════════════════════ */
    .d-card {
        background: var(--d-bg0);
        border: 1px solid var(--d-border);
        border-radius: var(--d-r2);
        box-shadow: var(--d-sh1);
        overflow: hidden;
        animation: dfadeUp .5s ease both;
    }

    .d-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 18px 20px 0;
        gap: 12px;
    }

    .d-card-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 14.5px;
        font-weight: 600;
        color: var(--d-text);
        /* ← scoped, jamais blanc */
        margin: 0 0 2px;
    }

    .d-card-sub {
        font-size: 11.5px;
        color: var(--d-text3);
        margin: 0;
    }

    .d-card-body {
        padding: 6px 8px 10px;
    }

    .d-period-sel {
        background: var(--d-bg2);
        border: 1px solid var(--d-border);
        color: var(--d-text2);
        font-size: 11.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 5px 10px;
        border-radius: var(--d-r1);
        cursor: pointer;
        outline: none;
        transition: border-color .2s;
        flex-shrink: 0;
    }

    .d-period-sel:hover {
        border-color: var(--d-brand);
    }

    /* ══ GRILLES ═════════════════════════════════════════════════ */
    .d-grid-8-4 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .d-grid-6-6 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    /* ══ SKELETON ═══════════════════════════════════════════════ */
    .d-skel {
        background: linear-gradient(90deg, var(--d-bg2) 25%, var(--d-bg1) 50%, var(--d-bg2) 75%);
        background-size: 300% 100%;
        animation: dshimmer 1.6s infinite;
        border-radius: var(--d-r1);
    }

    @keyframes dshimmer {
        0% {
            background-position: 300% 0
        }

        100% {
            background-position: -300% 0
        }
    }

    .d-chart-shell {
        height: 285px;
    }

    /* ══ TABLE ═══════════════════════════════════════════════════ */
    .d-table-wrap {
        background: var(--d-bg0);
        border: 1px solid var(--d-border);
        border-radius: var(--d-r2);
        box-shadow: var(--d-sh1);
        overflow: hidden;
        margin-bottom: 14px;
    }

    .d-table-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--d-border);
    }

    .d-table-link {
        font-size: 12.5px;
        color: var(--d-brand);
        text-decoration: none;
        font-weight: 600;
    }

    .d-table-link:hover {
        color: var(--d-purple);
    }

    .d-table {
        width: 100%;
        border-collapse: collapse;
    }

    .d-table th {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--d-text3);
        /* ← scoped */
        padding: 11px 18px;
        text-align: left;
        background: var(--d-bg1);
        border-bottom: 1px solid var(--d-border);
        white-space: nowrap;
    }

    .d-table td {
        padding: 12px 18px;
        font-size: 13px;
        color: var(--d-text);
        /* ← scoped : JAMAIS blanc en mode clair */
        border-bottom: 1px solid var(--d-border);
        vertical-align: middle;
    }

    .d-table tr:last-child td {
        border-bottom: none;
    }

    .d-table tbody tr:hover {
        background: var(--d-bg1);
    }

    .d-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--d-brand), var(--d-purple));
        color: #fff;
        font-size: 10.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 9px;
    }

    .d-niveau {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 999px;
        background: var(--d-bg2);
        color: var(--d-text2);
    }

    /* ══ BOTTOM NAV ══════════════════════════════════════════════ */
    .d-bnav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--d-bg0);
        border-top: 1px solid var(--d-border);
        padding: 8px 0 max(8px, env(safe-area-inset-bottom));
        z-index: 1050;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, .07);
    }

    .d-bnav-inner {
        display: flex;
        justify-content: space-around;
    }

    .d-bnav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        font-size: 10px;
        font-weight: 600;
        color: var(--d-text3);
        text-decoration: none;
        padding: 4px 14px;
        border-radius: var(--d-r1);
        transition: color .2s;
    }

    .d-bnav-item i {
        font-size: 19px;
    }

    .d-bnav-item.active {
        color: var(--d-brand);
    }

    /* ══ APEXCHARTS dark overrides (scoped) ══════════════════════ */
    .dash-dark .apexcharts-canvas svg text {
        fill: var(--d-text2) !important;
    }

    .dash-dark .apexcharts-legend-text {
        color: var(--d-text2) !important;
    }

    .dash-dark .apexcharts-gridline {
        stroke: var(--d-bg2) !important;
    }

    .dash-dark .apexcharts-tooltip {
        background: var(--d-bg1) !important;
        border-color: var(--d-border) !important;
        color: var(--d-text) !important;
    }

    .dash-dark .apexcharts-tooltip-title {
        background: var(--d-bg2) !important;
        border-color: var(--d-border) !important;
        color: var(--d-text) !important;
    }

    /* ══ ANIMATIONS ═════════════════════════════════════════════ */
    @keyframes dfadeUp {
        from {
            opacity: 0;
            transform: translateY(16px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    .d-kpi:nth-child(1) {
        animation-delay: .05s
    }

    .d-kpi:nth-child(2) {
        animation-delay: .10s
    }

    .d-kpi:nth-child(3) {
        animation-delay: .15s
    }

    .d-kpi:nth-child(4) {
        animation-delay: .20s
    }

    .d-kpi:nth-child(5) {
        animation-delay: .25s
    }

    /* ══ RESPONSIVE ══════════════════════════════════════════════ */
    @media(max-width:1200px) {
        .d-kpi-grid {
            grid-template-columns: repeat(3, 1fr)
        }
    }

    @media(max-width:1024px) {
        .d-grid-8-4 {
            grid-template-columns: 1fr
        }
    }

    @media(max-width:768px) {
        .dash-wrap {
            padding-bottom: 90px
        }

        .dash-hero {
            padding: 20px 18px
        }

        .hero-title {
            font-size: 18px !important
        }

        .d-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px
        }

        .d-grid-6-6 {
            grid-template-columns: 1fr
        }

        .d-bnav {
            display: block
        }
    }

    @media(max-width:420px) {
        .d-kpi-grid {
            grid-template-columns: 1fr
        }
    }
</style>
@endsection


@section('content')
<div class="dash-wrap" id="dashWrap">

    {{-- ══════════ HERO ══════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="hero-inner d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div>
                @auth
                <h1 class="hero-title">Bonjour, {{ Auth::user()->username }} 👋</h1>
                @else
                <h1 class="hero-title">Tableau de bord</h1>
                @endauth
                <p class="hero-sub">Vue d'ensemble de votre plateforme de recrutement</p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="hero-pill">
                    <span class="live-dot"></span>
                    <span id="liveCount">{{ number_format($stats['total']) }} candidats</span>
                </span>
                <span class="hero-chip"><i class="ri-calendar-line"></i><span id="heroDate">—</span></span>
                <span class="hero-chip"><i class="ri-time-line"></i><span id="heroTime">—</span></span>
                <button class="hero-dark-btn" onclick="dashToggleDark()" id="darkBtn">
                    <i class="ri-moon-line" id="darkIcon"></i><span id="darkLabel">Sombre</span>
                </button>
            </div>

        </div>
    </div>

    {{-- ══════════ KPI CARDS ═══════════════════════════════ --}}
    <div class="d-kpi-grid">

        <div class="d-kpi" style="--d-accent:#4361ee">
            <div class="d-kpi-icon"><i class="ri-team-line"></i></div>
            <p class="d-kpi-label">Total candidats</p>
            <p class="d-kpi-value">{{ number_format($stats['total']) }}</p>
            <div class="d-kpi-footer">
                @if(($stats['croissance'] ?? 0) >= 0)
                <span class="d-badge d-badge-up"><i class="ri-arrow-up-s-line"></i>{{ $stats['croissance'] ?? 0 }}%</span>
                @else
                <span class="d-badge d-badge-down"><i class="ri-arrow-down-s-line"></i>{{ abs($stats['croissance'] ?? 0) }}%</span>
                @endif
                <span>vs semaine dernière</span>
            </div>
        </div>

        <div class="d-kpi" style="--d-accent:#06d6a0">
            <div class="d-kpi-icon"><i class="ri-user-add-line"></i></div>
            <p class="d-kpi-label">Aujourd'hui</p>
            <p class="d-kpi-value">{{ $stats['aujourdhui'] }}</p>
            <div class="d-kpi-footer">
                <i class="ri-calendar-check-line" style="color:#06d6a0"></i>
                <span>Nouvelles inscriptions</span>
            </div>
        </div>

        <div class="d-kpi" style="--d-accent:#4cc9f0">
            <div class="d-kpi-icon"><i class="ri-map-pin-2-line"></i></div>
            <p class="d-kpi-label">Villes couvertes</p>
            <p class="d-kpi-value">{{ $stats['villes'] }}</p>
            <div class="d-kpi-footer">
                <i class="ri-map-pin-line" style="color:#4cc9f0"></i>
                <span>Top : <strong>{{ $stats['top_ville'] ?? '—' }}</strong></span>
            </div>
        </div>

        <div class="d-kpi" style="--d-accent:#ffd166">
            <div class="d-kpi-icon"><i class="ri-graduation-cap-line"></i></div>
            <p class="d-kpi-label">Niveaux d'études</p>
            <p class="d-kpi-value">{{ $stats['niveaux'] }}</p>
            <div class="d-kpi-footer">
                <i class="ri-book-open-line" style="color:#ffd166"></i>
                <span>Diplômes distincts</span>
            </div>
        </div>

        <div class="d-kpi" style="--d-accent:#f72585">
            <div class="d-kpi-icon"><i class="ri-shield-check-line"></i></div>
            <p class="d-kpi-label">Profils complets</p>
            <p class="d-kpi-value">{{ $stats['completude'] ?? 0 }}<span style="font-size:16px;font-weight:500">%</span></p>
            <div class="d-kpi-footer">
                <i class="ri-bar-chart-line" style="color:#f72585"></i>
                <span>Tous champs remplis</span>
            </div>
        </div>

    </div>

    {{-- ══════════ INSCRIPTIONS + VILLES ═══════════════════ --}}
    <div class="d-grid-8-4">

        <div class="d-card">
            <div class="d-card-header">
                <div>
                    <p class="d-card-title">Évolution des inscriptions</p>
                    <p class="d-card-sub">Nouveaux candidats par jour</p>
                </div>
                <select class="d-period-sel" id="periodeInscriptions">
                    <option value="7">7 jours</option>
                    <option value="30" selected>30 jours</option>
                    <option value="90">3 mois</option>
                </select>
            </div>
            <div class="d-card-body">
                <div id="wrapInsc" class="d-chart-shell d-skel">
                    <div id="chartInsc"></div>
                </div>
            </div>
        </div>

        <div class="d-card">
            <div class="d-card-header">
                <div>
                    <p class="d-card-title">Répartition par ville</p>
                    <p class="d-card-sub">Top 6</p>
                </div>
            </div>
            <div class="d-card-body">
                <div id="wrapCity" class="d-chart-shell d-skel">
                    <div id="chartCity"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════ ÉDUCATION + ÂGES ═══════════════════════ --}}
    <div class="d-grid-6-6">

        <div class="d-card">
            <div class="d-card-header">
                <div>
                    <p class="d-card-title">Niveaux d'études</p>
                    <p class="d-card-sub">Distribution des diplômes</p>
                </div>
            </div>
            <div class="d-card-body">
                <div id="wrapEdu" class="d-chart-shell d-skel">
                    <div id="chartEdu"></div>
                </div>
            </div>
        </div>

        <div class="d-card">
            <div class="d-card-header">
                <div>
                    <p class="d-card-title">Tranches d'âge</p>
                    <p class="d-card-sub">Pyramide des âges</p>
                </div>
            </div>
            <div class="d-card-body">
                <div id="wrapAge" class="d-chart-shell d-skel">
                    <div id="chartAge"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════ TABLE ════════════════════════════════════ --}}
    <div class="d-table-wrap">
        <div class="d-table-head">
            <div>
                <p class="d-card-title">Dernières inscriptions</p>
                <p class="d-card-sub">8 candidats les plus récents</p>
            </div>
            <a href="{{ route('candidat.index') }}" class="d-table-link">
                Voir tout <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div style="overflow-x:auto">
            <table class="d-table">
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Ville</th>
                        <th>Niveau</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="recentBody">
                    @for($i = 0; $i < 5; $i++)
                        <tr>
                        <td><span class="d-skel" style="display:inline-block;width:150px;height:13px;border-radius:4px"></span></td>
                        <td><span class="d-skel" style="display:inline-block;width:80px;height:13px;border-radius:4px"></span></td>
                        <td><span class="d-skel" style="display:inline-block;width:90px;height:13px;border-radius:4px"></span></td>
                        <td><span class="d-skel" style="display:inline-block;width:70px;height:13px;border-radius:4px"></span></td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
    </div>

</div>{{-- /dash-wrap --}}

{{-- ══════════ BOTTOM NAV MOBILE ═══════════════════════════ --}}
<nav class="d-bnav" id="dashBnav">
    <div class="d-bnav-inner">
        <a href="{{ route('dashboard.index') }}" class="d-bnav-item active">
            <i class="ri-home-5-line"></i><span>Accueil</span>
        </a>
        <a href="{{ route('candidat.index') }}" class="d-bnav-item">
            <i class="ri-team-line"></i><span>Candidats</span>
        </a>
        <a href="#" class="d-bnav-item">
            <i class="ri-bar-chart-2-line"></i><span>Stats</span>
        </a>
        @if(Auth::user()->role === 'superadmin' || Auth::user()->role === 'developpeur')
        <a href="{{ route('parametre.index') }}" class="d-bnav-item">
            <i class="ri-settings-3-line"></i><span>Réglages</span>
        </a>
        @endif
    </div>
</nav>
@endsection


@section('script')
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    /* ══════════════════════════════════════════════════════════════
   DASHBOARD — IIFE isolé, zéro pollution globale
   Dark mode → .dash-dark sur #dashWrap, jamais sur <html>
   Velzon garde data-theme intact
══════════════════════════════════════════════════════════════ */
    (function() {

        const wrap = document.getElementById('dashWrap');
        const DARK_KEY = 'dash-dark-v2';

        /* ── Dark mode ─────────────────────────────────────────── */
        function applyDark(on) {
            wrap.classList.toggle('dash-dark', on);
            const icon = document.getElementById('darkIcon');
            const label = document.getElementById('darkLabel');
            if (icon) icon.className = on ? 'ri-sun-line' : 'ri-moon-line';
            if (label) label.textContent = on ? 'Clair' : 'Sombre';
            if (window._dReady) {
                destroyCharts();
                window._dReady = false;
                loadAll();
            }
        }

        window.dashToggleDark = function() {
            const next = !wrap.classList.contains('dash-dark');
            localStorage.setItem(DARK_KEY, next ? '1' : '0');
            applyDark(next);
        };

        applyDark(localStorage.getItem(DARK_KEY) === '1'); // restaurer

        /* ── Horloge ───────────────────────────────────────────── */
        function tick() {
            const now = new Date();
            const d = document.getElementById('heroDate');
            const t = document.getElementById('heroTime');
            if (d) d.textContent = now.toLocaleDateString('fr-FR', {
                weekday: 'short',
                day: 'numeric',
                month: 'long'
            });
            if (t) t.textContent = now.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        setInterval(tick, 1000);
        tick();

        /* ── Couleurs selon thème ──────────────────────────────── */
        function pal() {
            const dk = wrap.classList.contains('dash-dark');
            return {
                text: dk ? '#8892b8' : '#4a5280',
                grid: dk ? '#222d42' : '#eaecfb',
                mode: dk ? 'dark' : 'light',
                series: ['#4361ee', '#7209b7', '#f72585', '#4cc9f0', '#06d6a0', '#ffd166'],
            };
        }

        /* ── Skeleton removal ──────────────────────────────────── */
        function clearSkel(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('d-skel', 'd-chart-shell');
                el.removeAttribute('style');
            }
        }

        /* ── Charts ────────────────────────────────────────────── */
        let cI, cC, cE, cA;
        window._dReady = false;

        function destroyCharts() {
            [cI, cC, cE, cA].forEach(c => c?.destroy());
            cI = cC = cE = cA = null;
        }

        function base(extra) {
            const p = pal();
            return {
                chart: {
                    background: 'transparent',
                    toolbar: {
                        show: false
                    },
                    fontFamily: "'Plus Jakarta Sans',sans-serif",
                    animations: {
                        enabled: true,
                        speed: 420
                    },
                },
                theme: {
                    mode: p.mode
                },
                colors: p.series,
                grid: {
                    borderColor: p.grid,
                    strokeDashArray: 4
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: p.text,
                            fontSize: '11px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: p.text,
                            fontSize: '11px'
                        }
                    }
                },
                tooltip: {
                    theme: p.mode
                },
                ...extra,
            };
        }

        function buildCharts(data) {
            const p = pal();

            /* Inscriptions — area */
            clearSkel('wrapInsc');
            cI = new ApexCharts(document.getElementById('chartInsc'), base({
                chart: {
                    type: 'area',
                    height: 275
                },
                series: [{
                    name: 'Inscriptions',
                    data: data.daily_registrations.series
                }],
                xaxis: {
                    categories: data.daily_registrations.labels,
                    labels: {
                        style: {
                            colors: p.text,
                            fontSize: '11px'
                        },
                        rotate: -30
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: .35,
                        opacityTo: .02
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0
                },
            }));
            cI.render();

            /* Villes — donut */
            clearSkel('wrapCity');
            cC = new ApexCharts(document.getElementById('chartCity'), base({
                chart: {
                    type: 'donut',
                    height: 275
                },
                series: data.city_distribution.series,
                labels: data.city_distribution.labels,
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '12px',
                                    color: p.text
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                },
                dataLabels: {
                    enabled: false
                },
            }));
            cC.render();

            /* Éducation — bar distribué */
            clearSkel('wrapEdu');
            cE = new ApexCharts(document.getElementById('chartEdu'), base({
                chart: {
                    type: 'bar',
                    height: 275
                },
                series: [{
                    name: 'Candidats',
                    data: data.education_levels.series
                }],
                xaxis: {
                    categories: data.education_levels.labels,
                    labels: {
                        style: {
                            colors: p.text,
                            fontSize: '11px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: '55%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
            }));
            cE.render();

            /* Âges — bar */
            clearSkel('wrapAge');
            cA = new ApexCharts(document.getElementById('chartAge'), base({
                chart: {
                    type: 'bar',
                    height: 275
                },
                series: [{
                    name: 'Candidats',
                    data: data.age_distribution.series
                }],
                xaxis: {
                    categories: data.age_distribution.labels,
                    labels: {
                        style: {
                            colors: p.text,
                            fontSize: '11px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                colors: ['#7209b7'],
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: '55%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
            }));
            cA.render();

            window._dReady = true;
        }

        /* ── Table ──────────────────────────────────────────────── */
        function renderTable(rows) {
            const tbody = document.getElementById('recentBody');
            if (!tbody) return;
            if (!rows?.length) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:30px;color:var(--d-text3)">Aucune inscription récente</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => {
                const init = ((r.prenom?.[0] || '') + (r.nom?.[0] || '')).toUpperCase();
                const date = new Date(r.created_at).toLocaleDateString('fr-FR', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                return `<tr>
        <td><div style="display:flex;align-items:center">
          <span class="d-avatar">${init}</span>
          <span style="font-weight:600;color:var(--d-text)">${r.prenom||''} ${r.nom||''}</span>
        </div></td>
        <td style="color:var(--d-text2)">
          <i class="ri-map-pin-line" style="color:#4cc9f0;margin-right:4px;font-size:12px"></i>${r.ville||'—'}
        </td>
        <td><span class="d-niveau">${r.niveau_etudes||'—'}</span></td>
        <td style="color:var(--d-text3);font-size:12px">${date}</td>
      </tr>`;
            }).join('');
        }

        /* ── Fetch ──────────────────────────────────────────────── */
        async function loadAll(days) {
            days = days || document.getElementById('periodeInscriptions')?.value || 30;

            // Token CSRF obligatoire pour Laravel (même en GET sur les routes web)
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const res = await fetch(`/admin/stats/all?days=${days}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrf ? {
                            'X-CSRF-TOKEN': csrf
                        } : {}),
                    },
                    credentials: 'same-origin', // envoie les cookies de session Laravel
                });

                // Erreur 401 → pas authentifié (route dans api.php au lieu de web.php)
                // Erreur 404 → route non déclarée
                // Erreur 500 → exception PHP côté serveur
                if (!res.ok) {
                    const msg = res.status === 401 ?
                        'Non authentifié — vérifiez le middleware admin sur /admin/stats/all' :
                        res.status === 404 ?
                        'Route /admin/stats/all introuvable — vérifiez routes/web.php' :
                        `Erreur serveur HTTP ${res.status}`;
                    showFetchError(msg);
                    return;
                }

                const data = await res.json();

                if (!window._dReady) {
                    buildCharts(data);
                } else {
                    cI?.updateOptions({
                        xaxis: {
                            categories: data.daily_registrations.labels
                        }
                    });
                    cI?.updateSeries([{
                        name: 'Inscriptions',
                        data: data.daily_registrations.series
                    }]);
                }

                renderTable(data.recent_activity);

                const lc = document.getElementById('liveCount');
                if (lc && data.kpis?.total)
                    lc.textContent = `${Number(data.kpis.total).toLocaleString('fr-FR')} candidats`;

            } catch (e) {
                console.error('[Dashboard]', e.message);
                showFetchError('Impossible de contacter le serveur : ' + e.message);
            }
        }

        /* ── Affichage d'erreur inline ─────────────────────────── */
        function showFetchError(msg) {
            // Remplace les skeletons par un message d'erreur lisible
            ['wrapInsc', 'wrapCity', 'wrapEdu', 'wrapAge'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.remove('d-skel', 'd-chart-shell');
                el.style.cssText = '';
                // Ne pas écraser si le graphique est déjà rendu
                if (!window._dReady) {
                    el.innerHTML = `<div style="display:flex;align-items:center;justify-content:center;
          height:180px;color:var(--d-text3);font-size:13px;gap:8px;flex-direction:column">
          <i class="ri-error-warning-line" style="font-size:28px;color:#f72585"></i>
          <span>${msg}</span>
        </div>`;
                }
            });
            console.error('[Dashboard API]', msg);
        }

        /* ── Init ───────────────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            loadAll();
            document.getElementById('periodeInscriptions')
                ?.addEventListener('change', function() {
                    loadAll(this.value);
                });
            setInterval(loadAll, 5 * 60 * 1000);
        });

    })();
</script>
@endsection