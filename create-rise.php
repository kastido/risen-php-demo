<?php

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $startDate = $_POST["start_date"] ?? "";

    if ($title === "" || $startDate === "") {
        $message = "Please enter a title and start date.";
    } else {

        $sql = "INSERT INTO rises
                (title, description, category, start_date)
                VALUES
                (:title, :description, :category, :start_date)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "title" => $title,
            "description" => $description,
            "category" => $category,
            "start_date" => $startDate
        ]);

        $message = "Your Rise has been created.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create a Rise</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="container">

        <p>
            <a href="rises.php">← My Rises</a>
        </p>

        <h1>🌱 Create a Rise</h1>

        <?php if ($message !== ""): ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <p>
                <label for="title">Rise title</label><br>
                <input
                    type="text"
                    id="title"
                    name="title"
                    required>
            </p>

            <p>
                <label for="description">Description</label><br>
                <textarea
                    id="description"
                    name="description"
                    rows="5"></textarea>
            </p>

            <p>
                <label for="category">Category</label><br>
                <input
                    type="text"
                    id="category"
                    name="category">
            </p>

            <p>
                <label for="start_date">Start date</label><br>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    required>
            </p>

            <button type="submit">Create Rise</button>

        </form>

    </div>

</body>

</html>