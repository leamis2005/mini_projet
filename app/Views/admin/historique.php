<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="data-card">
    <div class="data-card-head"><h3>Historique des demandes</h3></div>
    <table class="tbl">
      <thead>
        <tr><th>Employe</th><th>Type</th><th>Periode</th><th>Duree</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php if (empty($demandes)): ?>
          <tr><td colspan="5"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande en historique.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($demandes ?? []) as $demande): ?>
          <tr>
            <td class="td-name"><?= esc($demande['employe_prenom'].' '.$demande['employe_nom']) ?></td>
            <td><span class="type-badge <?= esc(type_badge_class($demande['type_libelle'])) ?>"><?= esc($demande['type_libelle']) ?></span></td>
            <td class="td-muted"><?= esc(format_date($demande['date_debut'])) ?> – <?= esc(format_date($demande['date_fin'])) ?></td>
            <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
            <td><span class="statut <?= esc(statut_class($demande['statut'])) ?>"><?= esc($demande['statut']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
