@extends('backend.layouts.master')
@section('title') Paiements en attente @endsection

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
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
    .modal-header-gradient { background: linear-gradient(to right, #405189, #0ab39c); color: white; }
</style>
@endsection

@section('content')
@component('backend.components.breadcrumb')
@slot('li_1') Administration @endslot
@slot('title') Paiements en attente @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="ri-information-line fs-18"></i>
            <div>
                Ces candidats ont terminé leur dossier et doivent payer <strong>{{ number_format(config('payment.montant_inscription'), 0, ',', ' ') }} FCFA</strong>
                via Wave, puis envoyer leur preuve par WhatsApp au <strong>{{ implode(' ', str_split(substr(config('payment.whatsapp_number'), -10), 2)) }}</strong>.
                Vérifie le message reçu avant de valider un paiement ci-dessous.
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <h5 class="card-title mb-0">Candidats en attente de vérification ({{ $candidats->count() }})</h5>
            </div>
            <div class="card-body">
                <table id="paiements-datatable" class="table table-card dt-responsive nowrap align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">N° Dossier</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Dossier complété le</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($candidats as $key => $item)
                        <tr id="row_{{ $item->id }}">
                            <td><span class="text-muted fw-bold">{{ ++$key }}</span></td>
                            <td class="fw-semibold text-uppercase">{{ $item->nom }}</td>
                            <td class="fw-medium">{{ $item->prenom }}</td>
                            <td class="fw-medium">{{ $item->numero_dossier ?? '-' }}</td>
                            <td><i class="ri-phone-fill text-muted me-1"></i>{{ $item->telephone }}</td>
                            <td><i class="ri-map-pin-line text-muted me-1"></i>{{ $item->ville }}</td>
                            <td class="text-muted small">{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="https://wa.me/225{{ $item->telephone }}" target="_blank"
                                   class="btn btn-soft-success btn-sm" title="Ouvrir WhatsApp">
                                    <i class="ri-whatsapp-line"></i>
                                </a>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalValider{{ $item->id }}">
                                    <i class="ri-check-line"></i> Marquer payé
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm delete-inscription" data-id="{{ $item->id }}" title="Supprimer">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Aucun paiement en attente de vérification 🎉</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── MODALS DE VALIDATION ─────────────────────────────────────── --}}
@foreach ($candidats as $item)
<div class="modal fade" id="modalValider{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header modal-header-gradient p-3">
                <h5 class="modal-title text-white">Confirmer le paiement de {{ $item->nom }} {{ $item->prenom }} — {{ $item->numero_dossier ?? 'N/A' }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('paiements.valider', $item->id) }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted">
                        Confirme uniquement après avoir vérifié la capture d'écran reçue sur WhatsApp
                        ({{ $item->telephone }}) pour un montant de
                        <strong>{{ number_format(config('payment.montant_inscription'), 0, ',', ' ') }} FCFA</strong>.
                    </p>
                    <label class="form-label text-muted small fw-bold">RÉFÉRENCE WAVE (optionnel)</label>
                    <input type="text" name="reference_wave" class="form-control" placeholder="Ex : TX.240715.XXXX.XXXXX">
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-ghost-dark" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="ri-check-line align-bottom me-1"></i> Confirmer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#paiements-datatable').DataTable({
            responsive: true,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json" },
            columnDefs: [
                { targets: 7, responsivePriority: 1 },
                { targets: 0, responsivePriority: 1 },
                { targets: [1, 3, 4], responsivePriority: 2 }
            ]
        });

        // Supprime le dossier : le candidat repasse en préinscrit et doit tout refaire
        $(document).on('click', '.delete-inscription', function() {
            var id = $(this).data('id');
            if (confirm("Supprimer ce dossier ? Le candidat repassera en préinscrit et devra refaire toute la procédure d'inscription (numéro de dossier, infos parents et paiements liés effacés).")) {
                $.ajax({
                    url: '/admin/inscriptions/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() { table.row('#row_' + id).remove().draw(); }
                });
            }
        });
    });
</script>
@endsection
