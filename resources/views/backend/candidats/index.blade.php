@extends('backend.layouts.master')
@section('title')
Candidats
@endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('backend.components.breadcrumb')
@slot('li_1') Liste @endslot
@slot('title') Candidats @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
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
                                <th>Niveau d'études</th>
                                <th>Langues</th>
                                <th>Contact d'urgence</th>
                                <th>Date d'inscription</th>
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
                                            <span class="text-primary">
                                                {{ strtoupper(substr($item->nom, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ strtoupper($item->nom) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->prenom }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ \Carbon\Carbon::parse($item->date_naissance)->format('d/m/Y') }}</td>

                                <td>
                                    <span class="badge bg-soft-info text-info">
                                        {{ $item->age }} ans
                                    </span>
                                </td>

                                <td>{{ $item->lieu_naissance }}</td>

                                <td>{{ $item->telephone }}</td>

                                <td>{{ $item->ville }}</td>

                                <td>
                                    <span class="badge bg-soft-secondary text-secondary">
                                        {{ $item->niveau_etudes }}
                                    </span>
                                </td>

                                <td>
                                    @if(is_array($item->langues))
                                    @foreach($item->langues as $langue)
                                    <span class="badge bg-soft-success text-success me-1">
                                        {{ $langue }}
                                        @php
                                        $niveauKey = 'niveau_' . strtolower(substr($langue, 0, 2));
                                        @endphp
                                        @if($item->$niveauKey)
                                        ({{ $item->$niveauKey }})
                                        @endif
                                    </span>
                                    @endforeach
                                    @endif
                                </td>

                                <td>
                                    <strong>{{ $item->urgence_nom }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $item->urgence_tel }}</small>
                                </td>

                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>

                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetail{{ $item->id }}">
                                                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                    Voir détail
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="dropdown-item remove-item-btn delete"
                                                    data-id="{{ $item->id }}">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                    Supprimer
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal détail candidat --}}
                            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Fiche candidat — {{ strtoupper($item->nom) }} {{ $item->prenom }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Nom complet</p>
                                                    <p>{{ strtoupper($item->nom) }} {{ $item->prenom }}</p>
                                                </div>

                                                <div class="col-md-3">
                                                    <p class="text-muted mb-1 fw-semibold">Date de naissance</p>
                                                    <p>{{ \Carbon\Carbon::parse($item->date_naissance)->format('d/m/Y') }}</p>
                                                </div>

                                                <div class="col-md-3">
                                                    <p class="text-muted mb-1 fw-semibold">Âge</p>
                                                    <p>{{ $item->age }} ans</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Lieu de naissance</p>
                                                    <p>{{ $item->lieu_naissance }}</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Téléphone</p>
                                                    <p>{{ $item->telephone }}</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Ville d'inscription</p>
                                                    <p>{{ $item->ville }}</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Niveau d'études</p>
                                                    <p>{{ $item->niveau_etudes }}</p>
                                                </div>

                                                <div class="col-12">
                                                    <p class="text-muted mb-1 fw-semibold">Langues</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @if(is_array($item->langues))
                                                        @foreach($item->langues as $langue)
                                                        @php $niveauKey = 'niveau_' . strtolower(substr($langue, 0, 2)); @endphp
                                                        <span class="badge bg-soft-success text-success fs-12">
                                                            {{ $langue }}
                                                            @if($item->$niveauKey) — {{ $item->$niveauKey }} @endif
                                                        </span>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Contact d'urgence</p>
                                                    <p>{{ $item->urgence_nom }}</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Téléphone d'urgence</p>
                                                    <p>{{ $item->urgence_tel }}</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-1 fw-semibold">Inscrit le</p>
                                                    <p>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y à H:i') }}</p>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    window.routeName = "candidat";

    // Suppression AJAX
    $(document).on('click', '.delete', function() {
        const id = $(this).data('id');
        const row = $('#row_' + id);

        if (!confirm('Confirmer la suppression de ce candidat ?')) return;

        $.ajax({
            url: '/' + window.routeName + '/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                row.fadeOut(400, function() {
                    $(this).remove();
                });
            },
            error: function() {
                alert('Erreur lors de la suppression.');
            }
        });
    });
</script>
@endsection