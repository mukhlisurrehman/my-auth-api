<?php
require __DIR__ . '/../config/db.php';

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowed_origins = [
    'http://localhost',
    'http://127.0.0.1'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$refresh_token = $_COOKIE['refresh_token'] ?? '';

if (!$refresh_token) {
    http_response_code(400);
    echo json_encode(["message" => "No refresh token found."]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM refresh_tokens WHERE token = ?");
    $stmt->execute([$refresh_token]);

    setcookie("refresh_token", "", [
        "expires" => time() - 3600,
        "path" => "/",
        "httponly" => true,
        "secure" => false,
        "samesite" => "Strict"
    ]);

    echo json_encode(["message" => "Logged out successfully."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Logout failed.", "error" => $e->getMessage()]);
}
?>
