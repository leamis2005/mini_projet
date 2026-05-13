<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_employe') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">
    <div>
      <div class="form-section">
        <h3>Details de la demande</h3>
        <form method="post" action="/employe/demandes">
          <?= csrf_field() ?>
          <div class="f-group" style="margin-bottom:1rem">
            <label class="f-label">Type de conge <span style="color:var(--danger)">*</span></label>
            <select class="f-select" name="type_conge_id">
              <option value="">-- Choisir un type --</option>
              <?php foreach (($types ?? []) as $type): ?>
                <?php $solde = $soldes[$type['id']] ?? null; $restant = $solde ? ((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) : null; ?>
                <option value="<?= esc($type['id']) ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>>
                  <?= esc($type['libelle']) ?><?= $restant !== null ? ' (' . $restant . ' j restants)' : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (session()->get('errors')['type_conge_id'] ?? null): ?>
              <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(session()->get('errors')['type_conge_id']) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Date de debut <span style="color:var(--danger)">*</span></label>
              <input type="date" name="date_debut" class="f-input" value="<?= esc(old('date_debut')) ?>"/>
              <?php if (session()->get('errors')['date_debut'] ?? null): ?>
                <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(session()->get('errors')['date_debut']) ?></div>
              <?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
              <input type="date" name="date_fin" class="f-input" value="<?= esc(old('date_fin')) ?>"/>
              <?php if (session()->get('errors')['date_fin'] ?? null): ?>
                <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(session()->get('errors')['date_fin']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="f-group" style="margin-bottom:1rem">
            <label class="f-label">Motif (optionnel)</label>
            <textarea name="motif" class="f-textarea" placeholder="Precisez le motif si necessaire..."><?= esc(old('motif')) ?></textarea>
            <div class="f-hint">Le motif est visible par le responsable RH.</div>
          </div>

          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
            <a href="/employe" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
          </div>
        </form>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:1rem">
      <div class="data-card" style="margin:0">
        <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
        <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
          <?php foreach (($types ?? []) as $type): ?>
            <?php $solde = $soldes[$type['id']] ?? null; $restant = $solde ? ((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) : 0; ?>
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                <span style="font-size:.8rem;color:var(--ink)"><?= esc($type['libelle']) ?></span>
                <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= esc($restant) ?> j</span>
              </div>
              <div class="solde-bar"><div class="solde-fill" style="width:<?= esc(($solde && $solde['jours_attribues'] > 0) ? ($restant / $solde['jours_attribues']) * 100 : 0) ?>%"></div></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="flash flash-info" style="margin:0">
        <i class="bi bi-info-circle-fill"></i>
        <span style="font-size:.8rem">Le solde est deduit uniquement a l'approbation de votre responsable.</span>
      </div>
      <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
        <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem"><i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des regles</div>
        <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
          <li>Preavis minimum : 48h avant la date de debut</li>
          <li>Pas de chevauchement avec une demande en cours</li>
          <li>Solde insuffisant = demande refusee automatiquement</li>
        </ul>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>
