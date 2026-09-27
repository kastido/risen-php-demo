<?php

require_once "config/database.php";

$id = $_GET["id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Milestone ID.");
}

$stmt = $pdo->prepare(
    "SELECT * FROM milestones WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$milestone = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$milestone) {
    die("Milestone not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $notes = trim($_POST["notes"] ?? "");
    $milestoneDate = $_POST["milestone_date"] ?? "";

    if ($title === "" || $milestoneDate === "") {

        $message = "Please enter a milestone title and date.";

    } else {

        $stmt = $pdo->prepare(
            "UPDATE milestones
             SET title = :title,
                 notes = :notes,
                 milestone_date = :milestone_date
             WHERE id = :id"
        );

        $stmt->execute([
            "title" => $title,
            "notes" => $notes,
            "milestone_date" => $milestoneDate,
            "id" => $id
        ]);

        header(
            "Location: view-rise.php?id=" . $milestone["rise_id"]
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

    <title>Edit Milestone</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <p>
        <a href="view-rise.php?id=<?php echo $milestone["rise_id"]; ?>">
            ← Back to Rise
        </a>
    </p>

    <h1>⭐ Edit Milestone</h1>

    <?php if ($message !== ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <p>

            <label for="title">
                Milestone title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                value="<?php echo htmlspecialchars($milestone["title"]); ?>"
                required
            >

        </p>

        <p>

            <label for="notes">
                Notes
            </label>

            <textarea
                id="notes"
                name="notes"
                rows="5"
            ><?php echo htmlspecialchars($milestone["notes"] ?? ""); ?></textarea>

        </p>

        <p>

            <label for="milestone_date">
                Date
            </label>

            <input
                type="date"
                id="milestone_date"
                name="milestone_date"
                value="<?php echo htmlspecialchars($milestone["milestone_date"]); ?>"
                required
            >

        </p>

        <div class="actions">

            <button type="submit">
                Save Changes
            </button>

            <a
                class="button button-secondary"
                href="view-rise.php?id=<?php echo $milestone["rise_id"]; ?>"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

</body>
</html>