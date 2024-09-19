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
    header('Location: login.php'); //send to login page if username not already connected
    exit();
}

$username = $_SESSION['username'];
$pageTitle = "Home";
$pageContent = "<h1>Welcome back, $username!</h1>
<p>This is the home page content.</p>";
include('Application_Template.php');
