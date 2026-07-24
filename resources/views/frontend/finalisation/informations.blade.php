@extends('frontend.layouts.app')

@section('title', 'Informations des parents')

@section('content')
<div class="step-card">
  <div class="step-label">Étape 3 / 4 — Parents / Tuteurs</div>
  <h1>Informations des parents</h1>
  <p class="subtitle">Renseignez les informations de contact de vos parents ou tuteurs.</p>

  @if ($errors->any())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('finalisation.informations.store') }}">
    @csrf

    <p class="step-label mt-2">Père</p>
    <div class="row g-2 mb-3">
      <div class="col-6">
        <label class="form-label">Nom</label>
        <input type="text" class="form-control" name="pere_nom" value="{{ old('pere_nom', $candidat->pere_nom) }}" required>
      </div>
      <div class="col-6">
        <label class="form-label">Prénom</label>
        <input type="text" class="form-control" name="pere_prenom" value="{{ old('pere_prenom', $candidat->pere_prenom) }}" required>
      </div>
      <div class="col-12">
        <label class="form-label">Contact</label>
        <div class="input-group">
          <span class="input-group-text">+225</span>
          <input type="tel" class="form-control tel-input" name="pere_contact" inputmode="numeric"
                 maxlength="10" pattern="[0-9]{10}" placeholder="07XXXXXXXX"
                 value="{{ old('pere_contact', $candidat->pere_contact) }}" required>
        </div>
      </div>
    </div>

    <p class="step-label mt-2">Mère</p>
    <div class="row g-2 mb-4">
      <div class="col-6">
        <label class="form-label">Nom</label>
        <input type="text" class="form-control" name="mere_nom" value="{{ old('mere_nom', $candidat->mere_nom) }}" required>
      </div>
      <div class="col-6">
        <label class="form-label">Prénom</label>
        <input type="text" class="form-control" name="mere_prenom" value="{{ old('mere_prenom', $candidat->mere_prenom) }}" required>
      </div>
      <div class="col-12">
        <label class="form-label">Contact</label>
        <div class="input-group">
          <span class="input-group-text">+225</span>
          <input type="tel" class="form-control tel-input" name="mere_contact" inputmode="numeric"
                 maxlength="10" pattern="[0-9]{10}" placeholder="07XXXXXXXX"
                 value="{{ old('mere_contact', $candidat->mere_contact) }}" required>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary-foot w-100">Continuer vers le paiement</button>
  </form>
</div>
@endsection
