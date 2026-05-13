<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="form-section">
    <h3>Initialiser / ajuster un solde</h3>
    <form method="post" action="/admin/soldes">
      <?= csrf_field() ?>
      <div class="form-grid-2" style="margin-bottom:1rem">
        <div class="f-group">
          <label class="f-label">Employe</label>
          <select name="employe_id" class="f-select">
            <option value="">-- Choisir --</option>
            <?php foreach (($employes ?? []) as $emp): ?>
              <option value="<?= esc($emp['id']) ?>" <?= old('employe_id') == $emp['id'] ? 'selected' : '' ?>><?= esc($emp['prenom'].' '.$emp['nom']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (session()->get('errors')['employe_id'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['employe_id']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Type de conge</label>
          <select name="type_conge_id" class="f-select">
            <option value="">-- Choisir --</option>
            <?php foreach (($types ?? []) as $type): ?>
              <option value="<?= esc($type['id']) ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>><?= esc($type['libelle']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (session()->get('errors')['type_conge_id'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['type_conge_id']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Annee</label>
          <input type="number" name="annee" class="f-input" value="<?= esc(old('annee') ?? $year) ?>"/>
          <?php if (session()->get('errors')['annee'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['annee']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Jours attribues</label>
          <input type="number" name="jours_attribues" class="f-input" value="<?= esc(old('jours_attribues') ?? 0) ?>"/>
          <?php if (session()->get('errors')['jours_attribues'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['jours_attribues']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Jours pris</label>
          <input type="number" name="jours_pris" class="f-input" value="<?= esc(old('jours_pris') ?? 0) ?>"/>
          <?php if (session()->get('errors')['jours_pris'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['jours_pris']) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-forest" type="submit"><i class="bi bi-save"></i> Enregistrer</button>
      </div>
    </form>
  </div>

  <div class="data-card">
    <div class="data-card-head"><h3>Soldes annuels</h3></div>
    <table class="tbl">
      <thead>
        <tr><th>Employe</th><th>Type</th><th>Annee</th><th>Attribues</th><th>Pris</th><th>Restant</th></tr>
      </thead>
      <tbody>
        <?php if (empty($soldes)): ?>
          <tr><td colspan="6"><div class="empty"><i class="bi bi-piggy-bank"></i><p>Aucun solde disponible.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($soldes ?? []) as $solde): ?>
          <?php $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris']; ?>
          <tr>
            <td class="td-name"><?= esc($solde['employe_prenom'].' '.$solde['employe_nom']) ?></td>
            <td><span class="type-badge <?= esc(type_badge_class($solde['type_libelle'])) ?>"><?= esc($solde['type_libelle']) ?></span></td>
            <td class="td-mono"><?= esc($solde['annee']) ?></td>
            <td class="td-mono"><?= esc($solde['jours_attribues']) ?> j</td>
            <td class="td-mono"><?= esc($solde['jours_pris']) ?> j</td>
            <td class="td-mono"><?= esc($restant) ?> j</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
