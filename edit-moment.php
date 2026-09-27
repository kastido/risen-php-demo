<?php

require_once "config/database.php";

$id = $_GET["id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Moment ID.");
}

$stmt = $pdo->prepare(
    "SELECT * FROM moments WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$moment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moment) {
    die("Moment not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $momentText = trim($_POST["moment_text"] ?? "");
    $momentDate = $_POST["moment_date"] ?? "";

    if ($momentText === "" || $momentDate === "") {

        $message = "Please enter the Moment and date.";

    } else {

        $stmt = $pdo->prepare(
            "UPDATE moments
             SET moment_text = :moment_text,
                 moment_date = :moment_date
             WHERE id = :id"
        );

        $stmt->execute([
            "moment_text" => $momentText,
            "moment_date" => $momentDate,
            "id" => $id
        ]);

        header(
            "Location: view-rise.php?id=" . $moment["rise_id"]
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

    <title>Edit Moment</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <p>
        <a href="view-rise.php?id=<?php echo $moment["rise_id"]; ?>">
            ← Back to Rise
        </a>
    </p>

    <h1>✏️ Edit Moment</h1>

    <?php if ($message !== ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <p>

            <label for="moment_text">
                What happened?
            </label>

            <textarea
                id="moment_text"
                name="moment_text"
                rows="6"
                required
            ><?php echo htmlspecialchars($moment["moment_text"]); ?></textarea>

        </p>

        <p>

            <label for="moment_date">
                Date
            </label>

            <input
                type="date"
                id="moment_date"
                name="moment_date"
                value="<?php echo htmlspecialchars($moment["moment_date"]); ?>"
                required
            >

        </p>

        <div class="actions">

            <button type="submit">
                Save Changes
            </button>

            <a
                class="button button-secondary"
                href="view-rise.php?id=<?php echo $moment["rise_id"]; ?>"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

</body>
</html>