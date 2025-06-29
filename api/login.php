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

$data = json_decode(file_get_contents("php://input"));
$username = trim($data->username ?? '');
$password = trim($data->password ?? '');

try {
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $token_data = ["id" => $user['id'], "username" => $username];
        $access_payload = [
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "data" => $token_data
        ];
        $access_token = JWT::encode($access_payload, $secret_key, 'HS256');

        $refresh_token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', time() + (86400 * 7));
        $stmt = $pdo->prepare("INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $refresh_token, $expires_at]);


        setcookie("refresh_token", $refresh_token, [
            "expires" => time() + (86400 * 7),
            "path" => "/",
            "httponly" => true,
            "secure" => false,
            "samesite" => "Strict"
        ]);

        echo json_encode([
            "access_token" => $access_token,
            "expires_in" => $expiration_time - $issued_at
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid login credentials."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Login failed.", "error" => $e->getMessage()]);
}
?>
