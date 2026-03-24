@yield('css')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<!-- include summernote css/js -->

<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('build/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
{{-- @yield('css') --}}
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

<style>
/* Styles personnalisés pour la sidebar mobile */
@media (max-width: 767px) {
    /* Cacher la sidebar par défaut sur mobile */
    .app-menu {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    /* Afficher la sidebar quand vertical-sidebar-enable est présent */
    .vertical-sidebar-enable .app-menu {
        transform: translateX(0);
    }

    /* Overlay pour fermer la sidebar */
    .vertical-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .vertical-sidebar-enable .vertical-overlay {
        opacity: 1;
        visibility: visible;
    }

    /* S'assurer que le contenu principal prend toute la largeur sur mobile */
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Ajuster le topbar pour mobile */
    .navbar-header {
        justify-content: space-between !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // S'assurer que le bouton hamburger fonctionne sur mobile
    const hamburgerBtn = document.getElementById('topnav-hamburger-icon');
    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function() {
            const windowSize = window.innerWidth;
            const body = document.body;

            if (windowSize <= 767) {
                // Toggle de la sidebar mobile
                if (body.classList.contains('vertical-sidebar-enable')) {
                    body.classList.remove('vertical-sidebar-enable');
                } else {
                    body.classList.add('vertical-sidebar-enable');
                }
            }
        });
    }

    // Fermer la sidebar mobile en cliquant sur l'overlay
    const overlay = document.querySelector('.vertical-overlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            document.body.classList.remove('vertical-sidebar-enable');
        });
    }

    // Fermer la sidebar mobile en appuyant sur Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.body.classList.contains('vertical-sidebar-enable')) {
            document.body.classList.remove('vertical-sidebar-enable');
        }
    });
});
</script>


