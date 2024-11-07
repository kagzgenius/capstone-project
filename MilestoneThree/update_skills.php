
     <?php
     
     /**
      * update_skills.php
      * This script processes updates to a user's skills. It is called when the 
      * `updateSkills` function in `skills.js` is executed.
      * 
      * Functionality includes:
      * 
      * 1. Retrieving parameters:
      *    The script receives the `skill_id`, `user_id`, and the updated skill data 
      *    via GET or POST request parameters to identify the specific skill record 
      *    to be updated.
      * 
      * 2. Database connection:
      *    It establishes a connection to the database using the `database.php` file 
      *    to perform the update operation.
      * 
      * 3. Updating the record:
      *    The script constructs an SQL UPDATE statement to modify the record in the 
      *    skills table based on the provided `skill_id`, applying the new skill data 
      *    while ensuring it corresponds to the specified `user_id`.
      * 
      * 4. (Optional: Error handling and confirmation messages can be described here if applicable)
      */
     
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

// Function to fetch skills
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
}function fetchUsersWithSkill($skillName, $userId, $conn) {
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

