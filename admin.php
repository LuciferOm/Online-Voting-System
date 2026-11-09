<?php
require_once 'config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    if ($action === 'add_candidate' && trim($_POST['name'] ?? '') !== '') {
        $pdo->prepare('INSERT INTO candidates (name, party) VALUES (?, ?)')
            ->execute([trim($_POST['name']), trim($_POST['party'] ?? '') ?: 'Independent']);
    } elseif ($action === 'delete_candidate') {
        $pdo->prepare('DELETE FROM candidates WHERE id = ?')->execute([(int)$_POST['id']]);
    } elseif ($action === 'delete_voter') {
        $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'voter'")->execute([(int)$_POST['id']]);
    }
    header('Location: admin.php'); exit;
}

$candidates = $pdo->query('SELECT * FROM candidates ORDER BY name')->fetchAll();
$voters = $pdo->query(
  "SELECT u.id, u.name, u.email, (v.id IS NOT NULL) AS has_voted
   FROM users u LEFT JOIN votes v ON v.user_id = u.id
   WHERE u.role = 'voter' ORDER BY u.name"
)->fetchAll();
include 'includes/header.php';
?>
<section class="card">
  <h1>Add a candidate</h1>
  <form method="post" class="inline">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="action" value="add_candidate">
    <input name="name" placeholder="Candidate name" required>
    <input name="party" placeholder="Party (optional)">
    <button class="btn">Add candidate</button>
  </form>
</section>

<section class="card">
  <h2>Candidates</h2>
  <?php if (!$candidates): ?><p class="muted">No candidates yet. Add the first one above.</p><?php endif; ?>
  <table>
    <?php foreach ($candidates as $c): ?>
    <tr>
      <td><?= e($c['name']) ?></td><td><?= e($c['party']) ?></td>
      <td>
        <form method="post" onsubmit="return confirm('Delete this candidate and their votes?')">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <input type="hidden" name="action" value="delete_candidate">
          <input type="hidden" name="id" value="<?= $c['id'] ?>">
          <button class="btn danger">Delete</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>

<section class="card">
  <h2>Voters</h2>
  <?php if (!$voters): ?><p class="muted">No voters have registered yet.</p><?php endif; ?>
  <table>
    <?php foreach ($voters as $v): ?>
    <tr>
      <td><?= e($v['name']) ?></td><td><?= e($v['email']) ?></td>
      <td><?= $v['has_voted'] ? 'Voted' : 'Not voted' ?></td>
      <td>
        <form method="post" onsubmit="return confirm('Remove this voter?')">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <input type="hidden" name="action" value="delete_voter">
          <input type="hidden" name="id" value="<?= $v['id'] ?>">
          <button class="btn danger">Remove</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>
<?php include 'includes/footer.php'; ?>
