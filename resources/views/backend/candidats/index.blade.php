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
    .filter-card { border-top: 3px solid #405189; }
    .modal-header-gradient { background: linear-gradient(to right, #405189, #0ab39c); color: white; }
    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before { background-color: #405189 !important; }
    /* Le menu d'actions doit toujours passer au-dessus de la sidebar, même sur un tableau large/scrollable */
    .table-card .dropdown-menu { z-index: 1055; }
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
                        <label class="form-label text-muted small fw-bold">PRÉINSCRIPTION DU</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">AU</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>

                    <div class="col-sm-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">VILLE</label>
                        <select id="select_ville" class="form-select">
                            <option value="">Toutes les villes</option>
                            @foreach($candidats->pluck('ville')->unique()->filter() as $ville)
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
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">PIED FORT</label>
                        <select id="select_pied" class="form-select">
                            <option value="">Tous</option>
                            <option value="gauche">Gauche</option>
                            <option value="droit">Droit</option>
                            <option value="les deux">Les deux</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-1">
                        <label class="form-label text-muted small fw-bold">POSTE</label>
                        <select id="select_poste" class="form-select">
                            <option value="">Tous</option>
                            @for($i = 1; $i <= 11; $i++)
                            <option value="{{ $i }}">#{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-1">
                        <label class="form-label text-muted small fw-bold">STATUT</label>
                        <select id="select_statut" class="form-select">
                            <option value="">Tous</option>
                            <option value="Préinscrit">Préinscrit</option>
                            <option value="En attente paiement">En attente</option>
                            <option value="Inscrit">Inscrit</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid d-md-flex align-items-end">
                        <button id="reset_filters" class="btn btn-soft-secondary w-100">
                            <i class="ri-refresh-line align-bottom me-1"></i>reset
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
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Âge</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Pied Fort</th>
                            <th scope="col">Poste</th>
                            <th scope="col">Diplôme</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Préinscription</th>
                            <th scope="col" class="text-end">Actions</th>
                            {{-- Colonnes cachées : présentes uniquement pour l'export complet --}}
                            <th scope="col">Date de naissance</th>
                            <th scope="col">Lieu de naissance</th>
                            <th scope="col">Langues</th>
                            <th scope="col">Niveau Français</th>
                            <th scope="col">Niveau Anglais</th>
                            <th scope="col">Niveau Espagnol</th>
                            <th scope="col">Contact urgence</th>
                            <th scope="col">N° Dossier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidats as $key => $item)
                        @php
                            $statutMap = [
                                'preinscrit' => ['Préinscrit', 'secondary'],
                                'en_attente_paiement' => ['En attente paiement', 'warning'],
                                'inscrit' => ['Inscrit', 'success'],
                            ];
                            [$statutLabel, $statutColor] = $statutMap[$item->statut] ?? ['Préinscrit', 'secondary'];
                        @endphp
                        <tr id="row_{{ $item->id }}">
                            <td><span class="text-muted fw-bold">{{ ++$key }}</span></td>
                            <td class="fw-semibold text-uppercase">{{ $item->nom }}</td>
                            <td class="fw-medium">{{ $item->prenom }}</td>
                            <td><i class="ri-phone-fill text-muted me-1"></i>{{ $item->telephone }}</td>
                            <td><span class="badge bg-info-subtle text-info fs-12 px-2">{{ $item->age }} ans</span></td>
                            <td><i class="ri-map-pin-line text-muted me-1"></i>{{ $item->ville }}</td>
                            <td>
                                @if($item->pieds_fort)
                                    <span class="badge bg-primary-subtle text-primary fs-12 px-2">{{ ucfirst($item->pieds_fort) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->numero_poste)
                                    <span class="badge bg-warning-subtle text-warning fs-12 px-2 fw-bold">#{{ $item->numero_poste }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="fw-medium text-uppercase small">{{ $item->niveau_etudes }}</td>
                            <td>
                                <span class="badge bg-{{ $statutColor }}-subtle text-{{ $statutColor }} fs-12 px-2">{{ $statutLabel }}</span>
                            </td>
                            <td class="text-muted small" data-order="{{ $item->created_at->format('Y-m-d') }}">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown" data-bs-strategy="fixed">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}">
                                                <i class="ri-eye-fill me-2 align-bottom text-muted"></i> Fiche Profil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('candidat.fiche', $item->id) }}" target="_blank">
                                                <i class="ri-printer-fill me-2 align-bottom text-muted"></i> Imprimer la fiche
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
                            {{-- Colonnes cachées (export) --}}
                            <td>{{ \Carbon\Carbon::parse($item->date_naissance)->format('d/m/Y') }}</td>
                            <td>{{ $item->lieu_naissance }}</td>
                            <td>{{ $item->langues ? implode(', ', $item->langues) : '-' }}</td>
                            <td>{{ $item->niveau_fr ?? '-' }}</td>
                            <td>{{ $item->niveau_en ?? '-' }}</td>
                            <td>{{ $item->niveau_es ?? '-' }}</td>
                            <td>{{ $item->urgence_nom }} ({{ $item->urgence_tel }})</td>
                            <td>{{ $item->numero_dossier ?? '-' }}</td>
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
                <div class="row g-4 mt-3">
                    <div class="col-md-6 border-top pt-4">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">⚽ Caractéristiques Footballistiques</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1 small">Pied Fort</p>
                                <p class="fw-bold">
                                    @if($item->pieds_fort)
                                        <span class="badge bg-primary-subtle text-primary">{{ ucfirst($item->pieds_fort) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1 small">Numéro de Poste</p>
                                <p class="fw-bold">
                                    @if($item->numero_poste)
                                        <span class="badge bg-warning-subtle text-warning fs-13 fw-bold">#{{ $item->numero_poste }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 border-top border-start pt-4 ps-md-4">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">📞 Contact d'Urgence</h6>
                        <div class="mb-3">
                            <p class="text-muted mb-1 small">Nom &amp; Prénoms</p>
                            <p class="fw-medium">{{ $item->urgence_nom }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted mb-1 small">Téléphone</p>
                            <p class="fw-medium"><i class="ri-phone-fill me-1"></i>{{ $item->urgence_tel }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-ghost-dark" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('candidat.fiche', $item->id) }}" target="_blank" class="btn btn-outline-secondary shadow-sm px-3">
                    <i class="ri-printer-line me-1 align-bottom"></i> Fiche PDF
                </a>
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
        // Colonnes cachées (12 à 19) : uniquement pour l'export complet, jamais affichées à l'écran
        var HIDDEN_COLS = [12, 13, 14, 15, 16, 17, 18, 19];
        var EXPORT_COLS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10].concat(HIDDEN_COLS);

        var table = $('#candidats-datatable').DataTable({
            responsive: true,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json" },
            columnDefs: [
                { targets: HIDDEN_COLS, visible: false },
                // La colonne Actions (et #, Nom, Téléphone) ne doit jamais être repliée dans la
                // ligne responsive : sinon son bouton se retrouve aligné à gauche, collé à la
                // sidebar, et son menu "dropdown-menu-end" s'ouvre par-dessus elle.
                { targets: 11, responsivePriority: 1 },
                { targets: 0, responsivePriority: 1 },
                { targets: [1, 3], responsivePriority: 2 }
            ],
            dom: '<"row align-items-center"<"col-sm-12 col-md-auto"B><"col-sm-12 col-md"f>>rt<"row align-items-center"<"col-sm-12 col-md"i><"col-sm-12 col-md-auto"p>>',
            buttons: [
                { extend: 'excelHtml5', text: '<i class="ri-file-excel-line"></i> Excel', className: 'btn btn-success btn-export', exportOptions: { columns: EXPORT_COLS } },
                { extend: 'pdfHtml5', text: '<i class="ri-file-pdf-line"></i> PDF', className: 'btn btn-danger btn-export', exportOptions: { columns: EXPORT_COLS },
                    customize: function(doc) {
                        doc.styles.tableHeader.fillColor = '#405189';
                        doc.styles.tableHeader.color = 'white';
                        doc.pageOrientation = 'landscape';
                        doc.defaultStyle.fontSize = 7;
                    }
                },
                { extend: 'print', text: '<i class="ri-printer-line"></i> Imprimer', className: 'btn btn-info btn-export', exportOptions: { columns: EXPORT_COLS } }
            ]
        });

        // Fonction de filtrage combinée (Âge + Dates + Pied + Poste + Statut)
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            // Filtre Age (colonne 4)
            var minA = parseInt($('#min_age').val(), 10);
            var maxA = parseInt($('#max_age').val(), 10);
            var age = parseFloat(data[4]) || 0;

            // Filtre Date de préinscription (colonne 10)
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var dateOrder = $(table.row(dataIndex).node()).find('td:eq(10)').attr('data-order');

            // Filtre Pied Fort (colonne 6)
            var piedFilter = $('#select_pied').val();
            var piedCell = $(table.row(dataIndex).node()).find('td:eq(6)').text().toLowerCase();

            // Filtre Poste (colonne 7)
            var posteFilter = $('#select_poste').val();
            var posteCell = $(table.row(dataIndex).node()).find('td:eq(7)').text().trim();

            // Filtre Statut (colonne 9)
            var statutFilter = $('#select_statut').val();
            var statutCell = $(table.row(dataIndex).node()).find('td:eq(9)').text().trim();

            var ageMatch = (isNaN(minA) && isNaN(maxA)) || (isNaN(minA) && age <= maxA) || (minA <= age && isNaN(maxA)) || (minA <= age && age <= maxA);

            var dateMatch = true;
            if (start && (!dateOrder || dateOrder < start)) dateMatch = false;
            if (end && (!dateOrder || dateOrder > end)) dateMatch = false;

            var piedMatch = !piedFilter || piedCell.includes(piedFilter);
            var posteMatch = !posteFilter || posteCell.includes('#' + posteFilter);
            var statutMatch = !statutFilter || statutCell === statutFilter;

            return ageMatch && dateMatch && piedMatch && posteMatch && statutMatch;
        });

        // Listeners
        $('#min_age, #max_age, #start_date, #end_date').on('change keyup', function() { table.draw(); });
        $('#select_ville').on('change', function() { table.column(5).search(this.value).draw(); });
        $('#select_pied, #select_poste, #select_statut').on('change keyup', function() { table.draw(); });

        $('#reset_filters').on('click', function() {
            $('#min_age, #max_age, #start_date, #end_date, #select_ville, #select_pied, #select_poste, #select_statut').val('');
            table.search('').column(5).search('').draw();
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
