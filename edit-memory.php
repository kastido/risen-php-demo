<?php

require_once "config/database.php";

$id = $_GET["id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Memory ID.");
}

$stmt = $pdo->prepare(
    "SELECT * FROM memories WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$memory = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$memory) {
    die("Memory not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $caption = trim($_POST["caption"] ?? "");
    $memoryDate = $_POST["memory_date"] ?? "";

    if ($memoryDate === "") {

        $message = "Please choose a date.";

    } else {

        $stmt = $pdo->prepare(
            "UPDATE memories
             SET caption = :caption,
                 memory_date = :memory_date
             WHERE id = :id"
        );

        $stmt->execute([
            "caption" => $caption,
            "memory_date" => $memoryDate,
            "id" => $id
        ]);

        header(
            "Location: view-rise.php?id=" . $memory["rise_id"]
        );

        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Memory</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <p>
        <a href="view-rise.php?id=<?php echo $memory["rise_id"]; ?>">
            ← Back to Rise
        </a>
    </p>

    <h1>📷 Edit Memory</h1>

    <img
        src="<?php echo htmlspecialchars($memory["file_path"]); ?>"
        alt="Rise memory"
        class="memory-image"
    >

    <?php if ($message !== ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <p>

            <label for="caption">
                Caption
            </label>

            <input
                type="text"
                id="caption"
                name="caption"
                maxlength="255"
                value="<?php echo htmlspecialchars($memory["caption"] ?? ""); ?>"
            >

        </p>

        <p>

            <label for="memory_date">
                Date
            </label>

            <input
                type="date"
                id="memory_date"
                name="memory_date"
                value="<?php echo htmlspecialchars($memory["memory_date"]); ?>"
                required
            >

        </p>

        <div class="actions">

            <button type="submit">
                Save Changes
            </button>

            <a
                class="button button-secondary"
                href="view-rise.php?id=<?php echo $memory["rise_id"]; ?>"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

</body>
</html>