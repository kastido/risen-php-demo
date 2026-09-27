<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$id = $_POST["moment_id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Moment ID.");
}

$stmt = $pdo->prepare(
    "SELECT rise_id FROM moments WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$moment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moment) {
    die("Moment not found.");
}

$stmt = $pdo->prepare(
    "DELETE FROM moments WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

header(
    "Location: view-rise.php?id=" . $moment["rise_id"]
);

exit;