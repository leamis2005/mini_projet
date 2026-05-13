<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
    <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
  </div>
  <div class="sidebar-section">Menu</div>
  <ul class="sidebar-nav">
    <li><a href="/rh" class="<?= ($activeMenu ?? '') === 'demandes' ? 'active' : '' ?>"><i class="bi bi-inbox"></i> Demandes a traiter</a></li>
    <li><a href="/rh/soldes" class="<?= ($activeMenu ?? '') === 'soldes' ? 'active' : '' ?>"><i class="bi bi-people"></i> Soldes employes</a></li>
  </ul>
  <div class="sidebar-user">
    <div class="s-user-row">
      <div class="avatar av-blue"><?= esc(initials(auth_user()['prenom'] ?? '', auth_user()['nom'] ?? '')) ?></div>
      <div>
        <div class="user-name"><?= esc((auth_user()['prenom'] ?? '').' '.(auth_user()['nom'] ?? '')) ?></div>
        <div class="user-role">Responsable RH</div>
      </div>
      <a href="/logout" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Deconnexion"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</aside>
