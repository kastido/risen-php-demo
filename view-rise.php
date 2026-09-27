<?php

require_once "config/database.php";

$id = $_GET["id"] ?? null;

if (!$id || !ctype_digit($id)) {
    die("Invalid Rise ID.");
}

$sql = "SELECT * FROM rises WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

$rise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rise) {
    die("Rise not found.");
}

$sql = "SELECT * FROM moments
        WHERE rise_id = :rise_id
        ORDER BY moment_date DESC, created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "rise_id" => $id
]);

$moments = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Get all milestones belonging to this Rise.
 */
$sql = "SELECT * FROM milestones
        WHERE rise_id = :rise_id
        ORDER BY milestone_date DESC, created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "rise_id" => $id
]);

$milestones = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Get all memories belonging to this Rise.
 */
$sql = "SELECT * FROM memories
        WHERE rise_id = :rise_id
        ORDER BY memory_date DESC, created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "rise_id" => $id
]);

$memories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($rise["title"]); ?>
    </title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="container">

        <p>
            <a href="rises.php">← My Rises</a>
        </p>

        <h1>
            🌱 <?php echo htmlspecialchars($rise["title"]); ?>
        </h1>

        <p>
            <strong>Category:</strong>
            <?php echo htmlspecialchars($rise["category"]); ?>
        </p>

        <p>
            <strong>Started:</strong>
            <?php echo htmlspecialchars($rise["start_date"]); ?>
        </p>

        <h2>About this Rise</h2>

        <p>
            <?php echo nl2br(htmlspecialchars($rise["description"])); ?>
        </p>

        <div class="actions">

            <a
                class="button button-secondary"
                href="edit-rise.php?id=<?php echo $rise["id"]; ?>">
                Edit Rise
            </a>

            <a
                class="button"
                href="add-moment.php?rise_id=<?php echo $rise["id"]; ?>">
                + Add Moment
            </a>

            <a
                class="button"
                href="add-memory.php?rise_id=<?php echo $rise["id"]; ?>">
                📷 Add Memory
            </a>

            <a
                class="button"
                href="add-milestone.php?rise_id=<?php echo $rise["id"]; ?>">
                ⭐ Add Milestone
            </a>

            <a
                class="button"
                href="rise-story.php?id=<?php echo $rise["id"]; ?>">
                📖 Rise Story
            </a>

        </div>

        <form
            method="POST"
            action="delete-rise.php"
            onsubmit="return confirm('Are you sure you want to delete this Rise?');">
            <input
                type="hidden"
                name="id"
                value="<?php echo $rise["id"]; ?>">

            <button
                class="button-danger"
                type="submit">
                Delete Rise
            </button>
        </form>

        <h2>Moments</h2>

        <?php if (count($moments) === 0): ?>

            <p>No Moments have been added yet.</p>

        <?php else: ?>

            <?php foreach ($moments as $moment): ?>

                <div class="moment">

                    <p class="meta">
                        <strong>
                            <?php echo htmlspecialchars($moment["moment_date"]); ?>
                        </strong>
                    </p>

                    <p>
                        <?php
                        echo nl2br(
                            htmlspecialchars($moment["moment_text"])
                        );
                        ?>
                    </p>

                    <div class="actions">

                        <a
                            class="button button-secondary"
                            href="edit-moment.php?id=<?php echo $moment["id"]; ?>">
                            Edit Moment
                        </a>

                        <form
                            method="POST"
                            action="delete-moment.php"
                            onsubmit="return confirm('Delete this Moment?');"
                            style="margin: 0;">

                            <input
                                type="hidden"
                                name="moment_id"
                                value="<?php echo $moment["id"]; ?>">

                            <button
                                class="button-danger"
                                type="submit">
                                Delete Moment
                            </button>

                        </form>

                    </div>

                </div>

            <?php endforeach; ?>

            <h2>Memories</h2>

            <?php if (count($memories) === 0): ?>

                <p class="meta">
                    No memories preserved yet.
                </p>

            <?php else: ?>

                <?php foreach ($memories as $memory): ?>

                    <div class="card">

                        <p class="meta">
                            📷 Memory ·
                            <?php echo htmlspecialchars($memory["memory_date"]); ?>
                        </p>

                        <img
                            src="<?php echo htmlspecialchars($memory["file_path"]); ?>"
                            alt="Rise memory"
                            class="memory-image">

                        <?php if (!empty($memory["caption"])): ?>

                            <p>
                                <?php echo htmlspecialchars($memory["caption"]); ?>
                            </p>

                        <?php endif; ?>

                        <div class="actions">

                            <a
                                class="button button-secondary"
                                href="edit-memory.php?id=<?php echo $memory["id"]; ?>">
                                Edit Memory
                            </a>

                            <form
                                method="POST"
                                action="delete-memory.php"
                                onsubmit="return confirm('Delete this Memory?');"
                                style="margin: 0;">

                                <input
                                    type="hidden"
                                    name="memory_id"
                                    value="<?php echo $memory["id"]; ?>">

                                <button
                                    class="button-danger"
                                    type="submit">
                                    Delete Memory
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

            <h2>Milestones</h2>

            <?php if (count($milestones) === 0): ?>

                <p class="meta">
                    No milestones recorded yet.
                </p>

            <?php else: ?>

                <?php foreach ($milestones as $milestone): ?>

                    <div class="card">

                        <p class="meta">
                            ⭐ Milestone ·
                            <?php echo htmlspecialchars($milestone["milestone_date"]); ?>
                        </p>

                        <h3>
                            <?php echo htmlspecialchars($milestone["title"]); ?>
                        </h3>

                        <?php if (!empty($milestone["notes"])): ?>

                            <p>
                                <?php
                                echo nl2br(
                                    htmlspecialchars($milestone["notes"])
                                );
                                ?>
                            </p>

                            <div class="actions">

                                <a
                                    class="button button-secondary"
                                    href="edit-milestone.php?id=<?php echo $milestone["id"]; ?>">
                                    Edit Milestone
                                </a>

                                <form
                                    method="POST"
                                    action="delete-milestone.php"
                                    onsubmit="return confirm('Delete this Milestone?');"
                                    style="margin: 0;">

                                    <input
                                        type="hidden"
                                        name="milestone_id"
                                        value="<?php echo $milestone["id"]; ?>">

                                    <button
                                        class="button-danger"
                                        type="submit">
                                        Delete Milestone
                                    </button>

                                </form>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</body>

</html>