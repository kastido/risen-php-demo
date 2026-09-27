<?php

require_once "config/database.php";

$riseId = $_GET["rise_id"] ?? null;

if (!$riseId || !ctype_digit($riseId)) {
    die("Invalid Rise ID.");
}

/* Make sure the Rise actually exists */
$sql = "SELECT id, title FROM rises WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $riseId
]);

$rise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rise) {
    die("Rise not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $momentText = trim($_POST["moment_text"] ?? "");
    $momentDate = $_POST["moment_date"] ?? "";

    if ($momentText === "" || $momentDate === "") {

        $message = "Please enter a Moment and date.";
    } else {

        $sql = "INSERT INTO moments
                (rise_id, moment_text, moment_date)
                VALUES
                (:rise_id, :moment_text, :moment_date)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "rise_id" => $riseId,
            "moment_text" => $momentText,
            "moment_date" => $momentDate
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
        content="width=device-width, initial-scale=1.0">

    <title>Add Moment</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

    <div class="container">

        <p>
            <a href="view-rise.php?id=<?php echo $riseId; ?>">
                ← Back to Rise
            </a>
        </p>

        <h1>🌱 Add a Moment</h1>

        <h2>
            <?php echo htmlspecialchars($rise["title"]); ?>
        </h2>

        <?php if ($message !== ""): ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <p>

                <label for="moment_text">
                    What happened?
                </label>

                <br>

                <textarea
                    id="moment_text"
                    name="moment_text"
                    rows="6"
                    required></textarea>

            </p>

            <p>

                <label for="moment_date">
                    Date
                </label>

                <br>

                <input
                    type="date"
                    id="moment_date"
                    name="moment_date"
                    required>

            </p>

            <button type="submit">
                Save Moment
            </button>

        </form>
    </div>

</body>

</html>