<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="form-section">
    <h3><?= $editDepartement ? 'Modifier un departement' : 'Ajouter un departement' ?></h3>
    <form method="post" action="<?= $editDepartement ? '/admin/departements/' . $editDepartement['id'] : '/admin/departements' ?>">
      <?= csrf_field() ?>
      <div class="form-grid-2" style="margin-bottom:1rem">
        <div class="f-group">
          <label class="f-label">Nom</label>
          <input type="text" name="nom" class="f-input" value="<?= esc(old('nom') ?? ($editDepartement['nom'] ?? '')) ?>"/>
          <?php if (session()->get('errors')['nom'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['nom']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Description</label>
          <input type="text" name="description" class="f-input" value="<?= esc(old('description') ?? ($editDepartement['description'] ?? '')) ?>"/>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> <?= $editDepartement ? 'Mettre a jour' : 'Creer' ?></button>
        <a href="/admin/departements" class="btn-secondary">Reinitialiser</a>
      </div>
    </form>
  </div>

  <div class="data-card">
    <div class="data-card-head"><h3>Tous les departements</h3></div>
    <table class="tbl">
      <thead>
        <tr><th>Nom</th><th>Description</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($departements)): ?>
          <tr><td colspan="3"><div class="empty"><i class="bi bi-building"></i><p>Aucun departement.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($departements ?? []) as $dept): ?>
          <tr>
            <td class="td-name"><?= esc($dept['nom']) ?></td>
            <td class="td-muted"><?= esc($dept['description'] ?: '—') ?></td>
            <td>
              <div class="action-btns">
                <a class="btn-sm btn-edit" href="/admin/departements/<?= esc($dept['id']) ?>/edit"><i class="bi bi-pencil"></i> Editer</a>
                <form method="post" action="/admin/departements/<?= esc($dept['id']) ?>/delete">
                  <?= csrf_field() ?>
                  <button class="btn-sm btn-del" type="submit"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
