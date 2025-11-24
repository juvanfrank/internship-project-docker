<?php
header('Content-Type: application/json');

include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit;
}

$sessionToken = $data["session_token"] ?? "";

if (!empty($sessionToken)) {
    // Delete session from Redis
    $redis->del('session:' . $sessionToken);
}

echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
?>
