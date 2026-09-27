<?php

$appName = "RISEN: Journey";
$demoName = "PHP Demo";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?php echo $appName; ?> — <?php echo $demoName; ?></title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <div class="site-header">

        <div class="brand">
            RISEN: JOURNEY
        </div>

        <h1>
            🌱 <?php echo $appName; ?>
        </h1>

        <p class="intro">
            A PHP and MySQL implementation of selected concepts
            from RISEN: Journey.
        </p>

    </div>

    <div class="card">

        <h2>PHP Demo</h2>

        <p>
            This project demonstrates a simplified journey journal
            built with PHP, MySQL, PDO and server-side database operations.
        </p>

        <p>
            Create a Rise, document Moments, search your journeys,
            and manage saved records through a relational database.
        </p>

        <div class="actions">

            <a
                class="button"
                href="rises.php"
            >
                Enter Demo
            </a>

            <a
                class="button button-secondary"
                href="https://risenapp.io"
                target="_blank"
                rel="noopener noreferrer"
            >
                Visit RISEN: Journey
            </a>

        </div>

    </div>

    <div class="card">

        <h2>Built With</h2>

        <p class="meta">
            PHP 8 · MySQL / MariaDB · PDO · HTML · CSS
        </p>

        <p>
            Features include CRUD operations, prepared statements,
            search, form validation, relational data and a
            one-to-many Rise → Moments structure.
        </p>

    </div>

    <div class="footer">

        RISEN: Journey PHP Demo

    </div>

</div>

</body>

</html>