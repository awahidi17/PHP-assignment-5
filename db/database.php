<?php
/**
    * Database connection setup for the SportsPro application.
 *
 * Sql is named tech_support.sql and creates a database called `tech_support`.
 * this file will automatically fall back and connect to whichever exists.
 */

$host = 'localhost';
$username = 'root';
$password = '';

// Try these database names in order.
$dbNamesToTry = ['tech_support1', 'tech_support'];

$db = null;
$lastError = null;

foreach ($dbNamesToTry as $dbName) {
    try {
        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        break;
    } catch (PDOException $e) {
        $lastError = $e;
    }
}

if (!$db) {
    echo "Database error: " . ($lastError ? $lastError->getMessage() : 'Unknown error');
    exit;
}