<?php

/**
 * 
 *  Creator: Zach Fordahl
 * Date: 9/26/2024
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
 send_message is what the messaging.php uses to allow users to send the message to the message table. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->
 * update_experience.php
 * This script processes updates to a user's experience records. It is called when the 
 * `updateExperience` function in `experience.js` is executed.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `experience_id`, `user_id`, and the updated experience 
 *    data via GET or POST request parameters to identify the specific experience 
 *    record to be updated.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file 
 *    to perform the update operation.
 * 
 * 3. Updating the record:
 *    The script constructs an SQL UPDATE statement to modify the record in the 
 *    experience table based on the provided `experience_id`, applying the new 
 *    experience data while ensuring it corresponds to the specified `user_id`.
 * 
 * 4. (Optional: Error handling and confirmation messages can be described here if applicable)
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
 * fetchExperience
 * 
 * This function takes the userId that was sent by index.php.
 * It executes a SELECT statement to fetch the id, job_title, years_worked, and user_id 
 * from the experience table for the specified userId.
 * 
 * The fetched data is passed to experience.js, where it is used to display all of the experience records 
 * associated with the logged-in user.
 */

function fetchExperiences($userId, $conn) {
    $query = "SELECT id, job_name, job_title, years_worked, user_id FROM experience WHERE user_id = '$userId'";
    error_log("Executing query: $query"); // Log the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    $experiences = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $experiences[] = $row;
    }
    return ['experience' => $experiences];
}

/**
 * updateExperience
 * 
 * This function takes the experienceId, userId, job title, and years worked 
 * that are passed from experience.js updateExperience.
 * It executes an UPDATE statement to update the experience record in the experience table 
 * based on the provided experienceId and userId.
 * 
 * The function updates the experience data with the new values provided by the user.
 * 
 * The updated data is then used to refresh the experience table and show the changes made.
 */

function updateExperience($experienceId, $userId, $jobName, $jobTitle, $yearsWorked, $conn) {
    $query = "UPDATE experience SET job_name='$jobName', job_title='$jobTitle', years_worked='$yearsWorked' WHERE id='$experienceId' AND user_id='$userId'";
    error_log("Executing update query: $query"); // Log the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Update query failed: " . mysqli_error($conn)); // Log the error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    return ['success' => true, 'message' => 'Experience updated successfully'];
}

/**
 * fetchCompanies
 * 
 * This function takes the userId that was sent by index.php.
 * It executes a SELECT statement to fetch the id, job_name AS company_name, and user_id 
 * from the experience table for the specified userId.
 * 
 * The fetched data is passed to home.js, where it is used to display all of the companies 
 * associated with the logged-in user.
 */


function fetchCompanies($userId, $conn) {
    $query = "SELECT id, job_name AS company_name, user_id FROM experience WHERE user_id = '$userId'";
    error_log("Executing fetchCompanies query for user ID: $userId"); // Log user ID
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchCompanies: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $companies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $companies[] = $row;
    }
    error_log("Companies fetched for user ID $userId: " . json_encode($companies)); // Log fetched companies
    return ['companies' => $companies]; // Ensure the key is 'companies'
}

/**
 * fetchUsersWithCompany
 * 
 * This function takes the companyName and userId that are passed from home.js fetchUsersWithCompany.
 * It executes a SELECT statement to fetch the user data when a user clicks on view profile.
 * 
 * The function retrieves all users who have worked at the specified company and are not the logged-in user.
 * It builds the data for these users, including their username, company name, and id.
 * 
 * The fetched data is used to display the users with the selected company.
 */


function fetchUsersWithCompany($companyName, $userId, $conn) {
    error_log("Company name received in fetchUsersWithCompany: $companyName"); // Log the company name received
    $query = "SELECT u.id, u.username, e.job_name AS company_name FROM users u JOIN experience e ON u.id = e.user_id WHERE e.job_name = '$companyName' AND u.id != '$userId'";
    error_log("Executing fetchUsersWithCompany query for company name: $companyName"); // Log query execution
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchUsersWithCompany: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    error_log("Users fetched with company $companyName: " . json_encode($users)); // Log fetched users
    return ['users' => $users];
}

/**
 * fetchTitles
 * 
 * This function takes the userId that was sent by index.php.
 * It executes a SELECT statement to fetch the id, job_title, and user_id 
 * from the experience table for the specified userId.
 * 
 * The fetched data is passed to home.js, where it is used to display all of the job titles 
 * associated with the logged-in user.
 */

function fetchTitles($userId, $conn) {
    $query = "SELECT id, job_title, user_id FROM experience WHERE user_id = '$userId'";
    error_log("Executing fetchTitles query for user ID: $userId"); // Log user ID
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchTitles: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $titles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $titles[] = $row;
    }
    error_log("Titles fetched for user ID $userId: " . json_encode($titles)); // Log fetched titles
    return ['titles' => $titles]; // Correct key to match frontend
}

/**
 * fetchUsersWithTitle
 * 
 * This function takes the titleName and userId that are passed from home.js fetchUsersWithTitle.
 * It executes a SELECT statement to fetch the user data when a user clicks on view profile.
 * 
 * The function retrieves all users who have the specified job title and are not the logged-in user.
 * It builds the data for these users, including their username, job title, and id.
 * 
 * The fetched data is used to display the users with the selected job title.
 */

function fetchUsersWithTitle($titleName, $userId, $conn) {
    error_log("Title name received in fetchUsersWithTitle: $titleName"); // Log the title name received
    $query = "SELECT u.id, u.username, e.job_title FROM users u JOIN experience e ON u.id = e.user_id WHERE e.job_title = '$titleName' AND u.id != '$userId'";
    error_log("Executing fetchUsersWithTitle query for title name: $titleName"); // Log query execution
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchUsersWithTitle: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    error_log("Users fetched with title $titleName: " . json_encode($users)); // Log fetched users
    return ['users' => $users];
}


$response = [];
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    if ($userId === 0) {
        $response = ['error' => 'Invalid user ID'];
    } else {
        error_log("Fetching experiences for user ID: $userId"); // Log the user ID being fetched
        $response = fetchExperiences($userId, $conn);
    }
}elseif (isset($_GET['company_name'])) { 
    $companyName = $_GET['company_name']; error_log("Company name in GET request: $companyName"); // Log the company name in the GET request 
    $userId = $_SESSION['user_id']; // Assuming the user ID is stored in the session 
    error_log("User ID from session: $userId"); // Log the user ID 
    $response = fetchUsersWithCompany($companyName, $userId, $conn); }
    elseif (isset($_GET['title_name'])) { 
        $titleName = $_GET['title_name']; error_log("Title name in GET request: $titleName"); // Log the title name in the GET request 
        $userId = $_SESSION['user_id']; // Assuming the user ID is stored in the session 
        error_log("User ID from session: $userId"); // Log the user ID 
        $response = fetchUsersWithTitle($titleName, $userId, $conn); }

if (isset($_POST['experience_id']) && isset($_POST['user_id']) && isset($_POST['job_name']) && isset($_POST['job_title']) && isset($_POST['years_worked'])) {
    $experienceId = intval($_POST['experience_id']);
    $userId = intval($_POST['user_id']);
    $jobName = trim($_POST['job_name']);
    $jobTitle = trim($_POST['job_title']);
    $yearsWorked = trim($_POST['years_worked']);
    if ($experienceId === 0 || $userId === 0 || empty($jobName) || empty($jobTitle) || empty($yearsWorked)) {
        $response = ['error' => 'Invalid input data'];
    } else {
        error_log("Updating experience ID: $experienceId for user ID: $userId with job name: $jobName, job title: $jobTitle, years worked: $yearsWorked"); // Log the update details
        $response = updateExperience($experienceId, $userId, $jobName, $jobTitle, $yearsWorked, $conn);
    }
}

echo json_encode($response);

