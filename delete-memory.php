<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$memoryId = $_POST["memory_id"] ?? null;

if (!$memoryId || !ctype_digit($memoryId)) {
    die("Invalid Memory ID.");
}

/*
 * Find the memory first so we know:
 * 1. which Rise to return to
 * 2. which uploaded file to delete
 */
$stmt = $pdo->prepare(
    "SELECT id, rise_id, file_path
     FROM memories
     WHERE id = :id"
);

$stmt->execute([
    "id" => $memoryId
]);

$memory = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$memory) {
    die("Memory not found.");
}

$riseId = $memory["rise_id"];

/*
 * Delete the database record.
 */
$stmt = $pdo->prepare(
    "DELETE FROM memories WHERE id = :id"
);

$stmt->execute([
    "id" => $memoryId
]);

/*
 * Delete the actual uploaded image too.
 */
$file = __DIR__ . "/" . $memory["file_path"];

if (is_file($file)) {
    unlink($file);
}

header("Location: view-rise.php?id=" . $riseId);
exit;