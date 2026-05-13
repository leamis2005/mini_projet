<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_rh') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="data-card">
    <div class="data-card-head"><h3>Soldes des employes — <?= date('Y') ?></h3></div>
    <table class="tbl">
      <thead>
        <tr><th>Employe</th><th>Departement</th><th>Type</th><th>Attribues</th><th>Pris</th><th>Restant</th></tr>
      </thead>
      <tbody>
        <?php if (empty($soldes)): ?>
          <tr><td colspan="6"><div class="empty"><i class="bi bi-people"></i><p>Aucun solde disponible.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($soldes ?? []) as $solde): ?>
          <?php $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris']; ?>
          <tr>
            <td class="td-name"><?= esc($solde['employe_prenom'].' '.$solde['employe_nom']) ?></td>
            <td class="td-muted"><?= esc($solde['departement_nom'] ?? '-') ?></td>
            <td><span class="type-badge <?= esc(type_badge_class($solde['type_libelle'])) ?>"><?= esc($solde['type_libelle']) ?></span></td>
            <td class="td-mono"><?= esc($solde['jours_attribues']) ?> j</td>
            <td class="td-mono"><?= esc($solde['jours_pris']) ?> j</td>
            <td class="td-mono"><?= esc($restant) ?> j</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
