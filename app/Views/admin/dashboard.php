<?= $this->extend('layout/app') ?>

<?= $this->section('sidebar') ?>
  <?= $this->include('components/sidebar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('topActions') ?>
  <a href="/admin/employes" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employe</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="metrics">
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
      <div class="metric-val"><?= esc($activeCount) ?></div>
      <div class="metric-label">Employes actifs</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
      <div class="metric-val"><?= esc($pendingCount) ?></div>
      <div class="metric-label">Demandes en attente</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div></div>
      <div class="metric-val"><?= esc($approvedThisMonth) ?></div>
      <div class="metric-label">Approuvees ce mois</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
      <div class="metric-val"><?= esc($departementCount) ?></div>
      <div class="metric-label">Departements</div>
    </div>
    <div class="metric">
      <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
      <div class="metric-val"><?= esc($absentToday) ?></div>
      <div class="metric-label">Absents aujourd'hui</div>
    </div>
  </div>

  <div class="section-grid">
    <div class="data-card" style="margin:0">
      <div class="data-card-head">
        <h3>Demandes recentes</h3>
        <a href="/admin/historique" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
      </div>
      <table class="tbl">
        <thead>
          <tr><th>Employe</th><th>Type</th><th>Duree</th><th>Statut</th></tr>
        </thead>
        <tbody>
          <?php if (empty($recentDemandes)): ?>
            <tr><td colspan="4"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande recente.</p></div></td></tr>
          <?php endif; ?>
          <?php foreach (($recentDemandes ?? []) as $demande): ?>
            <tr>
              <td class="td-name"><?= esc($demande['employe_prenom'].' '.$demande['employe_nom']) ?></td>
              <td><span class="type-badge <?= esc(type_badge_class($demande['type_libelle'])) ?>"><?= esc($demande['type_libelle']) ?></span></td>
              <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
              <td><span class="statut <?= esc(statut_class($demande['statut'])) ?>"><?= esc($demande['statut']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="data-card" style="margin:0">
      <div class="data-card-head"><h3>Absences du mois</h3></div>
      <table class="tbl">
        <thead>
          <tr><th>Employe</th><th>Type</th><th>Periode</th></tr>
        </thead>
        <tbody>
          <?php if (empty($absencesMois)): ?>
            <tr><td colspan="3"><div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune absence approuvee.</p></div></td></tr>
          <?php endif; ?>
          <?php foreach (($absencesMois ?? []) as $absence): ?>
            <tr>
              <td class="td-name"><?= esc($absence['employe_prenom'].' '.$absence['employe_nom']) ?></td>
              <td><span class="type-badge <?= esc(type_badge_class($absence['type_libelle'])) ?>"><?= esc($absence['type_libelle']) ?></span></td>
              <td class="td-muted"><?= esc(format_date($absence['date_debut'])) ?> – <?= esc(format_date($absence['date_fin'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?= $this->endSection() ?>
