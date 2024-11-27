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
     
 * get_user_profile.php
 * 
 * This script retrieves user profile data from the database based on the provided user ID.
 * It fetches data from multiple tables including users, profile, skills, education, interests, and experience.
 * The data is returned in JSON format to be used in constructing and displaying user profiles on the home page.
 * 
 * The script includes the following steps:
 * 1. Check if the user ID is provided in the request.
 * 2. Execute an SQL query to retrieve user profile data.
 * 3. Fetch and return the user profile data in JSON format.
 * 
 * home.js uses the function `buildUserProfile` to fetch the profile data from this script and display it 
 * in a structured table format, allowing the user to view the selected user's details.
 */

include 'database.php'; // Include your database connection file

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'User ID not provided']);
    exit();
}

$user_id = $_GET['user_id'];

$query = "
    SELECT 
        u.username, 
        p.first_name, p.last_name, p.bio, p.job_title, p.address, p.profile_pic, p.cover_pic, p.phone_number,
        GROUP_CONCAT(DISTINCT e.job_name, ', ', e.job_title, ', ', e.years_worked SEPARATOR '|') AS experience,
        GROUP_CONCAT(DISTINCT s.skill_name SEPARATOR ', ') AS skills,
        GROUP_CONCAT(DISTINCT i.interest_name SEPARATOR ', ') AS interests,
        GROUP_CONCAT(DISTINCT ed.school_name, ', ', ed.degree_obtained, ', ', ed.years_attended SEPARATOR '|') AS education
    FROM users u
    LEFT JOIN profile p ON u.id = p.user_id
    LEFT JOIN experience e ON u.id = e.user_id
    LEFT JOIN skills s ON u.id = s.user_id
    LEFT JOIN interests i ON u.id = i.user_id
    LEFT JOIN education ed ON u.id = ed.user_id
    WHERE u.id = ?
    GROUP BY u.id, u.username, p.first_name, p.last_name, p.bio, p.job_title, p.address, p.profile_pic, p.cover_pic, p.phone_number
";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die(json_encode(['error' => 'Error preparing query: ' . htmlspecialchars($conn->error)]));
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();

if (!$user_profile) {
    echo json_encode(['error' => 'User not found']);
    exit();
}

echo json_encode($user_profile);

$stmt->close();
$conn->close();

