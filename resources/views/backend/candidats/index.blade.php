@extends('backend.layouts.master')
@section('title')
Candidats
@endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
    .filter-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ebec;
        border-left: 4px solid #405189;
    }
</style>
@endsection

@section('content')
@component('backend.components.breadcrumb')
@slot('li_1') Liste @endslot
@slot('title') Candidats @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card filter-card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-bold text-uppercase fs-11">Âge Min</label>
                        <input type="number" id="min_age" class="form-control form-control-sm" placeholder="Ex: 18">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold text-uppercase fs-11">Âge Max</label>
                        <input type="number" id="max_age" class="form-control form-control-sm" placeholder="Ex: 35">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-uppercase fs-11">Niveau d'études</label>
                        <select id="select_diplome" class="form-select form-select-sm">
                            <option value="">Tous les niveaux</option>
                            @foreach($candidats->pluck('niveau_etudes')->unique() as $niveau)
                            <option value="{{ $niveau }}">{{ $niveau }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-uppercase fs-11">Ville</label>
                        <select id="select_ville" class="form-select form-select-sm">
                            <option value="">Toutes les villes</option>
                            @foreach($candidats->pluck('ville')->unique() as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="reset_filters" class="btn btn-sm btn-soft-danger w-100">
                            <i class="ri-refresh-line align-bottom me-1"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Liste des préinscriptions
                    <span class="badge bg-primary ms-2">{{ $candidats->count() }}</span>
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom complet</th>
                                <th>Date de naissance</th>
                                <th>Âge</th>
                                <th>Lieu de naissance</th>
                                <th>Téléphone</th>
                                <th>Ville</th>
                                <th>Diplôme</th>
                                <th>Langues</th>
                                <th>Contact urgence</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidats as $key => $item)
                            <tr id="row_{{ $item->id }}">
                                <td>{{ ++$key }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                {{ strtoupper(substr($item->nom, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong class="text-primary">{{ strtoupper($item->nom) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->prenom }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->date_naissance)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-soft-info text-info">{{ $item->age }} ans</span>
                                </td>
                                <td>{{ $item->lieu_naissance }}</td>
                                <td>{{ $item->telephone }}</td>
                                <td>{{ $item->ville }}</td>
                                <td><span class="badge bg-soft-secondary text-secondary">{{ $item->niveau_etudes }}</span></td>
                                <td>
                                    @if(is_array($item->langues))
                                    @foreach($item->langues as $langue)
                                    <span class="badge bg-soft-success text-success me-1">{{ $langue }}</span>
                                    @endforeach
                                    @endif
                                </td>
                                <td>{{ $item->urgence_nom }} ({{ $item->urgence_tel }})</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}">
                                                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i> Voir détail
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item text-danger delete" data-id="{{ $item->id }}">
                                                    <i class="ri-delete-bin-fill align-bottom me-2"></i> Supprimer
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Detail Simplifié pour l'exemple --}}
                            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Détails de {{ $item->nom }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Niveau :</strong> {{ $item->niveau_etudes }}</p>
                                            <p><strong>Ville :</strong> {{ $item->ville }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialisation DataTable
        var table = $('#buttons-datatables').DataTable({
            "order": [
                [0, "asc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            }
        });

        // Filtre Custom : Âge (Colonne index 3)
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = parseInt($('#min_age').val(), 10);
                var max = parseInt($('#max_age').val(), 10);
                var age = parseFloat(data[3]) || 0;

                if ((isNaN(min) && isNaN(max)) ||
                    (isNaN(min) && age <= max) ||
                    (min <= age && isNaN(max)) ||
                    (min <= age && age <= max)) {
                    return true;
                }
                return false;
            }
        );

        // Déclencheur Âge
        $('#min_age, #max_age').on('keyup change', function() {
            table.draw();
        });

        // Déclencheur Diplôme (Colonne Index 7)
        $('#select_diplome').on('change', function() {
            table.column(7).search(this.value).draw();
        });

        // Déclencheur Ville (Colonne Index 6)
        $('#select_ville').on('change', function() {
            table.column(6).search(this.value).draw();
        });

        // Réinitialisation
        $('#reset_filters').on('click', function() {
            $('#min_age, #max_age, #select_diplome, #select_ville').val('');
            table.search('').column(6).search('').column(7).search('').draw();
        });

        // Suppression AJAX
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            if (confirm("Supprimer ce candidat ?")) {
                $.ajax({
                    url: '/candidat/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#row_' + id).fadeOut();
                    }
                });
            }
        });
    });
</script>
@endsection