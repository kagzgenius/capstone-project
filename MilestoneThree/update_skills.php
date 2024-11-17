
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
 * update_skills.php
 * 
 * This script processes updates to a user's skills and fetches skills data. It is called when 
 * the `updateSkills` function in `skills.js` is executed.
 * 
 * Functionality includes:
 * 
 * 1. fetchSkills:
 *    This function calls the skills table to fetch the id, skill_name, and user_id 
 *    for the specified userId. The fetched data is used by home.js in the viewSameSkills function to 
 *    populate a list of the skills associated with the logged-in user.
 * 
 * 2. fetchUsersWithSkills:
 *    This function takes the userId and skillName that are passed from home.js FetchUsersWithSkills.
 *    It executes a SELECT statement to fetch all users who have the specified skill 
 *    and are not the logged-in user. The function retrieves the user data by joining the users and skills tables.
 *    The fetched data is used to display the users with the selected skill in home.js.
 * 
 * 3. updateSkill:
 *    This function is used to update the skills for the specified user.
 *    The profile tab on index.php uses the UpdateDeleteSkills function in skills.js 
 *    to create a table of all the skills available for the user. When a user clicks on update in the table 
 *    created by UpdateDeleteSkills, it sends the skill name, id, and userId to updateSkills in skills.js.
 *    updateSkills in skills.js then calls the updateSkill function in update_skills.php 
 *    to update the skills table with the changes made.
 * 
 * The script performs the following steps:
 * - Checks if the necessary parameters (`skill_id`, `user_id`, and updated skill data) are provided via GET or POST requests.
 * - Establishes a connection to the database using the `database.php` file to perform the update operation.
 * - Constructs an SQL UPDATE statement to modify the record in the skills table based on the provided `skill_id`, applying the new skill data while ensuring it corresponds to the specified `user_id`.
 * - Handles any errors that occur during the update process and provides confirmation messages upon successful completion of the update.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

/**
 * fetchSkills
 * 
 * This function calls the skills table to fetch the id, skill_name, and user_id 
 * for the specified userId.
 * 
 * The fetched data is used by home.js in the viewSameSkills function to 
 * populate a list of the skills associated with the logged-in user.
 */

/**
 * fetchUsersWithSkills
 * 
 * This function takes the userId and skillName that are passed from home.js FetchUsersWithSkills.
 * It executes a SELECT statement to fetch all users who have the specified skill 
 * and are not the logged-in user.
 * 
 * The function retrieves the user data by joining the users and skills tables.
 * 
 * The fetched data is used to display the users with the selected skill in home.js.
 */

function fetchSkills($userId, $conn) {
    $query = "SELECT id, skill_name, user_id FROM skills WHERE user_id = '$userId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $skills = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $skills[] = $row;
    }
    return ['skills' => $skills];
}

function fetchUsersWithSkill($skillName, $userId, $conn) {
    $query = "
        SELECT u.id, u.username, s.skill_name
        FROM users u
        JOIN skills s ON u.id = s.user_id
        WHERE s.skill_name = '$skillName'
        AND u.id != '$userId'
    ";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    return ['users' => $users];
}

/**
 * updateSkill
 * 
 * This function is used to update the skills for the specified user.
 * The profile tab on index.php uses the UpdateDeleteSkills function in skills.js 
 * to create a table of all the skills available for the user. 
 * When a user clicks on update in the table created by UpdateDeleteSkills, 
 * it sends the skill name, id, and userId to updateSkills in skills.js.
 * 
 * updateSkills in skills.js then calls the updateSkill function in update_skills.php 
 * to update the skills table with the changes made.
 */

function updateSkill($skillId, $userId, $skillName, $conn) {
    $query = "UPDATE skills SET skill_name='$skillName' WHERE id='$skillId' AND user_id='$userId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    return ['success' => true, 'message' => 'Skill updated successfully'];
}

$response = [];
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    if ($userId === 0) {
        $response = ['error' => 'Invalid user ID'];
    } else {
        $response = fetchSkills($userId, $conn);
    }
}elseif (isset($_GET['skill_name'])) { $skillName = $_GET['skill_name']; $userId = $_SESSION['user_id'];
    $response = fetchUsersWithSkill($skillName, $userId, $conn);}
if (isset($_POST['skill_id']) && isset($_POST['user_id']) && isset($_POST['skill_name'])) {
    $skillId = intval($_POST['skill_id']);
    $userId = intval($_POST['user_id']);
    $skillName = trim($_POST['skill_name']);
    if ($skillId === 0 || $userId === 0 || empty($skillName)) {
        $response = ['error' => 'Invalid input data'];
    } else {
        $response = updateSkill($skillId, $userId, $skillName, $conn);
    }
}

echo json_encode($response);

