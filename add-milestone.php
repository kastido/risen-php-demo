<?php

require_once "config/database.php";

$riseId = $_GET["rise_id"] ?? null;

if (!$riseId || !ctype_digit($riseId)) {
    die("Invalid Rise ID.");
}

$stmt = $pdo->prepare(
    "SELECT id, title FROM rises WHERE id = :id"
);

$stmt->execute([
    "id" => $riseId
]);

$rise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rise) {
    die("Rise not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $notes = trim($_POST["notes"] ?? "");
    $milestoneDate = $_POST["milestone_date"] ?? "";

    if ($title === "" || $milestoneDate === "") {

        $message = "Please enter a milestone title and date.";

    } else {

        $sql = "INSERT INTO milestones
                (rise_id, title, notes, milestone_date)
                VALUES
                (:rise_id, :title, :notes, :milestone_date)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "rise_id" => $riseId,
            "title" => $title,
            "notes" => $notes,
            "milestone_date" => $milestoneDate
        ]);

        header("Location: view-rise.php?id=" . $riseId);
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

    <title>Record Milestone</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <p>
        <a href="view-rise.php?id=<?php echo $riseId; ?>">
            ← Back to Rise
        </a>
    </p>

    <h1>⭐ Record a Milestone</h1>

    <p class="intro">
        <?php echo htmlspecialchars($rise["title"]); ?>
    </p>

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
            ></textarea>

        </p>

        <p>

            <label for="milestone_date">
                Date
            </label>

            <input
                type="date"
                id="milestone_date"
                name="milestone_date"
                required
            >

        </p>

        <button type="submit">
            Save Milestone
        </button>

    </form>

</div>

</body>
</html>