<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Fiche candidat — {{ $candidat->numero_dossier ?? $candidat->id }}</title>
  <style>
    body { font-family: sans-serif; color: #222; font-size: 12px; }
    h1 { color: #1a7a2e; font-size: 20px; margin-bottom: 0; }
    .sub { color: #666; margin-bottom: 20px; }
    h2 {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: #f47c20;
      border-bottom: 2px solid #f47c20;
      padding-bottom: 3px;
      margin: 22px 0 8px;
    }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 5px 4px; border-bottom: 1px solid #ddd; }
    td.label { color: #666; width: 40%; }
    td.value { font-weight: bold; }
    .statut {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 10px;
      border: 1px solid #1a7a2e;
      color: #1a7a2e;
      font-weight: bold;
      font-size: 11px;
    }
    .footer { margin-top: 30px; font-size: 10px; color: #888; }
  </style>
</head>
<body>
  <h1>SchoolFoot.ci</h1>
  <p class="sub">Fiche candidat — Saison {{ now()->year }}</p>

  <p>
    <span class="statut">
      @php
        $statutLabels = [
          'preinscrit' => 'Préinscrit',
          'en_attente_paiement' => 'En attente de paiement',
          'inscrit' => 'Inscrit',
        ];
      @endphp
      {{ $statutLabels[$candidat->statut] ?? $candidat->statut }}
    </span>
  </p>

  <h2>Identité</h2>
  <table>
    <tr><td class="label">Nom &amp; Prénom</td><td class="value">{{ $candidat->prenom }} {{ strtoupper($candidat->nom) }}</td></tr>
    <tr><td class="label">Numéro de dossier</td><td class="value">{{ $candidat->numero_dossier ?? '—' }}</td></tr>
    <tr><td class="label">Date de naissance</td><td class="value">{{ $candidat->date_naissance->format('d/m/Y') }} ({{ $candidat->age }} ans)</td></tr>
    <tr><td class="label">Lieu de naissance</td><td class="value">{{ $candidat->lieu_naissance }}</td></tr>
    <tr><td class="label">Ville</td><td class="value">{{ $candidat->ville }}</td></tr>
    <tr><td class="label">Téléphone</td><td class="value">{{ $candidat->telephone }}</td></tr>
  </table>

  <h2>Profil footballistique &amp; scolaire</h2>
  <table>
    <tr><td class="label">Niveau d'études</td><td class="value">{{ $candidat->niveau_etudes }}</td></tr>
    <tr><td class="label">Langues</td><td class="value">{{ $candidat->langues ? implode(', ', $candidat->langues) : '—' }}</td></tr>
    <tr><td class="label">Pied fort</td><td class="value">{{ $candidat->pieds_fort ? ucfirst($candidat->pieds_fort) : '—' }}</td></tr>
    <tr><td class="label">Numéro de poste</td><td class="value">{{ $candidat->numero_poste ?? '—' }}</td></tr>
  </table>

  <h2>Contact d'urgence</h2>
  <table>
    <tr><td class="label">Nom</td><td class="value">{{ $candidat->urgence_nom }}</td></tr>
    <tr><td class="label">Téléphone</td><td class="value">{{ $candidat->urgence_tel }}</td></tr>
  </table>

  @if ($candidat->pere_contact || $candidat->mere_contact)
    <h2>Parents / Tuteurs</h2>
    <table>
      <tr><td class="label">Père</td><td class="value">{{ $candidat->pere_prenom }} {{ $candidat->pere_nom }} — {{ $candidat->pere_contact ?? '—' }}</td></tr>
      <tr><td class="label">Mère</td><td class="value">{{ $candidat->mere_prenom }} {{ $candidat->mere_nom }} — {{ $candidat->mere_contact ?? '—' }}</td></tr>
    </table>
  @endif

  <h2>Parcours</h2>
  <table>
    <tr><td class="label">Préinscription</td><td class="value">{{ $candidat->created_at->format('d/m/Y à H:i') }}</td></tr>
    <tr><td class="label">Inscription finalisée</td><td class="value">{{ $candidat->inscrit_at ? $candidat->inscrit_at->format('d/m/Y à H:i') : 'En attente' }}</td></tr>
  </table>

  <p class="footer">Document généré automatiquement — SchoolFoot.ci — {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
