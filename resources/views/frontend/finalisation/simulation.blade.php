@extends('frontend.layouts.app')

@section('title', 'Simulation de paiement')

@section('content')
<div class="step-card">
  <div class="step-label">Paiement (simulation)</div>
  <h1>Simulateur de paiement</h1>
  <p class="subtitle">
    L'intégration Wave n'est pas encore active : cette page simule le retour d'un
    paiement mobile money pour tester le parcours complet.
  </p>

  <div class="mb-4">
    <div class="info-row"><span>Référence</span><span>{{ $paiement->reference }}</span></div>
    <div class="info-row"><span>Montant</span><span>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span></div>
  </div>

  <form method="POST" action="{{ route('finalisation.paiement.callback') }}" class="d-grid gap-2">
    @csrf
    <input type="hidden" name="reference" value="{{ $paiement->reference }}">
    <button type="submit" name="resultat" value="succes" class="btn btn-primary-foot">
      <i class="bi bi-check-circle"></i> Simuler un paiement réussi
    </button>
    <button type="submit" name="resultat" value="echec" class="btn btn-outline-foot">
      <i class="bi bi-x-circle"></i> Simuler un paiement échoué
    </button>
  </form>
</div>
@endsection
