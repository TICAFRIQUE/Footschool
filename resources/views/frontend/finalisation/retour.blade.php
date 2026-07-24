@extends('frontend.layouts.app')

@section('title', 'Résultat du paiement')

@section('content')
<div class="step-card text-center">
  @if ($paiement->statut === 'reussi')
    <div class="step-label">Inscription finalisée</div>
    <h1><i class="bi bi-check-circle-fill text-success"></i> Paiement réussi</h1>
    <p class="subtitle">Votre inscription est confirmée. Conservez précieusement votre numéro de dossier.</p>

    <div class="info-row"><span>Numéro de dossier</span><span>{{ $paiement->candidat->numero_dossier }}</span></div>
    <div class="info-row"><span>Référence paiement</span><span>{{ $paiement->reference }}</span></div>
    <div class="info-row"><span>Montant</span><span>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span></div>

    <a href="{{ route('espace.connexion') }}" class="btn btn-primary-foot w-100 mt-4">
      Accéder à mon espace candidat
    </a>
  @else
    <div class="step-label">Paiement échoué</div>
    <h1><i class="bi bi-x-circle-fill text-danger"></i> Le paiement a échoué</h1>
    <p class="subtitle">Aucun montant n'a été débité. Vous pouvez réessayer.</p>

    <a href="{{ route('finalisation.paiement') }}" class="btn btn-primary-foot w-100 mt-3">
      Réessayer le paiement
    </a>
  @endif
</div>
@endsection
