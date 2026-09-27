<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$id = $_POST["milestone_id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Milestone ID.");
}

$stmt = $pdo->prepare(
    "SELECT rise_id FROM milestones WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$milestone = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$milestone) {
    die("Milestone not found.");
}

$stmt = $pdo->prepare(
    "DELETE FROM milestones WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

header(
    "Location: view-rise.php?id=" . $milestone["rise_id"]
);

exit;