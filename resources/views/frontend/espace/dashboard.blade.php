@extends('frontend.layouts.app')

@section('title', 'Mon espace candidat')

@php
  $statutLabels = [
    'preinscrit'           => ['Préinscrit', 'secondary'],
    'en_attente_paiement'  => ['En attente de paiement', 'warning'],
    'inscrit'              => ['Inscrit', 'success'],
  ];
  [$statutLabel, $statutColor] = $statutLabels[$candidat->statut] ?? ['Inconnu', 'secondary'];

  $niveauLabels = [
    'Débutant' => 'Débutant', 'Intermédiaire' => 'Intermédiaire', 'Avancé' => 'Avancé',
  ];
@endphp

@push('styles')
<style>
  .section-heading {
    font-family: 'Barlow Condensed', sans-serif;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--orange-light);
    font-size: 1rem;
    margin: 28px 0 12px;
  }
  .timeline-item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,.08);
  }
  .timeline-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    margin-top: 6px;
    flex-shrink: 0;
  }
  .timeline-dot.done { background: var(--green-light); }
  .timeline-dot.pending { background: #6b6f76; }

  @media print {
    .brand-bar, .no-print { display: none !important; }
    body { background: #fff !important; color: #000 !important; }
    main { padding: 0 !important; display: block !important; }
    .step-card {
      max-width: 100% !important;
      background: #fff !important;
      border: none !important;
      box-shadow: none !important;
      backdrop-filter: none !important;
      color: #000 !important;
    }
    .step-label, .section-heading { color: #000 !important; }
    .info-row { border-bottom: 1px solid #ccc !important; }
    .info-row span:first-child { color: #555 !important; }
    .info-row span:last-child { color: #000 !important; }
    .badge { border: 1px solid #000 !important; color: #000 !important; background: none !important; }
    .timeline-dot.done { background: #000 !important; }
    .timeline-dot.pending { background: #999 !important; }
    .alert { display: none !important; }
  }
</style>
@endpush

@section('content')
<div class="step-card" style="max-width: 720px;">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <div class="step-label">Espace candidat</div>
      <h1>{{ $candidat->prenom }} {{ strtoupper($candidat->nom) }}</h1>
    </div>
    <span class="badge badge-statut bg-{{ $statutColor }}">{{ $statutLabel }}</span>
  </div>

  <div class="d-flex gap-2 flex-wrap mb-3 no-print">
    <a href="{{ route('espace.fiche') }}" class="btn btn-outline-foot btn-sm">
      <i class="bi bi-download"></i> Télécharger ma fiche (PDF)
    </a>
    <button type="button" onclick="window.print()" class="btn btn-outline-foot btn-sm">
      <i class="bi bi-printer"></i> Imprimer ma fiche
    </button>
  </div>

  @if ($candidat->statut !== 'inscrit')
    <div class="alert alert-warning">
      Ton inscription n'est pas encore finalisée.
      <a href="{{ route('finalisation.reprendre') }}">Terminer mon inscription</a>
    </div>
  @endif

  <div class="info-row"><span>Numéro de dossier</span><span>{{ $candidat->numero_dossier ?? '— (généré à l\'inscription)' }}</span></div>

  {{-- ── MON PARCOURS ─────────────────────────────────────────── --}}
  <p class="section-heading">Mon parcours</p>
  <div class="timeline-item">
    <span class="timeline-dot done"></span>
    <span>Préinscription effectuée le <strong>{{ $candidat->created_at->format('d/m/Y à H:i') }}</strong></span>
  </div>
  <div class="timeline-item">
    <span class="timeline-dot {{ in_array($candidat->statut, ['en_attente_paiement', 'inscrit']) ? 'done' : 'pending' }}"></span>
    <span>
      @if (in_array($candidat->statut, ['en_attente_paiement', 'inscrit']))
        Informations complémentaires (parents) renseignées
      @else
        Informations complémentaires (parents) — en attente
      @endif
    </span>
  </div>
  <div class="timeline-item" style="border-bottom:none;">
    <span class="timeline-dot {{ $candidat->inscrit_at ? 'done' : 'pending' }}"></span>
    <span>
      @if ($candidat->inscrit_at)
        Inscription finalisée (paiement validé) le <strong>{{ $candidat->inscrit_at->format('d/m/Y à H:i') }}</strong>
      @else
        Inscription (paiement) — en attente
      @endif
    </span>
  </div>

  {{-- ── MES INFORMATIONS ─────────────────────────────────────── --}}
  <p class="section-heading">Mes informations</p>
  <div class="row">
    <div class="col-md-6">
      <div class="info-row"><span>Date de naissance</span><span>{{ $candidat->date_naissance->format('d/m/Y') }}</span></div>
      <div class="info-row"><span>Âge</span><span>{{ $candidat->age }} ans</span></div>
      <div class="info-row"><span>Lieu de naissance</span><span>{{ $candidat->lieu_naissance }}</span></div>
      <div class="info-row"><span>Ville</span><span>{{ $candidat->ville }}</span></div>
      <div class="info-row"><span>Téléphone</span><span>{{ $candidat->telephone }}</span></div>
    </div>
    <div class="col-md-6">
      <div class="info-row"><span>Niveau d'études</span><span>{{ $candidat->niveau_etudes }}</span></div>
      <div class="info-row"><span>Langues</span><span>{{ $candidat->langues ? implode(', ', $candidat->langues) : '—' }}</span></div>
      <div class="info-row"><span>Pied fort</span><span>{{ $candidat->pieds_fort ? ucfirst($candidat->pieds_fort) : '—' }}</span></div>
      <div class="info-row"><span>Numéro de poste</span><span>{{ $candidat->numero_poste ?? '—' }}</span></div>
      <div class="info-row"><span>Contact d'urgence</span><span>{{ $candidat->urgence_nom }} ({{ $candidat->urgence_tel }})</span></div>
    </div>
  </div>

  {{-- ── PARENTS / TUTEURS ────────────────────────────────────── --}}
  @if ($candidat->pere_contact || $candidat->mere_contact)
    <p class="section-heading">Parents / Tuteurs</p>
    <div class="row">
      <div class="col-md-6">
        <div class="info-row"><span>Père</span><span>{{ $candidat->pere_prenom }} {{ $candidat->pere_nom }}</span></div>
        <div class="info-row"><span>Contact père</span><span>{{ $candidat->pere_contact ?? '—' }}</span></div>
      </div>
      <div class="col-md-6">
        <div class="info-row"><span>Mère</span><span>{{ $candidat->mere_prenom }} {{ $candidat->mere_nom }}</span></div>
        <div class="info-row"><span>Contact mère</span><span>{{ $candidat->mere_contact ?? '—' }}</span></div>
      </div>
    </div>
  @endif

  {{-- ── HISTORIQUE DES PAIEMENTS ─────────────────────────────── --}}
  <p class="section-heading">Historique des paiements</p>

  @forelse ($paiements as $paiement)
    <div class="info-row">
      <span>
        {{ $paiement->created_at->format('d/m/Y H:i') }} — {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
        <span class="badge bg-{{ $paiement->statut === 'reussi' ? 'success' : ($paiement->statut === 'echoue' ? 'danger' : 'warning') }}">
          {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
        </span>
      </span>
      <span class="no-print">
        @if ($paiement->statut === 'reussi')
          <a href="{{ route('espace.recu', $paiement) }}" class="btn btn-sm btn-outline-foot">
            <i class="bi bi-download"></i> Reçu
          </a>
        @endif
      </span>
    </div>
  @empty
    <p class="subtitle">Aucun paiement enregistré pour le moment.</p>
  @endforelse

  <form method="POST" action="{{ route('espace.deconnexion') }}" class="mt-4 no-print">
    @csrf
    <button type="submit" class="btn btn-outline-foot w-100">Se déconnecter</button>
  </form>
</div>
@endsection
