@extends('backend.layouts.master')
@section('title') Tableau de bord @endsection

@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* Custom Modern UI */
    .dashboard-header {
        background: linear-gradient(to right, #405189, #0ab39c);
        border-radius: 15px;
        padding: 25px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        transition: transform 0.3s ease;
        border: none;
        border-radius: 12px;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: white !important;
        backdrop-filter: blur(5px);
    }

    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="h-100">
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                @auth
                <h4 class="text-white mb-1">Félicitations, {{ Auth::user()->username }} ! 🎉</h4>
                @endauth
                <p class="text-white-50 mb-0">Votre plateforme de recrutement a enregistré de nouveaux profils aujourd'hui.</p>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end gap-2">
                    <div class="input-group input-group-sm w-auto">
                        <span class="input-group-text glass-input border-0"><i class="ri-calendar-line"></i></span>
                        <input type="text" id="date" class="form-control glass-input border-0" style="min-width: 180px;" readonly>
                    </div>
                    <div class="input-group input-group-sm w-auto">
                        <span class="input-group-text glass-input border-0"><i class="ri-time-line"></i></span>
                        <input type="text" id="horloge" class="form-control glass-input border-0" style="width: 80px;" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @php
        $cards = [
        ['title' => 'Candidats', 'val' => $stats['total'], 'icon' => 'ri-team-line', 'color' => 'primary', 'route' => 'candidat.index'],
        ['title' => 'Villes', 'val' => $stats['villes'], 'icon' => 'ri-map-pin-2-line', 'color' => 'info', 'route' => 'candidat.index'],
        ['title' => 'Diplômes', 'val' => $stats['niveaux'], 'icon' => 'ri-graduation-cap-line', 'color' => 'warning', 'route' => 'candidat.index'],
        ['title' => 'Aujourd\'hui', 'val' => $stats['aujourdhui'], 'icon' => 'ri-user-add-line', 'color' => 'success', 'route' => 'candidat.index']
        ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-3">{{ $card['title'] }}</p>
                            <h4 class="fs-22 fw-bold mb-0">{{ $card['val'] }}</h4>
                        </div>
                        <div class="icon-shape bg-soft-{{ $card['color'] }} text-{{ $card['color'] }}">
                            <i class="{{ $card['icon'] }} fs-24"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <!-- <a href="{{ $card['route'] }}" class="link-{{ $card['color'] }} fw-medium fs-12">
                            Détails <i class="ri-arrow-right-line align-middle"></i>
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex border-0 bg-transparent">
                    <h4 class="card-title mb-0 flex-grow-1">Flux des Inscriptions</h4>
                </div>
                <div class="card-body">
                    <div id="inscription_chart" class="apex-charts"></div>
                </div>
            </div>
        </div> -->
        <!-- <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex border-0 bg-transparent">
                    <h4 class="card-title mb-0 flex-grow-1">Répartition par Ville</h4>
                </div>
                <div class="card-body">
                    <div id="ville_pie_chart" class="apex-charts"></div>
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // Horloge
    function updateTime() {
        const now = new Date();
        document.getElementById('horloge').value = now.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('date').value = now.toLocaleDateString('fr-FR', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Chart Inscriptions (Exemple dynamique)
    var options = {
        series: [{
            name: 'Inscriptions',
            data: [10, 22, 15, 30, 25, 40, 35]
        }],
        chart: {
            height: 320,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#405189'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0.1
            }
        },
        xaxis: {
            categories: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']
        }
    };
    new ApexCharts(document.querySelector("#inscription_chart"), options).render();

    // Chart Villes
    // var pieOptions = {
    //     series: [44, 33, 20],
    //     labels: ['Abidjan', 'Bouaké', 'San-Pédro'],
    //     chart: {
    //         type: 'donut',
    //         height: 300
    //     },
    //     legend: {
    //         position: 'bottom'
    //     },
    //     colors: ['#405189', '#0ab39c', '#f7b84b']
    // };
    new ApexCharts(document.querySelector("#ville_pie_chart"), pieOptions).render();
</script>
@endsection