<?= $this->include('layout/head') ?>
<div class="app-wrap">
  <?= $this->renderSection('sidebar') ?>
  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title"><?= esc($pageTitle ?? '') ?></div>
        <div class="topbar-breadcrumb">
          <?= esc($breadcrumb ?? '') ?>
        </div>
      </div>
      <div class="topbar-actions">
        <?= $this->renderSection('topActions') ?>
      </div>
    </div>
    <div class="content">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('warning')): ?>
        <div class="flash flash-warn"><i class="bi bi-exclamation-triangle-fill"></i> <?= esc(session()->getFlashdata('warning')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('info')): ?>
        <div class="flash flash-info"><i class="bi bi-info-circle-fill"></i> <?= esc(session()->getFlashdata('info')) ?></div>
      <?php endif; ?>
      <?= $this->renderSection('content') ?>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
