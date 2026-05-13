<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_rh') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
    <a class="btn-secondary" href="/rh">Tous (<?= esc($counts['en_attente'] + $counts['approuvee'] + $counts['refusee'] + $counts['annulee']) ?>)</a>
    <a class="btn-secondary" href="/rh?statut=en_attente">En attente (<?= esc($counts['en_attente']) ?>)</a>
    <a class="btn-secondary" href="/rh?statut=approuvee">Approuvees (<?= esc($counts['approuvee']) ?>)</a>
    <a class="btn-secondary" href="/rh?statut=refusee">Refusees (<?= esc($counts['refusee']) ?>)</a>
    <form method="get" action="/rh" style="margin-left:auto">
      <?php if ($statut !== ''): ?>
        <input type="hidden" name="statut" value="<?= esc($statut) ?>"/>
      <?php endif; ?>
      <select class="f-select" name="departement_id" style="font-size:.8rem;padding:6px 10px;width:auto">
        <option value="">Tous les departements</option>
        <?php foreach (($departements ?? []) as $dept): ?>
          <option value="<?= esc($dept['id']) ?>" <?= ($departementId ?? '') == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['nom']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn-secondary" type="submit" style="padding:6px 12px;font-size:.8rem">Filtrer</button>
    </form>
  </div>

  <div class="data-card">
    <div class="data-card-head"><h3>Toutes les demandes</h3></div>
    <table class="tbl">
      <thead>
        <tr><th>Employe</th><th>Type</th><th>Periode</th><th>Duree</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($demandes)): ?>
          <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande a traiter.</p></div></td></tr>
        <?php endif; ?>
        <?php foreach (($demandes ?? []) as $demande): ?>
          <?php $soldeRestant = $demande['solde_restant']; ?>
          <tr>
            <td>
              <div class="profile-row">
                <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= esc(initials($demande['employe_prenom'], $demande['employe_nom'])) ?></div>
                <div class="profile-info">
                  <div class="pname"><?= esc($demande['employe_prenom'].' '.$demande['employe_nom']) ?></div>
                  <div class="pdept"><?= esc($demande['departement_nom'] ?? '-') ?></div>
                </div>
              </div>
            </td>
            <td><span class="type-badge <?= esc(type_badge_class($demande['type_libelle'])) ?>"><?= esc($demande['type_libelle']) ?></span></td>
            <td class="td-muted" style="font-size:.8rem"><?= esc(format_date($demande['date_debut'])) ?> – <?= esc(format_date($demande['date_fin'])) ?></td>
            <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
            <td>
              <?php if ($soldeRestant !== null): ?>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:<?= $soldeRestant < $demande['nb_jours'] ? 'var(--danger)' : 'var(--success)' ?>;font-weight:500"><?= esc($soldeRestant) ?> j</span>
              <?php else: ?>
                <span class="td-muted">—</span>
              <?php endif; ?>
            </td>
            <td><span class="statut <?= esc(statut_class($demande['statut'])) ?>"><?= esc($demande['statut']) ?></span></td>
            <td>
              <?php if ($demande['statut'] === 'en_attente'): ?>
                <div class="action-btns">
                  <form method="post" action="/rh/demandes/<?= esc($demande['id']) ?>/approuver">
                    <?= csrf_field() ?>
                    <button class="btn-sm btn-approve" type="submit" <?= ($soldeRestant !== null && $soldeRestant < $demande['nb_jours']) ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>><i class="bi bi-check-lg"></i> Approuver</button>
                  </form>
                  <form method="post" action="/rh/demandes/<?= esc($demande['id']) ?>/refuser">
                    <?= csrf_field() ?>
                    <button class="btn-sm btn-refuse" type="submit"><i class="bi bi-x-lg"></i> Refuser</button>
                  </form>
                </div>
              <?php else: ?>
                <span class="td-muted" style="font-size:.75rem">Traite</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?= $this->endSection() ?>
