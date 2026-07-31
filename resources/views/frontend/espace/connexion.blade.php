@extends('frontend.layouts.app')

@section('title', 'Espace candidat')

@section('content')
<div class="step-card">
  <div class="step-label">Espace candidat</div>
  <h1>Suivre mon dossier</h1>
  <p class="subtitle">Connectez-vous avec le numéro de téléphone utilisé lors de ta préinscription.</p>

  @if ($errors->any())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  @if ($errors->has('candidat'))
    <a href="{{ route('preinscription') }}#inscription" class="btn btn-primary-foot w-100 mb-3">
      Faire ma préinscription
    </a>
  @endif

  <form method="POST" action="{{ route('espace.verifier') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label" for="telephone">Numéro de téléphone</label>
      <div class="input-group">
        <span class="input-group-text">+225</span>
        <input type="tel" class="form-control tel-input" id="telephone" name="telephone" inputmode="numeric"
               maxlength="10" pattern="[0-9]{10}" placeholder="07XXXXXXXX"
               value="{{ old('telephone') }}" required autofocus>
      </div>
    </div>
    <button type="submit" class="btn btn-primary-foot w-100">Me connecter</button>
  </form>
</div>
@endsection
