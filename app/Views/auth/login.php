<?= $this->include('layout/head') ?>
<section class="auth-page geo-bg">
  <div class="auth-split">
    <div class="auth-left">
      <div>
        <p class="auth-left-brand">TechMada RH<span>Gestion des conges</span></p>
        <p class="auth-left-text" style="margin-top:2rem">
          <strong>Bienvenue sur votre espace RH.</strong>
          Gere tes demandes, consulte ton solde et suis l'etat des demandes en temps reel.
        </p>
      </div>
      <div class="auth-roles">
        <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de demonstration</div>
        <div class="role-pill">
          <i class="bi bi-shield-check"></i>
          <div><div class="role-pill-name">Administrateur</div><div class="role-pill-cred">admin@techmada.mg · admin123</div></div>
        </div>
        <div class="role-pill">
          <i class="bi bi-person-check"></i>
          <div><div class="role-pill-name">Responsable RH</div><div class="role-pill-cred">rh@techmada.mg · rh123</div></div>
        </div>
        <div class="role-pill">
          <i class="bi bi-person"></i>
          <div><div class="role-pill-name">Employe</div><div class="role-pill-cred">employe@techmada.mg · emp123</div></div>
        </div>
      </div>
    </div>

    <div class="auth-right">
      <p class="auth-title">Connexion</p>
      <p class="auth-sub">Entrez vos identifiants pour acceder a votre espace.</p>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?= esc(session()->getFlashdata('error')) ?>
        </div>
      <?php endif; ?>

      <?php if (session()->get('errors')): ?>
        <div class="flash flash-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          Merci de corriger les champs.
        </div>
      <?php endif; ?>

      <form method="post" action="/login">
        <?= csrf_field() ?>
        <div class="f-group">
          <label class="f-label">Adresse email</label>
          <input type="email" name="email" class="f-input" placeholder="vous@techmada.mg" value="<?= esc(old('email')) ?>"/>
          <?php if (session()->get('errors')['email'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['email']) ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label">Mot de passe</label>
          <input type="password" name="password" class="f-input" placeholder="••••••••"/>
          <?php if (session()->get('errors')['password'] ?? null): ?>
            <div class="f-error"><?= esc(session()->get('errors')['password']) ?></div>
          <?php endif; ?>
        </div>
        <button type="submit" class="btn-primary" style="margin-top:.5rem">
          Se connecter <i class="bi bi-arrow-right-short"></i>
        </button>
      </form>
    </div>
  </div>
</section>
</body>
</html>
