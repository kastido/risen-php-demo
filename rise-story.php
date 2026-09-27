<?php

require_once "config/database.php";

$riseId = $_GET["id"] ?? null;

if (!$riseId || !ctype_digit($riseId)) {
    die("Invalid Rise ID.");
}

/*
 * Get the Rise
 */
$stmt = $pdo->prepare(
    "SELECT * FROM rises WHERE id = :id"
);

$stmt->execute([
    "id" => $riseId
]);

$rise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rise) {
    die("Rise not found.");
}

/*
 * Get Moments
 */
$stmt = $pdo->prepare(
    "SELECT
        id,
        moment_text AS title,
        moment_text AS content,
        moment_date AS story_date,
        'moment' AS type,
        NULL AS file_path
     FROM moments
     WHERE rise_id = :rise_id"
);

$stmt->execute([
    "rise_id" => $riseId
]);

$moments = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Get Memories
 */
$stmt = $pdo->prepare(
    "SELECT
        id,
        caption AS title,
        caption AS content,
        memory_date AS story_date,
        'memory' AS type,
        file_path
     FROM memories
     WHERE rise_id = :rise_id"
);

$stmt->execute([
    "rise_id" => $riseId
]);

$memories = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Get Milestones
 */
$stmt = $pdo->prepare(
    "SELECT
        id,
        title,
        notes AS content,
        milestone_date AS story_date,
        'milestone' AS type,
        NULL AS file_path
     FROM milestones
     WHERE rise_id = :rise_id"
);

$stmt->execute([
    "rise_id" => $riseId
]);

$milestones = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Combine all journey entries.
 */
$storyItems = array_merge(
    $moments,
    $memories,
    $milestones
);

/*
 * Sort chronologically.
 */
usort($storyItems, function ($a, $b) {

    return strcmp(
        $a["story_date"],
        $b["story_date"]
    );
});

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($rise["title"]); ?> — Rise Story
    </title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

    <div class="container">

        <div class="actions no-print">

            <a
                class="button button-secondary"
                href="view-rise.php?id=<?php echo $rise["id"]; ?>">
                ← Back to Rise
            </a>

            <button
                type="button"
                onclick="window.print();">
                📄 Save Story as PDF
            </button>

        </div>

        <p class="eyebrow">
            RISEN: JOURNEY
        </p>

        <h1>
            🌱 <?php echo htmlspecialchars($rise["title"]); ?>
        </h1>

        <p>
            <strong>Rise Story</strong>
        </p>

        <p>
            <?php echo htmlspecialchars($rise["description"]); ?>
        </p>

        <div class="story-meta">

            <span>
                <?php echo htmlspecialchars($rise["category"]); ?>
            </span>

            <span>
                Started <?php echo htmlspecialchars($rise["start_date"]); ?>
            </span>

        </div>

        <hr>

        <h2>The Story</h2>

        <?php if (empty($storyItems)): ?>

            <p>
                This Rise does not have any story entries yet.
            </p>

        <?php else: ?>

            <div class="story-timeline">

                <?php foreach ($storyItems as $item): ?>

                    <article class="story-item">

                        <div class="story-date">

                            <?php
                            echo date(
                                "d M Y",
                                strtotime($item["story_date"])
                            );
                            ?>

                        </div>

                        <?php if ($item["type"] === "moment"): ?>

                            <div class="story-type">
                                Moment
                            </div>

                            <p>
                                <?php echo nl2br(
                                    htmlspecialchars($item["content"])
                                ); ?>
                            </p>

                        <?php elseif ($item["type"] === "memory"): ?>

                            <div class="story-type">
                                📷 Memory
                            </div>

                            <?php if (!empty($item["file_path"])): ?>

                                <img
                                    src="<?php echo htmlspecialchars($item["file_path"]); ?>"
                                    alt="Rise memory"
                                    class="memory-image">

                            <?php endif; ?>

                            <?php if (!empty($item["content"])): ?>

                                <p>
                                    <?php echo htmlspecialchars($item["content"]); ?>
                                </p>

                            <?php endif; ?>

                        <?php elseif ($item["type"] === "milestone"): ?>

                            <div class="story-type">
                                ⭐ Milestone
                            </div>

                            <h3>
                                <?php echo htmlspecialchars($item["title"]); ?>
                            </h3>

                            <?php if (!empty($item["content"])): ?>

                                <p>
                                    <?php echo nl2br(
                                        htmlspecialchars($item["content"])
                                    ); ?>
                                </p>

                            <?php endif; ?>

                        <?php endif; ?>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>