@extends('frontend.layouts.app')

@section('title', 'Confirmation de vos informations')

@section('content')
<div class="step-card">
  <div class="step-label">Étape 2 / 4 — Confirmation</div>
  <h1>Vos informations</h1>
  <p class="subtitle">Vérifiez que ces informations, saisies lors de votre préinscription, sont bien les vôtres.</p>

  @if (session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
    <a href="{{ route('espace.connexion') }}" class="btn btn-primary-foot w-100">Accéder à mon espace candidat</a>
  @else
    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <div class="mb-3">
      <div class="info-row"><span>Nom</span><span>{{ $candidat->nom }}</span></div>
      <div class="info-row"><span>Prénom</span><span>{{ $candidat->prenom }}</span></div>
      <div class="info-row"><span>Date de naissance</span><span>{{ $candidat->date_naissance->format('d/m/Y') }}</span></div>
      <div class="info-row"><span>Téléphone</span><span>{{ $candidat->telephone }}</span></div>
      <div class="info-row"><span>Ville</span><span>{{ $candidat->ville }}</span></div>
    </div>

    <form method="POST" action="{{ route('finalisation.confirmer') }}">
      @csrf
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="confirme" name="confirme" value="1" required>
        <label class="form-check-label" for="confirme">
          Je confirme que ces informations sont bien les miennes.
        </label>
      </div>
      <button type="submit" class="btn btn-primary-foot w-100">Confirmer et continuer</button>
    </form>
  @endif
</div>
@endsection
