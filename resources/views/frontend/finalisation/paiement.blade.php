@extends('frontend.layouts.app')

@section('title', 'Paiement de l\'inscription')

@push('styles')
<style>
  .pay-steps { counter-reset: pay-step; margin: 20px 0; }
  .pay-step {
    display: flex;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,255,255,.08);
  }
  .pay-step:last-child { border-bottom: none; }
  .pay-step-num {
    counter-increment: pay-step;
    flex-shrink: 0;
    width: 30px; height: 30px;
    border-radius: 50%;
    background: rgba(244,124,32,.15);
    border: 1px solid var(--orange);
    color: var(--orange-light);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700;
    font-size: .9rem;
  }
  .pay-step-num::before { content: counter(pay-step); }
  .btn-wave {
    background: #1DC8CD;
    color: #002E2F;
    font-weight: 700;
    border: none;
    border-radius: 10px;
    padding: 14px 20px;
    width: 100%;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .btn-wave:hover { background: #3ad8dc; color: #002E2F; }
</style>
@endpush

@section('content')
<div class="step-card">
  <div class="step-label">Étape 4 / 4 — Paiement</div>
  <h1>Paiement de l'inscription</h1>
  <p class="subtitle">Dernière étape avant de finaliser ton inscription.</p>

  <div class="mb-4">
    <div class="info-row"><span>Candidat</span><span>{{ $nomComplet }}</span></div>
    <div class="info-row"><span>Numéro de dossier</span><span>{{ $candidat->numero_dossier }}</span></div>
    <div class="info-row"><span>Téléphone</span><span>{{ $candidat->telephone }}</span></div>
    <div class="info-row"><span>Montant à payer</span><span>{{ number_format($montant, 0, ',', ' ') }} FCFA</span></div>
  </div>

  <div class="pay-steps">
    <div class="pay-step">
      <span class="pay-step-num"></span>
      <div>
        <strong>Paye {{ number_format($montant, 0, ',', ' ') }} FCFA sur Wave</strong>
        <p class="mb-0" style="color: rgba(248,249,245,.6); font-size: .88rem;">Clique sur le bouton ci-dessous pour ouvrir le lien de paiement officiel Wave.</p>
      </div>
    </div>
    <div class="pay-step">
      <span class="pay-step-num"></span>
      <div>
        <strong>Fais une capture d'écran</strong>
        <p class="mb-0" style="color: rgba(248,249,245,.6); font-size: .88rem;">Prends une capture de la confirmation de paiement Wave (reçu affiché après paiement).</p>
      </div>
    </div>
    <div class="pay-step">
      <span class="pay-step-num"></span>
      <div>
        <strong>Envoie la preuve depuis ton espace candidat</strong>
        <p class="mb-0" style="color: rgba(248,249,245,.6); font-size: .88rem;">
          Une fois payé, va dans ton <strong>espace candidat</strong> : le bouton "Envoyer ma preuve par WhatsApp"
          t'y attend, avec ton nom complet et ton numéro de dossier déjà pré-remplis.
        </p>
      </div>
    </div>
  </div>

  <div class="d-grid gap-2">
    <a href="{{ $wavePaymentLink }}" target="_blank" rel="noopener" class="btn-wave">
      <i class="bi bi-box-arrow-up-right"></i> Payer {{ number_format($montant, 0, ',', ' ') }} FCFA sur Wave
    </a>
  </div>

  <div class="alert alert-warning mt-4" style="background: rgba(247,184,75,.12); border: 1px solid rgba(247,184,75,.3); color: #f7b84b; font-size: .85rem;">
    <i class="bi bi-info-circle"></i> Après avoir payé, rends-toi dans ton
    <a href="{{ route('espace.connexion') }}" style="color: #f7b84b; text-decoration: underline;">espace candidat</a>
    pour envoyer ta preuve. Ton inscription passera en statut « Inscrit » dès que notre équipe l'aura vérifiée.
  </div>
</div>
@endsection
