@extends('backend.layouts.master')
@section('title') Inscriptions @endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />

<style>
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
    /* Le menu d'actions doit toujours passer au-dessus de la sidebar, même sur un tableau large/scrollable */
    .table-card .dropdown-menu { z-index: 1055; }
</style>
@endsection

@section('content')
@component('backend.components.breadcrumb')
@slot('li_1') Administration @endslot
@slot('title') Inscriptions @endslot
@endcomponent

{{-- ── KPI ──────────────────────────────────────────────────────── --}}
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Inscrits (payés)</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-user-follow-line fs-20 text-success"></i>
                    </div>
                </div>
                <h4 class="fs-22 fw-semibold mt-3 mb-0">{{ $kpis['total_inscrits'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Montant collecté</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-money-dollar-circle-line fs-20 text-primary"></i>
                    </div>
                </div>
                <h4 class="fs-22 fw-semibold mt-3 mb-0">{{ number_format($kpis['total_collecte'], 0, ',', ' ') }} <small class="fs-13 text-muted">FCFA</small></h4>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Paiements en attente</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-time-line fs-20 text-warning"></i>
                    </div>
                </div>
                <h4 class="fs-22 fw-semibold mt-3 mb-0">{{ $kpis['paiements_attente'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Paiements échoués</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-close-circle-line fs-20 text-danger"></i>
                    </div>
                </div>
                <h4 class="fs-22 fw-semibold mt-3 mb-0">{{ $kpis['paiements_echoues'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card filter-card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">INSCRIT DU</label>
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
                            @foreach($candidats->pluck('ville')->unique()->filter() as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label text-muted small fw-bold">MOYEN DE PAIEMENT</label>
                        <select id="select_moyen" class="form-select">
                            <option value="">Tous</option>
                            @foreach($candidats->pluck('paiements')->flatten()->pluck('moyen')->unique()->filter() as $moyen)
                            <option value="{{ $moyen }}">{{ ucfirst($moyen) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid d-md-flex align-items-end">
                        <button id="reset_filters" class="btn btn-soft-secondary w-100">
                            <i class="ri-refresh-line align-bottom me-1"></i>reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <h5 class="card-title mb-0">Candidats inscrits (préinscription + paiement validé)</h5>
            </div>
            <div class="card-body">
                <table id="inscriptions-datatable" class="table table-card dt-responsive nowrap align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">N° Dossier</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Montant payé</th>
                            <th scope="col">Moyen</th>
                            <th scope="col">Date inscription</th>
                            <th scope="col" class="text-end">Actions</th>
                            {{-- Colonnes cachées : présentes uniquement pour l'export complet --}}
                            <th scope="col">Référence paiement</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Niveau d'études</th>
                            <th scope="col">Contact père</th>
                            <th scope="col">Contact mère</th>
                            <th scope="col">Contact urgence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidats as $key => $item)
                        @php $paiement = $item->paiements->first(); @endphp
                        <tr id="row_{{ $item->id }}">
                            <td><span class="text-muted fw-bold">{{ ++$key }}</span></td>
                            <td class="fw-semibold text-uppercase">{{ $item->nom }}</td>
                            <td class="fw-medium">{{ $item->prenom }}</td>
                            <td><i class="ri-phone-fill text-muted me-1"></i>{{ $item->telephone }}</td>
                            <td class="fw-medium">{{ $item->numero_dossier ?? '-' }}</td>
                            <td><i class="ri-map-pin-line text-muted me-1"></i>{{ $item->ville }}</td>
                            <td class="fw-semibold">{{ $paiement ? number_format($paiement->montant, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                            <td>
                                @if($paiement)
                                    <span class="badge bg-info-subtle text-info fs-12 px-2">{{ ucfirst($paiement->moyen) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-muted small" data-order="{{ $item->inscrit_at?->format('Y-m-d') }}">
                                {{ $item->inscrit_at?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown" data-bs-strategy="fixed">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalInscription{{ $item->id }}">
                                                <i class="ri-eye-fill me-2 align-bottom text-muted"></i> Voir le dossier
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('candidat.fiche', $item->id) }}" target="_blank">
                                                <i class="ri-printer-fill me-2 align-bottom text-muted"></i> Imprimer la fiche
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-inscription" href="javascript:void(0);" data-id="{{ $item->id }}">
                                                <i class="ri-close-circle-fill me-2 align-bottom"></i> Supprimer l'inscription
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            {{-- Colonnes cachées (export) --}}
                            <td>{{ $paiement->reference ?? '-' }}</td>
                            <td>{{ $paiement->transaction_id ?? '-' }}</td>
                            <td>{{ $item->niveau_etudes }}</td>
                            <td>{{ $item->pere_prenom }} {{ $item->pere_nom }} ({{ $item->pere_contact ?? '-' }})</td>
                            <td>{{ $item->mere_prenom }} {{ $item->mere_nom }} ({{ $item->mere_contact ?? '-' }})</td>
                            <td>{{ $item->urgence_nom }} ({{ $item->urgence_tel }})</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── MODALS DÉTAIL ────────────────────────────────────────────── --}}
@foreach ($candidats as $item)
@php $paiement = $item->paiements->first(); @endphp
<div class="modal fade" id="modalInscription{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header modal-header-gradient p-3">
                <h5 class="modal-title text-white">Dossier : {{ $item->nom }} {{ $item->prenom }} — {{ $item->numero_dossier ?? 'N/A' }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">Candidat</h6>
                        <p class="text-muted mb-1 small">Téléphone</p>
                        <p class="fw-bold mb-3">{{ $item->telephone }}</p>
                        <p class="text-muted mb-1 small">Ville / Niveau</p>
                        <p class="fw-medium mb-3">{{ $item->ville }} — {{ $item->niveau_etudes }}</p>
                        <p class="text-muted mb-1 small">Contact d'urgence</p>
                        <p class="fw-medium">{{ $item->urgence_nom }} ({{ $item->urgence_tel }})</p>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">Parents / Tuteurs</h6>
                        <p class="text-muted mb-1 small">Père</p>
                        <p class="fw-medium mb-3">{{ $item->pere_prenom }} {{ $item->pere_nom }} — {{ $item->pere_contact ?? '-' }}</p>
                        <p class="text-muted mb-1 small">Mère</p>
                        <p class="fw-medium">{{ $item->mere_prenom }} {{ $item->mere_nom }} — {{ $item->mere_contact ?? '-' }}</p>
                    </div>
                </div>
                <div class="row g-4 mt-3">
                    <div class="col-12 border-top pt-4">
                        <h6 class="text-primary text-uppercase fs-11 fw-bold mb-3">💳 Paiement</h6>
                        @if($paiement)
                        <div class="row">
                            <div class="col-md-3 mb-2"><p class="text-muted mb-1 small">Montant</p><p class="fw-bold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p></div>
                            <div class="col-md-3 mb-2"><p class="text-muted mb-1 small">Référence</p><p class="fw-medium">{{ $paiement->reference }}</p></div>
                            <div class="col-md-3 mb-2"><p class="text-muted mb-1 small">Moyen</p><p class="fw-medium">{{ ucfirst($paiement->moyen) }}</p></div>
                            <div class="col-md-3 mb-2"><p class="text-muted mb-1 small">Date</p><p class="fw-medium">{{ $paiement->updated_at->format('d/m/Y H:i') }}</p></div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-paiement mt-2" data-id="{{ $paiement->id }}">
                            <i class="ri-delete-bin-line align-bottom"></i> Supprimer ce paiement
                        </button>
                        @else
                        <p class="text-muted">Aucun paiement réussi enregistré.</p>
                        @endif
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
        // Colonnes cachées (10 à 15) : uniquement pour l'export complet, jamais affichées à l'écran
        var HIDDEN_COLS = [10, 11, 12, 13, 14, 15];
        var EXPORT_COLS = [0, 1, 2, 3, 4, 5, 6, 7, 8].concat(HIDDEN_COLS);

        var table = $('#inscriptions-datatable').DataTable({
            responsive: true,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json" },
            columnDefs: [
                { targets: HIDDEN_COLS, visible: false },
                // La colonne Actions (et #, Nom, Téléphone) ne doit jamais être repliée dans la
                // ligne responsive : sinon son bouton se retrouve aligné à gauche, collé à la
                // sidebar, et son menu "dropdown-menu-end" s'ouvre par-dessus elle.
                { targets: 9, responsivePriority: 1 },
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

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var dateOrder = $(table.row(dataIndex).node()).find('td:eq(8)').attr('data-order');

            var moyenFilter = $('#select_moyen').val();
            var moyenCell = $(table.row(dataIndex).node()).find('td:eq(7)').text().trim().toLowerCase();

            var dateMatch = true;
            if (start && (!dateOrder || dateOrder < start)) dateMatch = false;
            if (end && (!dateOrder || dateOrder > end)) dateMatch = false;

            var moyenMatch = !moyenFilter || moyenCell.includes(moyenFilter.toLowerCase());

            return dateMatch && moyenMatch;
        });

        $('#start_date, #end_date').on('change', function() { table.draw(); });
        $('#select_ville').on('change', function() { table.column(5).search(this.value).draw(); });
        $('#select_moyen').on('change', function() { table.draw(); });

        $('#reset_filters').on('click', function() {
            $('#start_date, #end_date, #select_ville, #select_moyen').val('');
            table.search('').column(5).search('').draw();
        });

        // Supprimer l'inscription : remet le candidat en préinscrit et l'enlève de cette liste
        $(document).on('click', '.delete-inscription', function() {
            var id = $(this).data('id');
            if (confirm("Supprimer cette inscription ? Le candidat repassera en préinscrit (numéro de dossier, infos parents et paiements liés effacés).")) {
                $.ajax({
                    url: '/admin/inscriptions/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() { table.row('#row_' + id).remove().draw(); }
                });
            }
        });

        // Supprimer un paiement précis depuis la modale de détail
        $(document).on('click', '.delete-paiement', function() {
            var id = $(this).data('id');
            if (confirm("Supprimer ce paiement ? Si c'était le dernier paiement validé, le candidat repassera en préinscrit.")) {
                $.ajax({
                    url: '/admin/paiements/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() { location.reload(); }
                });
            }
        });
    });
</script>
@endsection
