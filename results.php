<?php include 'includes/header.php'; ?>
<section class="card">
  <h1>Live results</h1>
  <?php if (isset($_GET['thanks'])): ?><p class="ok">Your vote was recorded.</p><?php endif; ?>
  <p id="total" class="muted">Loading…</p>
  <div id="results"></div>
</section>
<?php include 'includes/footer.php'; ?>
