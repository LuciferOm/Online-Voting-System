<?php
require_once 'config.php';
require_login();
$uid = $_SESSION['user_id'];
$message = '';

$check = $pdo->prepare('SELECT candidate_id FROM votes WHERE user_id = ?');
$check->execute([$uid]);
$voted = $check->fetch();

if (!$voted && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $cid = (int)($_POST['candidate_id'] ?? 0);
    $exists = $pdo->prepare('SELECT id FROM candidates WHERE id = ?');
    $exists->execute([$cid]);
    if ($exists->fetch()) {
        try {
            $pdo->prepare('INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)')->execute([$uid, $cid]);
            header('Location: results.php?thanks=1'); exit;
        } catch (PDOException $e) {
            $voted = true;   // UNIQUE(user_id) blocked a duplicate vote
        }
    } else {
        $message = 'Choose a candidate from the list.';
    }
}
$candidates = $pdo->query('SELECT * FROM candidates ORDER BY name')->fetchAll();
include 'includes/header.php';
?>
<section class="card">
  <h1>Your ballot</h1>
  <?php if ($voted): ?>
    <p class="ok">You have already voted. Thank you for taking part.</p>
    <p><a class="btn" href="results.php">See live results</a></p>
  <?php elseif (!$candidates): ?>
    <p>No candidates have been added yet. Ask an admin to add some.</p>
  <?php else: ?>
    <?php if ($message): ?><p class="alert"><?= e($message) ?></p><?php endif; ?>
    <form method="post" onsubmit="return confirm('Cast this vote? You cannot change it later.')">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <?php foreach ($candidates as $c): ?>
        <label class="choice">
          <input type="radio" name="candidate_id" value="<?= $c['id'] ?>" required>
          <span><strong><?= e($c['name']) ?></strong><small><?= e($c['party']) ?></small></span>
        </label>
      <?php endforeach; ?>
      <button class="btn">Cast vote</button>
    </form>
  <?php endif; ?>
</section>
<?php include 'includes/footer.php'; ?>
