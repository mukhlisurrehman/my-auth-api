<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $jwt = $matches[1];

    // Check if token is blacklisted
    $blacklistPath = __DIR__ . '/../utils/blacklist.json';
    $blacklist = json_decode(file_get_contents($blacklistPath), true);
    if (in_array($jwt, $blacklist)) {
        http_response_code(401);
        echo json_encode(["message" => "Access denied. Token is blacklisted."]);
        exit;
    }

    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        echo json_encode([
            "message" => "Access granted.",
            "user_data" => $decoded->data
        ]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Access denied.", "error" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Authorization header not found."]);
}
?>
