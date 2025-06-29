<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/db.php';

use Firebase\JWT\JWT;

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
    http_response_code(401);
    echo json_encode(["message" => "No refresh token."]);
    exit;
}

try {
        $stmt = $pdo->prepare("SELECT user_id, expires_at FROM refresh_tokens WHERE token = ?");
    $stmt->execute([$refresh_token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && strtotime($row['expires_at']) > time()) {
        $user_id = $row['user_id'];

        $user_id = $row['user_id'];
        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $token_data = ["id" => $user_id, "username" => $user['username']];
        $payload = [
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "data" => $token_data
        ];
        $access_token = JWT::encode($payload, $secret_key, 'HS256');

        echo json_encode([
            "access_token" => $access_token,
            "expires_in" => $expiration_time - $issued_at
        ]);
    } else {
        http_response_code(403);
        echo json_encode(["message" => "Invalid refresh token."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Token refresh failed.", "error" => $e->getMessage()]);
}
?>
