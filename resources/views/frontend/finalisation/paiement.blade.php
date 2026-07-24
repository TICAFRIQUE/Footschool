@extends('frontend.layouts.app')

@section('title', 'Paiement de l\'inscription')

@section('content')
<div class="step-card">
  <div class="step-label">Étape 4 / 4 — Paiement</div>
  <h1>Paiement de l'inscription</h1>
  <p class="subtitle">Dernière étape avant de finaliser votre inscription.</p>

  <div class="mb-4">
    <div class="info-row"><span>Candidat</span><span>{{ $candidat->prenom }} {{ strtoupper($candidat->nom) }}</span></div>
    <div class="info-row"><span>Téléphone</span><span>{{ $candidat->telephone }}</span></div>
    <div class="info-row"><span>Montant de l'inscription</span><span>{{ number_format($montant, 0, ',', ' ') }} FCFA</span></div>
  </div>

  <form method="POST" action="{{ route('finalisation.paiement.initier') }}">
    @csrf
    <button type="submit" class="btn btn-primary-foot w-100">
      <i class="bi bi-wallet2"></i> Payer {{ number_format($montant, 0, ',', ' ') }} FCFA
    </button>
  </form>
</div>
@endsection
