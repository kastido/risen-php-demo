<?php

require_once "config/database.php";

$id = $_GET["id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Rise ID.");
}

/*
 * Get the existing Rise.
 */
$sql = "SELECT * FROM rises WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

$rise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rise) {
    die("Rise not found.");
}

$message = "";

/*
 * If the form has been submitted,
 * update the Rise.
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $startDate = $_POST["start_date"] ?? "";

    if ($title === "" || $startDate === "") {

        $message = "Please enter a title and start date.";
    } else {

        $sql = "UPDATE rises
                SET title = :title,
                    description = :description,
                    category = :category,
                    start_date = :start_date
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "title" => $title,
            "description" => $description,
            "category" => $category,
            "start_date" => $startDate,
            "id" => $id
        ]);

        header("Location: view-rise.php?id=" . $id);
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

    <title>Edit Rise</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

    <div class="container">

        <p>
            <a href="view-rise.php?id=<?php echo $rise["id"]; ?>">
                ← Back to Rise
            </a>
        </p>

        <h1>🌱 Edit Rise</h1>

        <?php if ($message !== ""): ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <p>

                <label for="title">
                    Rise title
                </label>

                <br>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo htmlspecialchars($rise["title"]); ?>"
                    required>

            </p>

            <p>

                <label for="description">
                    Description
                </label>

                <br>

                <textarea
                    id="description"
                    name="description"
                    rows="5"><?php echo htmlspecialchars($rise["description"]); ?></textarea>

            </p>

            <p>

                <label for="category">
                    Category
                </label>

                <br>

                <input
                    type="text"
                    id="category"
                    name="category"
                    value="<?php echo htmlspecialchars($rise["category"]); ?>">

            </p>

            <p>

                <label for="start_date">
                    Start date
                </label>

                <br>

                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="<?php echo htmlspecialchars($rise["start_date"]); ?>"
                    required>

            </p>

            <div class="actions">

                <button type="submit">
                    Save Changes
                </button>

                <a
                    class="button button-secondary"
                    href="view-rise.php?id=<?php echo $rise["id"]; ?>">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</body>

</html>