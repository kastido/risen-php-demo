<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$id = $_POST["id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Rise ID.");
}

/*
 * Make sure the Rise exists.
 */
$sql = "SELECT id, title FROM rises WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

$rise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rise) {
    die("Rise not found.");
}

/*
 * Delete the Rise.
 *
 * Because our moments table uses ON DELETE CASCADE,
 * Moments belonging to this Rise will also be deleted.
 */
$sql = "DELETE FROM rises WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

header("Location: rises.php");
exit;