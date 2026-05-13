<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_employe') ?>
<?= $this->endSection() ?>

<?= $this->section('topActions') ?>
  <a href="/employe/demandes/create" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
    <i class="bi bi-plus-lg"></i> Nouvelle demande
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="metrics">
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
      <div class="metric-val"><?= esc($counts['en_attente'] ?? 0) ?></div>
      <div class="metric-label">En attente</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
      <div class="metric-val"><?= esc($counts['approuvee'] ?? 0) ?></div>
      <div class="metric-label">Approuvees</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
      <?php $totalRestant = 0; $totalAttribues = 0; foreach (($soldes ?? []) as $solde) { $totalRestant += (int) $solde['jours_attribues'] - (int) $solde['jours_pris']; $totalAttribues += (int) $solde['jours_attribues']; } ?>
      <div class="metric-val"><?= esc($totalRestant) ?></div>
      <div class="metric-label">Jours restants</div>
      <div class="metric-sub">sur <?= esc($totalAttribues) ?> cette annee</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
      <div class="metric-val"><?= esc($counts['refusee'] ?? 0) ?></div>
      <div class="metric-label">Refusees</div>
    </div>
  </div>

  <div class="data-card">
    <div class="data-card-head"><h3>Mes soldes de conges — <?= date('Y') ?></h3></div>
    <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
      <?php if (empty($soldes)): ?>
        <div class="empty" style="grid-column:1/-1"><i class="bi bi-folder"></i><p>Aucun solde disponible.</p></div>
      <?php endif; ?>
      <?php foreach (($soldes ?? []) as $solde): ?>
        <?php
          $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
          $ratio = $solde['jours_attribues'] > 0 ? ($restant / $solde['jours_attribues']) * 100 : 0;
          $barClass = $ratio <= 25 ? 'danger' : ($ratio <= 50 ? 'warn' : '');
        ?>
        <div class="solde-card" style="margin:0">
          <div class="solde-header">
            <span class="solde-type"><?= esc($solde['libelle']) ?></span>
            <span class="solde-nums"><strong><?= esc($restant) ?></strong> / <?= esc($solde['jours_attribues']) ?> j</span>
          </div>
          <div class="solde-bar"><div class="solde-fill <?= $barClass ?>" style="width:<?= esc($ratio) ?>%"></div></div>
          <div class="solde-label"><?= esc($restant) ?> jours restants · <?= esc($solde['jours_pris']) ?> pris</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="data-card">
    <div class="data-card-head">
      <h3>Mes dernieres demandes</h3>
      <a href="/employe/demandes" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
    </div>
    <table class="tbl">
      <thead>
        <tr><th>Type</th><th>Du</th><th>Au</th><th>Duree</th><th>Statut</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php if (empty($latestDemandes)): ?>
          <tr><td colspan="6"><div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande pour le moment.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($latestDemandes ?? []) as $demande): ?>
          <tr>
            <td><span class="type-badge <?= esc(type_badge_class($demande['libelle'])) ?>"><?= esc($demande['libelle']) ?></span></td>
            <td class="td-muted"><?= esc(format_date($demande['date_debut'])) ?></td>
            <td class="td-muted"><?= esc(format_date($demande['date_fin'])) ?></td>
            <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
            <td><span class="statut <?= esc(statut_class($demande['statut'])) ?>"><?= esc($demande['statut']) ?></span></td>
            <td>
              <?php if ($demande['statut'] === 'en_attente'): ?>
                <form method="post" action="/employe/demandes/<?= esc($demande['id']) ?>/annuler">
                  <?= csrf_field() ?>
                  <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x"></i> Annuler</button>
                </form>
              <?php else: ?>
                <span class="td-muted" style="font-size:.75rem">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
