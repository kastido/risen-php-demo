<?php

require_once "config/database.php";

$search = trim($_GET["search"] ?? "");

if ($search !== "") {

    $sql = "SELECT * FROM rises
            WHERE title LIKE :search
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "search" => "%" . $search . "%"
    ]);
} else {

    $sql = "SELECT * FROM rises
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$rises = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Rises</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="container">

        <h1>🌱 My Rises</h1>

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search Rises..."
                value="<?php echo htmlspecialchars($search); ?>">

            <button type="submit">
                Search
            </button>

            <?php if ($search !== ""): ?>

                <a href="rises.php">
                    Clear
                </a>

            <?php endif; ?>

        </form>

        <p>
            <a href="create-rise.php">Create a new Rise</a>
        </p>

        <?php if (count($rises) === 0): ?>

            <?php if ($search !== ""): ?>

                <p>
                    No Rises found for
                    "<strong><?php echo htmlspecialchars($search); ?></strong>".
                </p>

            <?php else: ?>

                <p>You haven't created any Rises yet.</p>

            <?php endif; ?>

        <?php else: ?>

            <?php foreach ($rises as $rise): ?>

                <div class="card">

                    <p class="meta">
                        <?php echo htmlspecialchars($rise["category"]); ?>
                        ·
                        Started <?php echo htmlspecialchars($rise["start_date"]); ?>
                    </p>

                    <h2>
                        <a href="view-rise.php?id=<?php echo $rise["id"]; ?>">
                            <?php echo htmlspecialchars($rise["title"]); ?>
                        </a>
                    </h2>

                    <p>
                        <?php echo htmlspecialchars($rise["description"]); ?>
                    </p>

                    <a
                        class="button button-secondary"
                        href="view-rise.php?id=<?php echo $rise["id"]; ?>">
                        View Journey
                    </a>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>

</html>