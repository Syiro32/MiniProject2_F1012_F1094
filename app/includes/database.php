<?php

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD');
$dbName = getenv('DB_NAME') ?: 'campus_event_db';

$conn = new mysqli(
    $dbHost,
    $dbUser,
    $dbPassword === false ? '' : $dbPassword,
    $dbName
);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die("Database connection failed. Check the server configuration.");
}

$conn->set_charset("utf8mb4");