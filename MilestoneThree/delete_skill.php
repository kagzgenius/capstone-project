<?php

/**
 * Creator: Zach Fordahl
 * Date: 10/26/2024
 * Class: CSC450
 * Instructor: James Tucker
 * Group: 3
 *
 * Project requirements:
 * - Desktop or Web application (with optional Android app)
 * - User authentication and user management
 * - Messaging (not external email)
 * - User Profile
 * - Dashboard
 * - Some form of transaction between users
 * - Use a database with at least five tables
 *
 * Description: We are creating a professional application similar to Reddit where working professionals can connect with each other.
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->
 * 
 * /**
 * delete_skills.php
 * 
 * This script is called when the `deleteSkills` function in `skills.js` 
 * is executed. It manages the deletion of a user's skill record from the database.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `skill_id` and `user_id` via GET or POST request 
 *    parameters to identify the specific skill record to be deleted.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file to 
 *    perform the deletion operation.
 * 
 * 3. Deleting the record:
 *    The script constructs an SQL DELETE statement to remove the record from the 
 *    skills table based on the provided `skill_id`, ensuring it matches the specified 
 *    `user_id` to prevent accidental deletions.

 */



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

$input = json_decode(file_get_contents('php://input'), true);

$skillId = isset($input['skill_id']) ? intval($input['skill_id']) : 0;
$userId = isset($input['user_id']) ? intval($input['user_id']) : 0;

if ($skillId === 0 || $userId === 0) {
    echo json_encode(['error' => 'Invalid input data']);
    exit();
}

$query = "DELETE FROM skills WHERE id='$skillId' AND user_id='$userId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Query Failed: ' . mysqli_error($conn)]);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Skill deleted successfully']);

