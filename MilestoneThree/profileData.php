<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';



$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
error_log("Requested user ID in profileData.php (outside function): " . $userId);

if ($userId === 0) {
    error_log("Invalid user ID provided - ID is zero");
    echo json_encode(['error' => 'Invalid user ID provided']);
    exit();
}

// Check if the connection is established
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}
error_log("Database connection established");

// Use mysqli_query instead of prepared statements
$query = "SELECT users.id AS user_id, users.username, profile.first_name, profile.last_name, users.email, profile.bio, profile.job_title,profile.address, profile.phone_number,users.password, users.is_admin, users.is_active
          FROM users 
          JOIN profile ON users.id = profile.user_id 
          WHERE users.id = '$userId'";
error_log("Executing Query: " . $query);
$result = mysqli_query($conn, $query);

if (!$result) {
    error_log("Query Error: " . mysqli_error($conn));
    echo json_encode(['error' => 'Database query failed']);
    exit();
}

$userProfile = mysqli_fetch_assoc($result);
error_log("Retrieved Profile in profileData.php: " . json_encode($userProfile));

header('Content-Type: application/json');
if ($userProfile) {
    echo json_encode($userProfile);
} else {
    error_log("User profile not found for user ID: " . $userId);
    echo json_encode(['error' => 'User profile not found']);
}

