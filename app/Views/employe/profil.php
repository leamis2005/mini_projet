<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_employe') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="section-grid">
    <div>
      <div class="form-section">
        <h3>Mon profil</h3>
        <form method="post" action="/employe/profil">
          <?= csrf_field() ?>
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Prenom</label>
              <input type="text" name="prenom" class="f-input" value="<?= esc(old('prenom') ?? ($employe['prenom'] ?? '')) ?>"/>
              <?php if (session()->get('errors')['prenom'] ?? null): ?>
                <div class="f-error"><?= esc(session()->get('errors')['prenom']) ?></div>
              <?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Nom</label>
              <input type="text" name="nom" class="f-input" value="<?= esc(old('nom') ?? ($employe['nom'] ?? '')) ?>"/>
              <?php if (session()->get('errors')['nom'] ?? null): ?>
                <div class="f-error"><?= esc(session()->get('errors')['nom']) ?></div>
              <?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Email</label>
              <input type="email" class="f-input" value="<?= esc($employe['email'] ?? '') ?>" disabled/>
            </div>
            <div class="f-group">
              <label class="f-label">Mot de passe (optionnel)</label>
              <input type="password" name="password" class="f-input" placeholder="Nouveau mot de passe"/>
              <?php if (session()->get('errors')['password'] ?? null): ?>
                <div class="f-error"><?= esc(session()->get('errors')['password']) ?></div>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-save"></i> Enregistrer</button>
            <a href="/employe" class="btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
          </div>
        </form>
      </div>
    </div>

    <div>
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes</h3></div>
        <div style="padding:1rem 1.25rem;display:flex;flex-direction:column;gap:1rem">
          <?php foreach (($soldes ?? []) as $solde): ?>
            <?php $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris']; ?>
            <div>
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
                <span class="solde-type"><?= esc($solde['libelle']) ?></span>
                <span class="solde-nums"><strong><?= esc($restant) ?></strong> / <?= esc($solde['jours_attribues']) ?> j</span>
              </div>
              <div class="solde-bar"><div class="solde-fill" style="width:<?= esc($solde['jours_attribues'] > 0 ? ($restant / $solde['jours_attribues']) * 100 : 0) ?>%"></div></div>
              <div class="solde-label"><?= esc($restant) ?> jours restants · <?= esc($solde['jours_pris']) ?> pris</div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>
