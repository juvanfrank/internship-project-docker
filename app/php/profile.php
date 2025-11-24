<?php
header('Content-Type: application/json');

include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit;
}

$sessionToken = $data["session_token"] ?? "";

if (empty($sessionToken)) {
    echo json_encode(["status" => "error", "message" => "Session token is required"]);
    exit;
}

// Verify session token from Redis
$sessionData = $redis->get('session:' . $sessionToken);

if (!$sessionData) {
    echo json_encode(["status" => "error", "message" => "Invalid or expired session"]);
    exit;
}

$sessionInfo = json_decode($sessionData, true);
$email = $sessionInfo['email'] ?? "";

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Invalid session data"]);
    exit;
}

try {
    // Fetch profile details from MongoDB
    $profile = $profiles->findOne(['email' => $email]);
    
    if ($profile) {
        echo json_encode([
            "status" => "success",
            "data" => [
                "name" => $profile['name'] ?? '',
                "email" => $profile['email'] ?? $email,
                "age" => $profile['age'] ?? '',
                "dob" => $profile['dob'] ?? '',
                "contact" => $profile['contact'] ?? ''
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Profile not found"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error fetching profile: " . $e->getMessage()]);
}
?>
