<?php
session_start();
include('databaseConnection.php');

// Disable error display and enable error logging
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\Program Files\Ampps\www\Application Template\error.log');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Function to handle errors with more context
function handleError($stmt, $conn, $context, $sql = '') {
    error_log('Error in ' . $context . ': ' . htmlspecialchars($stmt ? $stmt->error : $conn->error) . ' SQL: ' . $sql);
    die('An error occurred. Please try again later.');
}

// Check database connection
if ($conn->connect_error) {
    handleError(null, $conn, 'Database Connection');
}

// Fetch user profile information from users table
$sql = "SELECT id, username, email FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    handleError(null, $conn, 'Preparing statement for users table', $sql);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    handleError($stmt, null, 'Executing statement for users table', $sql);
}
$user = $result->fetch_assoc();

// Fetch additional profile information from profile table
$sql = "SELECT first_name, last_name, bio FROM profile WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    handleError(null, $conn, 'Preparing statement for profile table', $sql);
}
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    handleError($stmt, null, 'Executing statement for profile table', $sql);
}
$usertwo = $result->fetch_assoc();

$stmt->close();
$conn->close();

$pageTitle = "Profile";
$pageContent = "
<h1>Profile of " . htmlspecialchars($user['username']) . "</h1>
<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>
<p><strong>First Name:</strong> " . htmlspecialchars($usertwo['first_name']) . "</p>
<p><strong>Last Name:</strong> " . htmlspecialchars($usertwo['last_name']) . "</p>
<p><strong>Bio:</strong> " . htmlspecialchars($usertwo['bio']) . "</p>
";
include('Application_Template.php');