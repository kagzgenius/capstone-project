<?php

/**
 * <!--
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
 * update_interest.php
 * 
 * This script processes updates to a user's interests and fetches interests data. It is called when 
 * the `updateInterests` function in `interests.js` is executed.
 * 
 * Functionality includes:
 * 
 * 1. fetchInterests:
 *    This function calls the interests table to fetch the id, interest_name, and user_id 
 *    for the specified userId. The fetched data is used by home.js in the viewSameInterests function to 
 *    populate a list of the interests associated with the logged-in user.
 * 
 * 2. fetchUsersWithInterest:
 *    This function takes the userId and interestName that are passed from home.js FetchUsersWithInterests.
 *    It executes a SELECT statement to fetch all users who have the specified interest 
 *    and are not the logged-in user. The function retrieves the user data by joining the users and interests tables.
 *    The fetched data is used to display the users with the selected interest in home.js.
 * 
 * 3. updateInterest:
 *    This function is used to update the interests for the specified user.
 *    The profile tab on index.php uses the UpdateDeleteInterests function in interests.js 
 *    to create a table of all the interests available for the user. When a user clicks on update in the table 
 *    created by UpdateDeleteInterests, it sends the interest name, id, and userId to updateInterests in interests.js.
 *    updateInterests in interests.js then calls the updateInterest function in update_interest.php 
 *    to update the interests table with the changes made.
 * 
 * The script performs the following steps:
 * - Checks if the necessary parameters (`interest_id`, `user_id`, and updated interest data) are provided via GET or POST requests.
 * - Establishes a connection to the database using the `database.php` file to perform the update operation.
 * - Constructs an SQL UPDATE statement to modify the record in the interests table based on the provided `interest_id`, applying the new interest data while ensuring it corresponds to the specified `user_id`.
 * - Handles any errors that occur during the update process and provides confirmation messages upon successful completion of the update.
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

// Ensure the database connection is established
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

/**
 * fetchInterests
 * 
 * This function calls the interests table to fetch the id, interest_name, and user_id 
 * for the specified userId.
 * 
 * The fetched data is used by home.js in the viewSameInterests function to 
 * populate a list of the interests associated with the logged-in user.
 */



function fetchInterests($userId, $conn) {
    $query = "SELECT id, interest_name, user_id FROM interests WHERE user_id = '$userId'";
    error_log("Executing query: $query"); // Log the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    $interests = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $interests[] = $row;
    }
    return ['interests' => $interests];
}

/**
 * fetchUsersWithInterest
 * 
 * This function takes the userId and interestName that are passed from home.js FetchUsersWithInterests.
 * It executes a SELECT statement to fetch all users who have the specified interest 
 * and are not the logged-in user.
 * 
 * The function retrieves the user data by joining the users and interests tables.
 * 
 * The fetched data is used to display the users with the selected interest in home.js.
 */

function fetchUsersWithInterest($interestName, $userId, $conn) {
     $query = " SELECT u.id, u.username, i.interest_name FROM users u JOIN interests i ON u.id = i.user_id WHERE i.interest_name = '$interestName' AND u.id != '$userId' "; 
     $result = mysqli_query($conn, $query); 
     if (!$result) { return ['error' => 'Query Failed: ' . mysqli_error($conn)]; }
      $users = []; while ($row = mysqli_fetch_assoc($result)) { $users[] = $row; } return ['users' => $users]; }

    /**
 * updateInterest
 * 
 * This function is used to update the interests for the specified user.
 * The profile tab on index.php uses the UpdateDeleteInterests function in interests.js 
 * to create a table of all the interests available for the user. 
 * When a user clicks on update in the table created by UpdateDeleteInterests, 
 * it sends the interest name, id, and userId to updateInterests in interests.js.
 * 
 * updateInterests in interests.js then calls the updateInterest function in update_interest.php 
 * to update the interests table with the changes made.
 */

function updateInterest($interestId, $userId, $interestName, $conn) {
    $query = "UPDATE interests SET interest_name='$interestName' WHERE id='$interestId' AND user_id='$userId'";
    error_log("Executing update query: $query"); // Log the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Update query failed: " . mysqli_error($conn)); // Log the error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    return ['success' => true, 'message' => 'Interest updated successfully'];
}

$response = [];
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    if ($userId === 0) {
        $response = ['error' => 'Invalid user ID'];
    } else {
        error_log("Fetching interests for user ID: $userId"); // Log the user ID being fetched
        $response = fetchInterests($userId, $conn);
    }
}elseif (isset($_GET['interest_name'])) { $interestName = $_GET['interest_name']; if (isset($_SESSION['user_id'])) { $userId = $_SESSION['user_id']; 
    $response = fetchUsersWithInterest($interestName, $userId, $conn); } else { $response = ['error' => 'User not logged in']; }}
if (isset($_POST['interest_id']) && isset($_POST['user_id']) && isset($_POST['interest_name'])) {
    $interestId = intval($_POST['interest_id']);
    $userId = intval($_POST['user_id']);
    $interestName = trim($_POST['interest_name']);
    if ($interestId === 0 || $userId === 0 || empty($interestName)) {
        $response = ['error' => 'Invalid input data'];
    } else {
        error_log("Updating interest ID: $interestId for user ID: $userId with name: $interestName"); // Log the update details
        $response = updateInterest($interestId, $userId, $interestName, $conn);
    }
}

echo json_encode($response);

