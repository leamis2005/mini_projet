<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('topActions') ?>
  <a href="/admin/employes" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employe</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="form-section">
    <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i><?= $editEmploye ? 'Modifier un employe' : 'Ajouter un employe' ?></h3>
    <form method="post" action="<?= $editEmploye ? '/admin/employes/' . $editEmploye['id'] : '/admin/employes' ?>">
      <?= csrf_field() ?>
      <div class="form-grid-2" style="margin-bottom:1rem">
        <div class="f-group">
          <label class="f-label">Prenom</label>
          <input type="text" name="prenom" class="f-input" value="<?= esc(old('prenom') ?? ($editEmploye['prenom'] ?? '')) ?>"/>
          <?php if (session()->get('errors')['prenom'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['prenom']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Nom</label>
          <input type="text" name="nom" class="f-input" value="<?= esc(old('nom') ?? ($editEmploye['nom'] ?? '')) ?>"/>
          <?php if (session()->get('errors')['nom'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['nom']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Email</label>
          <input type="email" name="email" class="f-input" value="<?= esc(old('email') ?? ($editEmploye['email'] ?? '')) ?>"/>
          <?php if (session()->get('errors')['email'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['email']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Mot de passe initial</label>
          <input type="password" name="password" class="f-input" placeholder="<?= $editEmploye ? 'Laisser vide pour ne pas modifier' : 'Mot de passe initial' ?>"/>
          <?php if (session()->get('errors')['password'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['password']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Departement</label>
          <select name="departement_id" class="f-select">
            <option value="">-- Choisir --</option>
            <?php foreach (($departements ?? []) as $dept): ?>
              <option value="<?= esc($dept['id']) ?>" <?= (old('departement_id') ?? ($editEmploye['departement_id'] ?? '')) == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['nom']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (session()->get('errors')['departement_id'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['departement_id']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Role</label>
          <select name="role" class="f-select">
            <?php $roleValue = old('role') ?? ($editEmploye['role'] ?? 'employe'); ?>
            <option value="employe" <?= $roleValue === 'employe' ? 'selected' : '' ?>>Employe</option>
            <option value="rh" <?= $roleValue === 'rh' ? 'selected' : '' ?>>Responsable RH</option>
            <option value="admin" <?= $roleValue === 'admin' ? 'selected' : '' ?>>Administrateur</option>
          </select>
          <?php if (session()->get('errors')['role'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['role']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Date d'embauche</label>
          <input type="date" name="date_embauche" class="f-input" value="<?= esc(old('date_embauche') ?? ($editEmploye['date_embauche'] ?? '')) ?>"/>
          <?php if (session()->get('errors')['date_embauche'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['date_embauche']) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> <?= $editEmploye ? 'Mettre a jour' : 'Creer l\'employe' ?></button>
        <a href="/admin/employes" class="btn-secondary">Reinitialiser</a>
      </div>
    </form>
  </div>

  <div class="data-card">
    <div class="data-card-head">
      <h3>Tous les employes</h3>
    </div>
    <table class="tbl">
      <thead>
        <tr><th>Employe</th><th>Departement</th><th>Role</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($employes)): ?>
          <tr><td colspan="7"><div class="empty"><i class="bi bi-people"></i><p>Aucun employe.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($employes ?? []) as $employe): ?>
          <?php $annual = $annualMap[$employe['id']] ?? null; $restant = $annual ? ((int) $annual['jours_attribues'] - (int) $annual['jours_pris']) : null; ?>
          <tr>
            <td>
              <div class="profile-row">
                <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem"><?= esc(initials($employe['prenom'], $employe['nom'])) ?></div>
                <div class="profile-info"><div class="pname"><?= esc($employe['prenom'].' '.$employe['nom']) ?></div><div class="pdept"><?= esc($employe['email']) ?></div></div>
              </div>
            </td>
            <td class="td-muted"><?= esc($employe['departement_nom'] ?? '-') ?></td>
            <td><span class="type-badge" style="background:#f1efe8;color:#444441"><?= esc($employe['role']) ?></span></td>
            <td class="td-muted td-mono" style="font-size:.78rem"><?= esc($employe['date_embauche']) ?></td>
            <td><span class="statut s-approuvee" style="font-size:.68rem"><?= ((int) $employe['actif'] === 1) ? 'actif' : 'inactif' ?></span></td>
            <td>
              <?php if ($annual): ?>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)"><?= esc($restant) ?> / <?= esc($annual['jours_attribues']) ?> j</span>
              <?php else: ?>
                <span class="td-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="action-btns">
                <a class="btn-sm btn-edit" href="/admin/employes/<?= esc($employe['id']) ?>/edit"><i class="bi bi-pencil"></i> Editer</a>
                <form method="post" action="/admin/employes/<?= esc($employe['id']) ?>/toggle">
                  <?= csrf_field() ?>
                  <button class="btn-sm btn-del" type="submit"><i class="bi bi-slash-circle"></i></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
