<?php

// Load Composer autoloader for MongoDB library
require_once __DIR__ . '/../vendor/autoload.php';

// Disable error display for clean JSON responses
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// MySQL - for authentication (email, password)
$mysqli = new mysqli("internship-mysql", "root", "root", "internship_db");

if ($mysqli->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "MySQL Connection Failed: " . $mysqli->connect_error]));
}

// MongoDB - for profile details (name, age, dob, contact)
try {
    $mongoClient = new MongoDB\Client("mongodb://internship-mongodb:27017");
    $mongoDB = $mongoClient->internship_db;
    $profiles = $mongoDB->profiles;
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "MongoDB Connection Failed: " . $e->getMessage()]));
}

// Redis - for session storage
try {
    $redis = new Redis();
    $redisConnected = @$redis->connect('internship-redis', 6379, 2);
    if (!$redisConnected) {
        throw new Exception("Failed to connect to Redis server");
    }
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "Redis Connection Failed: " . $e->getMessage()]));
} catch (Error $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "Redis Connection Failed: " . $e->getMessage()]));
}

?>
