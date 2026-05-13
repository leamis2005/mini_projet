<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
    <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
  </div>
  <div class="sidebar-section">Gestion</div>
  <ul class="sidebar-nav">
    <li><a href="/admin" class="<?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
    <li><a href="/admin/historique" class="<?= ($activeMenu ?? '') === 'historique' ? 'active' : '' ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
    <li><a href="/admin/employes" class="<?= ($activeMenu ?? '') === 'employes' ? 'active' : '' ?>"><i class="bi bi-people"></i> Employes</a></li>
    <li><a href="/admin/departements" class="<?= ($activeMenu ?? '') === 'departements' ? 'active' : '' ?>"><i class="bi bi-building"></i> Departements</a></li>
    <li><a href="/admin/types-conge" class="<?= ($activeMenu ?? '') === 'types' ? 'active' : '' ?>"><i class="bi bi-tags"></i> Types de conge</a></li>
    <li><a href="/admin/soldes" class="<?= ($activeMenu ?? '') === 'soldes' ? 'active' : '' ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
  </ul>
  <div class="sidebar-user">
    <div class="s-user-row">
      <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem"><?= esc(initials(auth_user()['prenom'] ?? '', auth_user()['nom'] ?? '')) ?></div>
      <div>
        <div class="user-name"><?= esc((auth_user()['prenom'] ?? '').' '.(auth_user()['nom'] ?? '')) ?></div>
        <div class="user-role">Admin systeme</div>
      </div>
      <a href="/logout" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Deconnexion"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</aside>
