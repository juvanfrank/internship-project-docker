<?php
header('Content-Type: application/json');

include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit;
}

$email = $data["email"] ?? "";
$password = $data["password"] ?? "";

if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email and password are required"]);
    exit;
}

$stmt = $mysqli->prepare("SELECT password_hash FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hash);

if ($stmt->fetch() && password_verify($password, $hash)) {
    // Generate session token
    $sessionToken = bin2hex(random_bytes(32));
    
    // Store session in Redis (expires in 24 hours)
    $sessionData = json_encode([
        'email' => $email,
        'created_at' => time()
    ]);
    $redis->setex('session:' . $sessionToken, 86400, $sessionData);
    
    echo json_encode([
        "status" => "success",
        "session_token" => $sessionToken
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
}

$stmt->close();
?>
