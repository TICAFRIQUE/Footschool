<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <style>
        /*
         * CORRECTION VISIBILITÉ SIDEBAR
         * data-sidebar="dark" → fond bleu sombre #3d4f7c ~ #2d3a5e
         * Les textes doivent être clairs (blanc/gris clair), pas sombres.
         * On surcharge les classes Velzon sans toucher au reste de la page.
         */

        /* Liens principaux — blanc semi-transparent */
        .app-menu .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.72) !important;
            font-weight: 500;
            transition: color .2s, background .2s;
        }

        /* Hover & actif — blanc plein + fond léger */
        .app-menu .navbar-nav .nav-link:hover,
        .app-menu .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 6px;
        }

        /* Sous-menu (collapse) */
        .app-menu .navbar-nav .menu-dropdown .nav-link {
            color: rgba(255, 255, 255, 0.60) !important;
            font-size: 13px;
        }

        .app-menu .navbar-nav .menu-dropdown .nav-link:hover,
        .app-menu .navbar-nav .menu-dropdown .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
            border-radius: 5px;
        }

        /* Icônes — héritent la couleur du lien parent */
        .app-menu .navbar-nav .nav-link i {
            color: inherit !important;
        }

        /* Titre de section (si présent) */
        .app-menu .menu-title {
            color: rgba(255, 255, 255, 0.35) !important;
            font-size: 10px;
            letter-spacing: 1px;
        }
    </style>

    <!-- LOGO -->
    <div class="navbar-brand-box">
        @if ($data_parametre != null)
        <a href="#" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ $data_parametre ? URL::asset($data_parametre?->getFirstMediaUrl('logo_header')) : URL::asset('images/camera-icon.png') }}"
                    alt="logo" width="auto" class="rounded-circle" height="60">
            </span>
        </a>
        @endif

        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>

            <ul class="navbar-nav" id="navbar-nav">

                @can('voir-tableau de bord')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('dashboard.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.index') }}">
                        <i class="ri-dashboard-2-line"></i>
                        <span>TABLEAU DE BORD</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('candidat.*') ? 'active' : '' }}"
                        href="{{ route('candidat.index') }}">
                        <i class="ri-user-2-line"></i>
                        <span>CANDIDATS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('inscriptions.*') ? 'active' : '' }}"
                        href="{{ route('inscriptions.index') }}">
                        <i class="ri-file-list-3-line"></i>
                        <span>INSCRIPTIONS</span>
                    </a>
                </li>
                @php
                    $nbPaiementsAttente = \App\Models\Candidat::where('statut', 'en_attente_paiement')->count();
                @endphp
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('paiements.*') ? 'active' : '' }}"
                        href="{{ route('paiements.index') }}">
                        <i class="ri-wallet-3-line"></i>
                        <span>PAIEMENTS EN ATTENTE</span>
                        @if ($nbPaiementsAttente > 0)
                            <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $nbPaiementsAttente }}</span>
                        @endif
                    </a>
                </li>
                @endcan

                @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'developpeur' || Auth::user()->can('voir-parametre'))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-settings-2-fill me-2"></i>
                        <span class="text-uppercase">Paramètres</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('role.*') || Route::is('parametre.*') || Route::is('module.*') || Route::is('permission.*') || Route::is('admin-register.*') ? 'show' : '' }}"
                        id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('parametre.index') }}"
                                    class="nav-link {{ Route::is('parametre.*') ? 'active' : '' }}">
                                    <i class="ri-information-line me-2"></i> Informations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin-register.index') }}"
                                    class="nav-link {{ Route::is('admin-register.*') ? 'active' : '' }}">
                                    <i class="ri-user-settings-line me-2"></i> Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('module.index') }}"
                                    class="nav-link {{ Route::is('module.*') ? 'active' : '' }}">
                                    <i class="ri-apps-2-line me-2"></i> Modules
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('role.index') }}"
                                    class="nav-link {{ Route::is('role.*') ? 'active' : '' }}">
                                    <i class="ri-user-star-line me-2"></i> Rôles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permission.index') }}"
                                    class="nav-link {{ Route::is('permission.*') ? 'active' : '' }}">
                                    <i class="ri-key-2-line me-2"></i> Permissions / Rôles
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay -->
<div class="vertical-overlay"></div>