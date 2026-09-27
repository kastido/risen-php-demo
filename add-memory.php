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

    $caption = trim($_POST["caption"] ?? "");
    $memoryDate = $_POST["memory_date"] ?? "";

    if ($memoryDate === "") {

        $message = "Please choose a date.";

    } elseif (
        !isset($_FILES["memory_file"]) ||
        $_FILES["memory_file"]["error"] !== UPLOAD_ERR_OK
    ) {

        $message = "Please choose an image to upload.";

    } else {

        $file = $_FILES["memory_file"];

        /*
         * Limit this demo to 5 MB.
         */
        if ($file["size"] > 5 * 1024 * 1024) {

            $message = "The image must be 5 MB or smaller.";

        } else {

            /*
             * Determine the real MIME type of the uploaded file.
             */
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file["tmp_name"]);

            $allowedTypes = [
                "image/jpeg" => "jpg",
                "image/png"  => "png",
                "image/webp" => "webp"
            ];

            if (!isset($allowedTypes[$mimeType])) {

                $message = "Only JPG, PNG and WEBP images are allowed.";

            } else {

                $extension = $allowedTypes[$mimeType];

                /*
                 * Generate our own filename instead of trusting
                 * the original uploaded filename.
                 */
                $fileName =
                    bin2hex(random_bytes(16))
                    . "."
                    . $extension;

                $uploadDirectory = __DIR__ . "/uploads/";
                $destination = $uploadDirectory . $fileName;

                if (!move_uploaded_file(
                    $file["tmp_name"],
                    $destination
                )) {

                    $message = "The image could not be saved.";

                } else {

                    $filePath = "uploads/" . $fileName;

                    $sql = "INSERT INTO memories
                            (
                                rise_id,
                                caption,
                                file_path,
                                memory_date
                            )
                            VALUES
                            (
                                :rise_id,
                                :caption,
                                :file_path,
                                :memory_date
                            )";

                    $stmt = $pdo->prepare($sql);

                    $stmt->execute([
                        "rise_id" => $riseId,
                        "caption" => $caption,
                        "file_path" => $filePath,
                        "memory_date" => $memoryDate
                    ]);

                    header(
                        "Location: view-rise.php?id=" . $riseId
                    );

                    exit;
                }
            }
        }
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

    <title>Add Memory</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <p>
        <a href="view-rise.php?id=<?php echo $riseId; ?>">
            ← Back to Rise
        </a>
    </p>

    <h1>📷 Add a Memory</h1>

    <p class="intro">
        <?php echo htmlspecialchars($rise["title"]); ?>
    </p>

    <?php if ($message !== ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <p>

            <label for="memory_file">
                Image
            </label>

            <input
                type="file"
                id="memory_file"
                name="memory_file"
                accept="image/jpeg,image/png,image/webp"
                required
            >

        </p>

        <p>

            <label for="caption">
                Caption
            </label>

            <input
                type="text"
                id="caption"
                name="caption"
                maxlength="255"
                placeholder="What makes this memory meaningful?"
            >

        </p>

        <p>

            <label for="memory_date">
                Date
            </label>

            <input
                type="date"
                id="memory_date"
                name="memory_date"
                required
            >

        </p>

        <button type="submit">
            Save Memory
        </button>

    </form>

</div>

</body>

</html>