<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Reçu d'inscription — {{ $candidat->numero_dossier }}</title>
  <style>
    body { font-family: sans-serif; color: #222; font-size: 13px; }
    h1 { color: #1a7a2e; font-size: 20px; margin-bottom: 0; }
    .sub { color: #666; margin-bottom: 24px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    td { padding: 6px 4px; border-bottom: 1px solid #ddd; }
    td.label { color: #666; width: 45%; }
    td.value { font-weight: bold; }
    .amount { font-size: 18px; color: #1a7a2e; }
    .footer { margin-top: 40px; font-size: 11px; color: #888; }
  </style>
</head>
<body>
  <h1>SchoolFoot.ci</h1>
  <p class="sub">Reçu de paiement — Inscription {{ now()->year }}</p>

  <table>
    <tr><td class="label">Candidat</td><td class="value">{{ $candidat->prenom }} {{ strtoupper($candidat->nom) }}</td></tr>
    <tr><td class="label">Numéro de dossier</td><td class="value">{{ $candidat->numero_dossier }}</td></tr>
    <tr><td class="label">Téléphone</td><td class="value">{{ $candidat->telephone }}</td></tr>
    <tr><td class="label">Référence paiement</td><td class="value">{{ $paiement->reference }}</td></tr>
    <tr><td class="label">Transaction</td><td class="value">{{ $paiement->transaction_id }}</td></tr>
    <tr><td class="label">Date</td><td class="value">{{ $paiement->updated_at->format('d/m/Y H:i') }}</td></tr>
    <tr><td class="label">Montant payé</td><td class="value amount">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td></tr>
  </table>

  <p class="footer">Document généré automatiquement — SchoolFoot.ci — {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
