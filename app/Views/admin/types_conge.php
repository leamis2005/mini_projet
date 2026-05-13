<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="form-section">
    <h3><?= $editType ? 'Modifier un type de conge' : 'Ajouter un type de conge' ?></h3>
    <form method="post" action="<?= $editType ? '/admin/types-conge/' . $editType['id'] : '/admin/types-conge' ?>">
      <?= csrf_field() ?>
      <div class="form-grid-2" style="margin-bottom:1rem">
        <div class="f-group">
          <label class="f-label">Libelle</label>
          <input type="text" name="libelle" class="f-input" value="<?= esc(old('libelle') ?? ($editType['libelle'] ?? '')) ?>"/>
          <?php if (session()->get('errors')['libelle'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['libelle']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Jours annuels</label>
          <input type="number" name="jours_annuels" class="f-input" value="<?= esc(old('jours_annuels') ?? ($editType['jours_annuels'] ?? 0)) ?>"/>
          <?php if (session()->get('errors')['jours_annuels'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['jours_annuels']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Deductible</label>
          <select name="deductible" class="f-select">
            <?php $deductible = (string) (old('deductible') ?? ($editType['deductible'] ?? 1)); ?>
            <option value="1" <?= $deductible === '1' ? 'selected' : '' ?>>Oui</option>
            <option value="0" <?= $deductible === '0' ? 'selected' : '' ?>>Non</option>
          </select>
          <?php if (session()->get('errors')['deductible'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['deductible']) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> <?= $editType ? 'Mettre a jour' : 'Creer' ?></button>
        <a href="/admin/types-conge" class="btn-secondary">Reinitialiser</a>
      </div>
    </form>
  </div>

  <div class="data-card">
    <div class="data-card-head"><h3>Tous les types de conge</h3></div>
    <table class="tbl">
      <thead>
        <tr><th>Libelle</th><th>Jours annuels</th><th>Deductible</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($types)): ?>
          <tr><td colspan="4"><div class="empty"><i class="bi bi-tags"></i><p>Aucun type configure.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($types ?? []) as $type): ?>
          <tr>
            <td class="td-name"><?= esc($type['libelle']) ?></td>
            <td class="td-mono"><?= esc($type['jours_annuels']) ?> j</td>
            <td class="td-muted"><?= ((int) $type['deductible'] === 1) ? 'Oui' : 'Non' ?></td>
            <td>
              <div class="action-btns">
                <a class="btn-sm btn-edit" href="/admin/types-conge/<?= esc($type['id']) ?>/edit"><i class="bi bi-pencil"></i> Editer</a>
                <form method="post" action="/admin/types-conge/<?= esc($type['id']) ?>/delete">
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
