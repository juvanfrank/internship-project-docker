<?php
header('Content-Type: application/json');

include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit;
}

$name = $data["name"] ?? "";
$email = $data["email"] ?? "";
$password = $data["password"] ?? "";
$age = $data["age"] ?? "";
$dob = $data["dob"] ?? "";
$contact = $data["contact"] ?? "";

if (empty($name) || empty($email) || empty($password) || empty($age) || empty($dob) || empty($contact)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Start transaction: Insert auth into MySQL and profile into MongoDB
$mysqli->begin_transaction();

try {
    // Insert authentication data into MySQL
    $stmt = $mysqli->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password_hash);
    
    if (!$stmt->execute()) {
        throw new Exception("MySQL insert failed: " . $mysqli->error);
    }
    
    $stmt->close();
    
    // Insert profile details into MongoDB
    $profileData = [
        'email' => $email,
        'name' => $name,
        'age' => $age,
        'dob' => $dob,
        'contact' => $contact,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ];
    
    $result = $profiles->insertOne($profileData);
    
    if ($result->getInsertedCount() === 0) {
        throw new Exception("MongoDB insert failed");
    }
    
    // Commit transaction
    $mysqli->commit();
    echo json_encode(["status" => "success"]);
    
} catch (Exception $e) {
    // Rollback on error
    $mysqli->rollback();
    
    if ($mysqli->errno == 1062) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed: " . $e->getMessage()]);
    }
}

$mysqli->close();
?>
