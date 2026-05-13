<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_employe') ?>
<?= $this->endSection() ?>

<?= $this->section('topActions') ?>
  <a href="/employe/demandes/create" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="data-card">
    <div class="data-card-head">
      <h3>Toutes mes demandes</h3>
      <form method="get" action="/employe/demandes" style="display:flex;gap:6px">
        <select class="f-select" name="statut" style="font-size:.8rem;padding:6px 10px;width:auto">
          <option value="" <?= ($statut ?? '') === '' ? 'selected' : '' ?>>Tous les statuts</option>
          <option value="en_attente" <?= ($statut ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
          <option value="approuvee" <?= ($statut ?? '') === 'approuvee' ? 'selected' : '' ?>>Approuvee</option>
          <option value="refusee" <?= ($statut ?? '') === 'refusee' ? 'selected' : '' ?>>Refusee</option>
          <option value="annulee" <?= ($statut ?? '') === 'annulee' ? 'selected' : '' ?>>Annulee</option>
        </select>
        <button class="btn-secondary" type="submit" style="padding:6px 12px;font-size:.8rem">Filtrer</button>
      </form>
    </div>
    <table class="tbl">
      <thead>
        <tr><th>Type</th><th>Debut</th><th>Fin</th><th>Duree</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php if (empty($demandes)): ?>
          <tr><td colspan="7"><div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande pour ce filtre.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($demandes ?? []) as $demande): ?>
          <tr>
            <td><span class="type-badge <?= esc(type_badge_class($demande['libelle'])) ?>"><?= esc($demande['libelle']) ?></span></td>
            <td class="td-muted"><?= esc(format_date($demande['date_debut'])) ?></td>
            <td class="td-muted"><?= esc(format_date($demande['date_fin'])) ?></td>
            <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
            <td><span class="statut <?= esc(statut_class($demande['statut'])) ?>"><?= esc($demande['statut']) ?></span></td>
            <td class="td-muted" style="font-size:.78rem"><?= esc($demande['commentaire_rh'] ?: '—') ?></td>
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
