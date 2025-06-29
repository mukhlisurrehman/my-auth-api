<?php
$host = 'localhost';
$db   = 'jwt_api';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
