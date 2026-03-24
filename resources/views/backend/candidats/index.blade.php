@extends('backend.layouts.master')
@section('title') Candidats @endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />

<style>
    /* On garde tes styles exacts */
    .table-card thead th {
        background-color: #f3f6f9;
        text-transform: uppercase;
        font-size: 11px;
        color: #878a99;
        font-weight: 700;
        border-bottom: 1px solid #e9ebec;
    }
    .table-card tbody td { vertical-align: middle; padding: 0.75rem 0.6rem; }
    .dt-buttons { margin-bottom: 15px; display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-export { border-radius: 4px !important; font-weight: 600; font-size: 12px; }
    .avatar-soft-primary { background-color: rgba(64, 81, 137, 0.1); color: #405189; }
    .filter-card { border-top: 3px solid #405189; }
    .modal-header-gradient { background: linear-gradient(to right, #405189, #0ab39c); color: white; }
    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before { background-color: #405189 !important; }
</style>
@endsection

@section('content')
@component('backend.components.breadcrumb')
@slot('li_1') Administration @endslot
@slot('title') Liste des Candidats @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card filter-card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">INSCRIPTION DU</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">AU</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label text-muted small fw-bold">VILLE</label>
                        <select id="select_ville" class="form-select">
                            <option value="">Toutes les villes</option>
                            @foreach($candidats->pluck('ville')->unique() as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-muted small fw-bold">ÂGE MIN</label>
                        <input type="number" id="min_age" class="form-control" placeholder="17">
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-muted small fw-bold">ÂGE MAX</label>
                        <input type="number" id="max_age" class="form-control" placeholder="22">
                    </div>
                    <div class="col-md-3 d-grid d-md-flex align-items-end">
                        <button id="reset_filters" class="btn btn-soft-secondary w-100">
                            <i class="ri-refresh-line align-bottom me-1"></i> RAZ FILTRES
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <h5 class="card-title mb-0">Base de données Candidats</h5>
            </div>
            <div class="card-body">
                <table id="candidats-datatable" class="table table-card dt-responsive nowrap align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Identité & Contact</th>
                            <th scope="col">Âge</th>
                            <th scope="col">Localisation</th>
                            <th scope="col">Diplôme</th>
                            <th scope="col">Inscription</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidats as $key => $item)
                        <tr id="row_{{ $item->id }}">
                            <td><span class="text-muted fw-bold">{{ ++$key }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-xs">
                                            <div class="avatar-title rounded-circle avatar-soft-primary fw-bold">
                                                {{ strtoupper(substr($item->nom, 0, 1)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fs-14 mb-0 text-dark">{{ strtoupper($item->nom) }} {{ $item->prenom }}</h6>
                                        <p class="text-muted mb-0 small"><i class="ri-phone-fill me-1"></i>{{ $item->telephone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info-subtle text-info fs-12 px-2">{{ $item->age }} ans</span></td>
                            <td><i class="ri-map-pin-line text-muted me-1"></i>{{ $item->ville }}</td>
                            <td class="fw-medium text-uppercase small">{{ $item->niveau_etudes }}</td>
                            <td class="text-muted small" data-order="{{ $item->created_at->format('Y-m-d') }}">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}">
                                                <i class="ri-eye-fill me-2 align-bottom text-muted"></i> Fiche Profil
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete" href="javascript:void(0);" data-id="{{ $item->id }}">
                                                <i class="ri-delete-bin-fill me-2 align-bottom"></i> Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- On conserve tes modals d'origine --}}
@foreach ($candidats as $item)
<div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header modal-header-gradient p-3">
                <h5 class="modal-title text-white">Dossier Candidat : {{ $item->nom }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">Informations Civiles</h6>
                        <div class="mb-3">
                            <p class="text-muted mb-1 small">Nom & Prénoms</p>
                            <p class="fw-bold">{{ strtoupper($item->nom) }} {{ $item->prenom }}</p>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1 small">Date de Naissance</p>
                                <p class="fw-medium">{{ \Carbon\Carbon::parse($item->date_naissance)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1 small">Lieu</p>
                                <p class="fw-medium">{{ $item->lieu_naissance }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">Profil Académique</h6>
                        <div class="mb-3">
                            <p class="text-muted mb-1 small">Niveau d'études</p>
                            <p class="fw-bold text-dark">{{ $item->niveau_etudes }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-ghost-dark" data-bs-dismiss="modal">Fermer</button>
                <a href="tel:{{ $item->telephone }}" class="btn btn-primary shadow-sm px-4">
                    <i class="ri-phone-line me-1 align-bottom"></i> Contacter
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialisation avec tes boutons d'export d'origine
        var table = $('#candidats-datatable').DataTable({
            responsive: true,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json" },
            dom: '<"row align-items-center"<"col-sm-12 col-md-auto"B><"col-sm-12 col-md"f>>rt<"row align-items-center"<"col-sm-12 col-md"i><"col-sm-12 col-md-auto"p>>',
            buttons: [
                { extend: 'excelHtml5', text: '<i class="ri-file-excel-line"></i> Excel', className: 'btn btn-success btn-export', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
                { extend: 'pdfHtml5', text: '<i class="ri-file-pdf-line"></i> PDF', className: 'btn btn-danger btn-export', exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                    customize: function(doc) {
                        doc.styles.tableHeader.fillColor = '#405189';
                        doc.styles.tableHeader.color = 'white';
                        doc.content[1].table.widths = ['5%', '35%', '10%', '15%', '20%', '15%'];
                    }
                },
                { extend: 'print', text: '<i class="ri-printer-line"></i> Imprimer', className: 'btn btn-info btn-export', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } }
            ]
        });

        // Fonction de filtrage combinée (Âge + Dates)
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            // Filtre Age
            var minA = parseInt($('#min_age').val(), 10);
            var maxA = parseInt($('#max_age').val(), 10);
            var age = parseFloat(data[2]) || 0; // Colonne Age

            // Filtre Date d'inscription
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var dateOrder = $(table.row(dataIndex).node()).find('td:eq(5)').attr('data-order');

            // Logique Age
            var ageMatch = (isNaN(minA) && isNaN(maxA)) || (isNaN(minA) && age <= maxA) || (minA <= age && isNaN(maxA)) || (minA <= age && age <= maxA);
            
            // Logique Date
            var dateMatch = true;
            if (start && dateOrder < start) dateMatch = false;
            if (end && dateOrder > end) dateMatch = false;

            return ageMatch && dateMatch;
        });

        // Listeners
        $('#min_age, #max_age, #start_date, #end_date').on('change keyup', function() { table.draw(); });
        $('#select_ville').on('change', function() { table.column(3).search(this.value).draw(); });

        $('#reset_filters').on('click', function() {
            $('#min_age, #max_age, #start_date, #end_date, #select_ville').val('');
            table.search('').column(3).draw();
        });

        // Ajax Delete (conservé)
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            if (confirm("Supprimer ce profil ?")) {
                $.ajax({
                    url: '/candidat/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() { $('#row_' + id).fadeOut(); }
                });
            }
        });
    });
</script>
@endsection