<?php
require_once 'callAPI.php';
// Example: Call login endpoint
$login = callAPI('POST', 'http://localhost/my-auth-api/api/login.php', [
    'username' => 'hello',
    'password' => 'hello'
]);

header('Content-Type: application/json');
echo json_encode($login);