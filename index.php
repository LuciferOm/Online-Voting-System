<?php include 'includes/header.php'; ?>
<section class="card hero">
  <h1>One person, one vote.</h1>
  <p>Register, log in, and cast your vote securely. Results update live.</p>
  <p>
    <?php if (empty($_SESSION['user_id'])): ?>
      <a class="btn" href="register.php">Create an account</a>
      <a class="btn ghost" href="login.php">Log in</a>
    <?php else: ?>
      <a class="btn" href="vote.php">Go to your ballot</a>
    <?php endif; ?>
  </p>
</section>
<?php include 'includes/footer.php'; ?>
