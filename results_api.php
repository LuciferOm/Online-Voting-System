<?php
require_once 'config.php';
header('Content-Type: application/json');
$rows = $pdo->query(
  'SELECT c.name, c.party, COUNT(v.id) AS votes
   FROM candidates c LEFT JOIN votes v ON v.candidate_id = c.id
   GROUP BY c.id ORDER BY votes DESC, c.name'
)->fetchAll();
echo json_encode(['total' => array_sum(array_column($rows, 'votes')), 'candidates' => $rows]);
