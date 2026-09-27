<?php

/*
 * RISEN: Journey PHP Demo
 *
 * Local development database configuration.
 * Default values are intended for XAMPP.
 */

$host = "localhost";
$database = "risen_php_demo";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die(
        "Database connection failed. "
        . "Please check your local database configuration."
    );
}