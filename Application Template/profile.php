<!--
 * Creator: Zach Fordahl
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
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->

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
//fetch the data from the database to use for the page
$sql = "SELECT school_name, degree_obtained, years_attended FROM education WHERE user_id = ?";
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

$education = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// Initialize $educationalContent
$educationalContent = "";

// Displaying educational data using $educationalContents
// I used $educationalContents to display the data within the education table.
// This approach was necessary because including a loop within $pageContent
// caused the system to not read the data correctly. 
// Therefore, I handled this outside of the $pageContent variable.
// This method is also applied to the view options for skills, experience, education, and interests.

/////////////////////////////////////////////////////////////////////////////View profile variable to store the data from the database table
// Loop through the education array and append to $educationalContent
foreach ($education as $edu) {
    $educationalContent .= "<br>";
    $educationalContent .= "<p class='profile-school-name'>School Name: " . htmlspecialchars($edu['school_name']) . "</p>";
    $educationalContent .= "<p class='profile-degree-obtained'>Degree Obtained: " . htmlspecialchars($edu['degree_obtained']) . "</p>";
    $educationalContent .= "<p class='profile-degree-year'>Year Attended: " . htmlspecialchars($edu['years_attended']) . "</p>";
    $educationalContent .= "<br>";
}

//fetch the data from the database to use for the page
$sql = "SELECT job_name, job_title, years_worked FROM experience WHERE user_id = ?";
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

$experience = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// Initialize $experienceContent
$experienceContent = "";

// Loop through the education array and append to $educationalContent
// Displaying educational data using $experienceContent
// I used $educationalContents to display the data within the education table.
// This approach was necessary because including a loop within $pageContent
// caused the system to not read the data correctly. 
// Therefore, I handled this outside of the $pageContent variable.
// This method is also applied to the view options for skills, experience, education, and interests.
foreach ($experience as $exp) {
    $experienceContent .= "<br>";
    $experienceContent .= "<p class='profile-experience-jobname' style='font-size: 18px; color: #555; margin: 5px 0; font-weight: bold;'>Job Name: " . htmlspecialchars($exp['job_name']) . "</p>";
    $experienceContent .= "<p class='profile-experience-jobtitle'>Job Title: " . htmlspecialchars($exp['job_title']) . "</p>";
    $experienceContent .= "<p class='profile-experience-year'>Years Worked: " . htmlspecialchars($exp['years_worked']) . "</p>";
    $experienceContent .= "<br>";
}


//fetch the data from the database to use for the page
$sql = "SELECT skill_name FROM skills WHERE user_id = ?";
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

$skills = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$skillsContent = "";

// Loop through the skills array and append to $skillsContent

// Loop through the education array and append to $educationalContent
// Displaying educational data using $experienceContent
// I used $skillsContent to display the data within the education table.
// This approach was necessary because including a loop within $pageContent
// caused the system to not read the data correctly. 
// Therefore, I handled this outside of the $pageContent variable.
// This method is also applied to the view options for skills, experience, education, and interests.
foreach ($skills as $skill) {
    
    $skillsContent .= "<p class='profile-skill-name' style='font-size: 18px; color: #555; margin: 5px 0; font-weight: bold;'>Skill: " . htmlspecialchars($skill['skill_name']) . "</p>";
   
}


//fetch the data from the database to use for the page

$sql = "SELECT interest_name FROM interests WHERE user_id = ?";
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

$interests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$interestsContent = "";

// Loop through the interests array and append to $interestsContent
// Loop through the education array and append to $educationalContent
// Displaying educational data using $experienceContent
// I used $interestsContent to display the data within the education table.
// This approach was necessary because including a loop within $pageContent
// caused the system to not read the data correctly. 
// Therefore, I handled this outside of the $pageContent variable.
// This method is also applied to the view options for skills, experience, education, and interests.

foreach ($interests as $interest) {
   
    $interestsContent .= "<p class='profile-interest-name' style='font-size: 18px; color: #555; margin: 5px 0; font-weight: bold;'>Interest: " . htmlspecialchars($interest['interest_name']) . "</p>";
    
}




//fetch the data from the database to use for the page

$sql = "SELECT interest_name FROM interests WHERE user_id = ?";
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

$interests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Create tables if they don't exist


//Edit............../////////////////////////////////////////////////////////////////////


$sql = "SELECT school_name, degree_obtained, years_attended FROM education WHERE user_id = ?";
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

$education = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Initialize $educationalContent
$educationalContent = "";

// Loop through the education array and append to $educationalContent
foreach ($education as $edu) {
    $educationalContent .= "<br>";
    $educationalContent .= "<p class='profile-school-name'>School Name: " . htmlspecialchars($edu['school_name']) . "</p>";
    $educationalContent .= "<p class='profile-degree-obtained'>Degree Obtained: " . htmlspecialchars($edu['degree_obtained']) . "</p>";
    $educationalContent .= "<p class='profile-degree-year'>Year Attended: " . htmlspecialchars($edu['years_attended']) . "</p>";
    $educationalContent .= "<a href='editEducation.php?id=" . $edu['id'] . "'>Edit</a>";
    $educationalContent .= "<br>";
}


//post method to insert into the education
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['school_name'])) {
    // Prepare and bind for education table
    $stmt = $conn->prepare("INSERT INTO education (user_id, school_name, degree_obtained, years_attended) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $school_name, $degree_obtained, $years_attended);

    foreach ($_POST['school_name'] as $index => $school_name) {
        $degree_obtained = $_POST['degree_obtained'][$index];
        $years_attended = $_POST['years_attended'][$index];
        $user_id = $user['id'];

        if (!$stmt->execute()) {
            handleError($stmt, $conn, 'Inserting into education table');
        }
    }

    $stmt->close();
}
//created the tables
$tables = [
    "experience" => "CREATE TABLE IF NOT EXISTS experience (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_name VARCHAR(100) NOT NULL,
        job_title VARCHAR(100) NOT NULL,
        years_worked VARCHAR(50) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "skills" => "CREATE TABLE IF NOT EXISTS skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        skill_name VARCHAR(100) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "interests" => "CREATE TABLE IF NOT EXISTS interests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        interest_name VARCHAR(100) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "education" => "CREATE TABLE IF NOT EXISTS education (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        school_name VARCHAR(100) NOT NULL,
        degree_obtained VARCHAR(100) NOT NULL,
        years_attended VARCHAR(50) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )"
];

//push the table array to the database to create the tables for education,skills, interest, experience
foreach ($tables as $table => $sql) {
    if ($conn->query($sql) === TRUE) {
        error_log("Table $table created successfully or already exists.");
    } else {
        error_log("Error creating table $table: " . $conn->error);
    }
}

// Check if the form is submitted
//method to insert into the table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind for experience table
    $stmt = $conn->prepare("INSERT INTO experience (user_id, job_name, job_title, years_worked) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $job_name, $job_title, $years_worked);

    // Loop through each entry
    foreach ($_POST['job_name'] as $index => $job_name) {
        $job_title = $_POST['job_title'][$index];
        $years_worked = $_POST['years_worked'][$index];
        $user_id = $user['id']; // Use the actual user ID

        // Execute the statement
        if (!$stmt->execute()) {
            handleError($stmt, $conn, 'Inserting into experience table');
        }
    }

    // Close the statement
    $stmt->close();

    // Prepare and bind for skills table
    $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $skill_name);

    foreach ($_POST['skills'] as $index => $skill_name) {
        $user_id = $user['id']; // Use the actual user ID
        if (!$stmt->execute()) {
            handleError($stmt, $conn, 'Inserting into skills table');
        }
    }

    $stmt->close();

    // Prepare and bind for interests table
    //insert into the interest table
    $stmt = $conn->prepare("INSERT INTO interests (user_id, interest_name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $interest_name);

    foreach ($_POST['interest'] as $index => $interest_name) {
        $user_id = $user['id']; // Use the actual user ID
        if (!$stmt->execute()) {
            handleError($stmt, $conn, 'Inserting into interests table');
        }
    }

    $stmt->close();

    // Prepare and bind for education table
    //insert into the education table
    $stmt = $conn->prepare("INSERT INTO education (user_id, school_name, degree_obtained, years_attended) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $school_name, $degree_obtained, $years_attended);

    foreach ($_POST['school_name'] as $index => $school_name) {
        $degree_obtained = $_POST['degree_obtained'][$index];
        $years_attended = $_POST['years_attended'][$index];
        $user_id = $user['id']; // Use the actual user ID
        if (!$stmt->execute()) {
            handleError($stmt, $conn, 'Inserting into education table');
        }
    }

    $stmt->close();
}

// Close the connection

////////////////////////////////////////////////////////////////////////////////////////////////
$pageTitle = "Profile";
$pageContent = "
<!DOCTYPE html>
<html>
<head>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        /*profile header*/
        .profile-header {
    display: flex;
    align-items: center;
    background: #fff;
    width: 100%;
    height: 300px; /* Increase if you have more profile data */
    position: relative;
    box-shadow: 0px 3px 4px rgba(0, 0, 0, 0.2);
    padding-right: 20px;
}

.profile-image {
    width: 230px;
    height: 230px;
    margin-left: 200px; /* Adjust this value to move the image to the left */
    margin-top: -180px; /* Adjust this value to control how the image is outside the header */
    margin-right: 20px; /* Add this to create padding between the image and profile info */
    position: relative;
    z-index: 1; /* Ensure the image is on top */
}

.profile-image img {
    border-radius: 50%;
    height: 100%;
    width: 100%;
    border: 4px solid #fff;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
}

.profile-nav-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.profile-nav-info .profile-username {
    font-variant: small-caps;
    font-size: 2rem;
    font-family: sans-serif;
    font-weight: bold;
}

.profile-nav-info .profile-title {
    font-size: 1.2rem;
    color: #555;
    margin-bottom: 10px;
}

.profile-nav-info .profile-fname,
.profile-nav-info .profile-lname,
.profile-nav-info .profile-address,
.profile-nav-info .profile-phone,
.profile-nav-info .profile-email,
.profile-nav-info .profile-bio {
    font-weight: bold; /* Corrected typo */
    color: #777;
    margin-bottom: 5px;
}

.nav {
    align-self: flex-end; /* Align the nav to the right */
    margin-top: auto; /* Push the nav to the bottom */
}

.nav ul {
    list-style-type: none;
    padding: 0;
}

.nav ul li {
    display: inline-block;
    margin-right: 10px;
    cursor: pointer;
    background-color: #e40046; /* Red background */
    color: white; /* White text */
    padding: 10px 20px; /* Increase padding for larger buttons */
    font-size: 1.2rem; /* Increase font size */
    border: none; /* Remove default border */
    border-radius: 5px; /* Optional: Add rounded corners */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline from text */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

.nav ul li:hover {
    background-color: #d32f2f; /* Darker red on hover */
}

.profile-body {
    display: none;
}

.profile-body.active {
    display: block;
}
    /* Form styling */
        .profile-skills-form,
        .profile-education-form,
        .profile-experience-form,
        .profile-interest-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-skills-h3,
        .profile-education-h3,
        .profile-interest-h3,
        .profile-experience-h3 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #333;
        }

        .profile-skills-entry,
        .profile-education-entry,
        .profile-interest-entry,
        .profile-experience-entry {
            margin-bottom: 10px;
        }

        .profile-skills-entry input,
        .profile-education-entry input,
        .profile-interest-entry input,
        .profile-experience-entry input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

       /* Specific styles for buttons inside the profile-skills-form */
.profile-skills-form button {
    background-color: #e40046; /* Red background */
    color: white; /* White text */
    padding: 10px 20px; /* Increase padding for larger buttons */
    font-size: 1.2rem; /* Increase font size */
    border: none; /* Remove default border */
    border-radius: 5px; /* Optional: Add rounded corners */
    cursor: pointer; /* Pointer cursor */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

        .profile-skills-form button:hover {
            background-color: #d32f2f;
        }
.profile-body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.profile-username {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.profile-school-name,
.profile-degree-obtained,
.profile-degree-year,
.profile-experience-jobname,
.profile-experience-jobtitle,
.profile-experience-year {
    font-size: 18px;
    color: #555;
    margin: 5px 0;
}

.profile-school-name,
.profile-experience-jobname {
    font-weight: bold;
}

.profile-degree-obtained,
.profile-experience-jobtitle {
    font-style: italic;
}

.profile-degree-year,
.profile-experience-year {
    color: #777;
}




</style> <!-- style -->
</head> <!--head -->
<body> 
<!-- The profile header has an image and then displays the data from the profile and the user table onto the profile card -->
<div class = 'container'>
    <div class='profile-header'>
        <div class = 'profile-image'> <img src='profile pic.png' width='200' alt='profilePic'></div><!-- profile-image -->
             <div class='profile-nav-info'>
            <h3 class='profile-username'>" . htmlspecialchars($user['username']) . "</h3>
            <h4 class='profile-title'>Job Title</h4>
            <p class='profile-fname'>First Name: " . htmlspecialchars($usertwo['first_name']) . "</p>
            <p class='profile-lname'>Last Name: " . htmlspecialchars($usertwo['last_name']) . "</p>
            <p class='profile-address'>Address: address</p>
            <p class='profile-phone'>Phone: 111-111-1111</p>
            <p class='profile-email'>Email: " . htmlspecialchars($user['email']) . "</p>
            <p class='profile-bio'>Bio: " . htmlspecialchars($usertwo['bio']) . "</p>
        </div> <!-- profile-nav-info -->


        
        
       <!-- The first nav bar includes options to View, Edit, and Add Profile. It calls the showSubNav function. -->
       <!-- 
This HTML structure represents a navigation menu system with three main actions: View Profile, Edit Profile, and Add to Profile. Each action triggers a JavaScript function to display the corresponding submenu.

1. Main Navigation Menu:
   - <div class='nav' id='main-nav'>: This is the main navigation menu.
   - <ul>: Contains list items (<li>) for each main action.
   - Each <li> has an onclick event that calls the showSubNav() function with a specific action ('view', 'edit', 'add').

2. Sub Navigation Menus:
   - <div class='nav' id='sub-nav'>: Submenu for adding profile details (Experience, Education, Skills, Interest).
   - <div class='nav' id='sub-nav-two'>: Submenu for editing profile details.
   - <div class='nav' id='sub-nav-three'>: Submenu for viewing profile details.
   - Each submenu contains list items (<li>) with onclick events that call the showTab() function with a specific tab ID or showMainNav() to return to the main menu.

3. JavaScript Functions:
   - hideAllTabs(): Hides all profile tabs by setting their display style to 'none'.
   - showMainNav(): Displays the main navigation menu and hides all submenus.
   - showSubNav(action): Hides the main navigation menu and displays the corresponding submenu based on the action parameter ('add', 'edit', 'view').
   - showTab(tabId): Hides all tabs and then displays the selected tab by setting its display style to 'block'.

How it works:
- When a user clicks on a main menu item (e.g., \"View Profile\"), the showSubNav('view') function is called.
- This function hides the main menu and displays the 'sub-nav-three' submenu.
- Within the submenu, clicking on an item (e.g., \"View Experience\") calls the showTab('view-experience') function.
- The showTab() function hides all tabs and then displays the 'view-experience' tab.
- Clicking \"Back\" in any submenu calls showMainNav(), which hides the submenu and displays the main menu again.
-->


         <div class='nav' id='main-nav'>
         
            <ul>
                <li onclick=\"showSubNav('view')\">View Profile</li>
                <li onclick=\"showSubNav('edit')\">Edit Profile</li>
                <li onclick=\"showSubNav('add')\">Add to Profile</li>
            </ul>
        </div> <!-- main-nav -->
        <!-- -->
        <div class='nav' id='sub-nav' style='display:none'>
    <ul>
        <li onclick=\"showTab('experience')\">Add Experience</li>
        <li onclick=\"showTab('education')\">Add Education</li>
        <li onclick=\"showTab('skills')\">Add Skills</li>
        <li onclick=\"showTab('interest')\">Add Interest</li>
        <li onclick=\"showMainNav()\">Back</li>
    </ul>
</div> <!-- sub-nav -->
    <!-- -->
    <div class=\"nav\" id=\"sub-nav-two\" style=\"display: none;\">
    <ul>
        <li onclick=\"showTab('edit-experience')\">Edit Experience</li>
        <li onclick=\"showTab('edit-education')\">Edit Education</li>
        <li onclick=\"showTab('edit-skills')\">Edit Skills</li>
        <li onclick=\"showTab('edit-interest')\">Edit Interest</li>
        <li onclick=\"showMainNav()\">Back</li>
    </ul>
</div>
   <!-- --> 
<div class=\"nav\" id=\"sub-nav-three\" style=\"display: none;\">
    <ul>
        <li onclick=\"showTab('view-experience')\">View Experience</li>
        <li onclick=\"showTab('view-education')\">View Education</li>
        <li onclick=\"showTab('view-skills')\">View Skills</li>
        <li onclick=\"showTab('view-interest')\">View Interest</li>
        <li onclick=\"showMainNav()\">Back</li>
    </ul>
</div>


    </div><!--profile-header-->
  <!-- The '[]' in the input names (e.g., job_name[], job_title[], years_worked[]) 
  indicates that these fields are part of an array. This is useful when there are multiple entries in a table, 
  as it allows the system to collect and display all the details for each entry. 
  By using arrays, we can handle multiple job experiences efficiently. -->
    <div class='main-body'> 
   <div class='profile-body' id='experience'>
    <h1>Experience</h1>
    <form id='profile-experience-form' method='POST' action='profile.php'>
        <h3 class='profile-experience-h3'>Add Experience</h3>
        <div id='profile-experience-container'>
            <div class='profile-experience-entry'>
                <input name='job_name[]' type='text' placeholder='Job Name'>
                <input name='job_title[]' type='text' placeholder='Job Title'>
                <input name='years_worked[]' type='text' placeholder='Years Worked'>
            </div>
        </div>
        <button type='button' onclick='addExperienceEntry()' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Add Another Job</button>
        <button type='submit' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Submit</button>
    </form>
    

</div>
<!-- 
 This function adds a new experience entry to the profile experience container.
When the button is clicked, it creates a new <div> element with the class 'profile-experience-entry'.
Inside this <div>, it adds three input fields for job name, job title, and years worked.
Finally, it appends this new <div> to the 'profile-experience-container' element.-->
<script>
function addExperienceEntry() {
    const container = document.getElementById('profile-experience-container');
    const newEntry = document.createElement('div');
    newEntry.className = 'profile-experience-entry';
    newEntry.innerHTML = `
        <input name='job_name[]' type='text' placeholder='Job Name'>
        <input name='job_title[]' type='text' placeholder='Job Title'>
        <input name='years_worked[]' type='text' placeholder='Years Worked'>`;
    container.appendChild(newEntry);
}
</script>
<div class='profile-body' id='education'>
    <h1>Education</h1>
    <form id='profile-education-form' method='POST' action='profile.php'>
        <h3 class='profile-education-h3'>Add Education</h3>
        <div id='profile-education-container'>
            <div class='profile-education-entry'>
                <input name='school_name[]' type='text' placeholder='School Name'>
                <input name='degree_obtained[]' type='text' placeholder='Degree Obtained'>
                <input name='years_attended[]' type='text' placeholder='Years Attended'>
            </div>
        </div>
        <button type='button' onclick='addEducationEntry()' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Add Another School</button>
        <button type='submit' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Submit</button>
    </form>
</div>
<!-- 
 This function adds a new experience entry to the profile education container.
When the button is clicked, it creates a new <div> element with the class 'profile-education-entry'.
Inside this <div>, it adds three input fields for job name, job title, and years worked.
Finally, it appends this new <div> to the 'profile-education-container' element.-->
<script>
function addEducationEntry() {
    const container = document.getElementById('profile-education-container');
    const newEntry = document.createElement('div');
    newEntry.className = 'profile-education-entry';
    newEntry.innerHTML = `
        <input name='school_name[]' type='text' placeholder='School Name'>
        <input name='degree_obtained[]' type='text' placeholder='Degree Obtained'>
        <input name='years_attended[]' type='text' placeholder='Years Attended'>`;
    container.appendChild(newEntry);
}
</script>

<div class='profile-body' id='skills'>
    <h1>Skills</h1>
    <form id='profile-skills-form' method='POST' action='profile.php'>
        <h3 class='profile-skills-h3'>Add Skills</h3> <!-- profile-skills-h3 -->
        <div id='profile-skills-container'> 
            <div class='profile-skills-entry'> 
                <input name='skills[]' type='text' placeholder='Add Skill'>
            </div> <!-- profile-skills-entry -->
        </div> <!-- profile-skills-container -->
        <button type='button' onclick='addSkillsEntry()' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Add Another Skill</button>
        <button type='submit' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Submit</button>
    </form> <!-- profile-skills-form -->
    <!-- 
 This function adds a new experience entry to the profile skills container.
When the button is clicked, it creates a new <div> element with the class 'skills-experience-entry'.
Inside this <div>, it adds three input fields for job name, job title, and years worked.
Finally, it appends this new <div> to the 'skills-experience-container' element.-->
    <script>
    function addSkillsEntry() {
        const container = document.getElementById('profile-skills-container');
        const newEntry = document.createElement('div');
        newEntry.className = 'profile-skills-entry';
        newEntry.innerHTML = `
            <input type='text' name='skills[]' placeholder='Add Skill'>`;
        container.appendChild(newEntry);
    }
    </script> <!-- form skills action -->
</div> <!-- profile-body -->

    <!-- -->
    <div class='profile-body' id='interest'>
    <h1>Interest</h1>
    <form id='profile-interest-form' method='POST' action='profile.php'>
        <h3 class='profile-interest-h3'>Add Interest</h3>
        <div id='profile-interest-container'>
            <div class='profile-interest-entry'>
                <input name='interest[]' type='text' placeholder='Interest'>
            </div>
        </div>
        <button type='button' onclick='addInterestEntry()' style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Add Another Interest</button>
        <button type='submit'style='background-color: #e40046; color: white; padding:5px 10px; font-size:1.5rem; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; width 20px; height 20px;'>Submit</button>
    </form>
</div>
<!-- 
 This function adds a new experience entry to the profile interest container.
When the button is clicked, it creates a new <div> element with the class 'skills-interst-entry'.
Inside this <div>, it adds three input fields for job name, job title, and years worked.
Finally, it appends this new <div> to the 'skills-intersts-container' element.-->
<script>
function addInterestEntry() {
    const container = document.getElementById('profile-interest-container');
    const newEntry = document.createElement('div');
    newEntry.className = 'profile-interest-entry';
    newEntry.innerHTML = `<input name='interest[]' type='text' placeholder='Interest'>`;
    container.appendChild(newEntry);
}

</script>

<!-- Edit------------------------------------------------------------->
<div class='profile-body' id='edit-experience'>
    <h3>Experience</h3>


</div>
<!-- -->
<div class='profile-body' id='edit-education'>
    <h3>Education</h3>
    <form action='profile.php' method='post'>
        <label for='school_name'>School Name:</label>
        <input type='text' id='school_name' name='school_name' value='" . htmlspecialchars($edu['school_name']) . "'><br>
        <label for='degree_obtained'>Degree Obtained:</label>
        <input type='text' id='degree_obtained' name='degree_obtained' value='" . htmlspecialchars($edu['degree_obtained']) . "'><br>
        <label for='years_attended'>Years Attended:</label>
        <input type='text' id='years_attended' name='years_attended' value='" . htmlspecialchars($edu['years_attended']) . "'><br>
        <input type='hidden' name='id' value='" . $edu['id'] . "'>
        <input type='submit' value='Update'>
    </form>
</div>
<!-- -->
<div class='profile-body' id='edit-skills'>
    <h3>Skills</h3>
</div>

<div class='profile-body' id= edit-interest'>
    <h3>Interest</h3>
    <?php foreach ($interests as $interest) { echo $interest; } ?>
</div>
<!-- View ------------------------------------------------------------>
<!--Displays the data from the view eperience table -->
<div class='profile-body' id='view-experience' style='display:none'>
    <h3>Experience</h3>
    $experienceContent
</div>


<!--Displays the data from teh view education table -->
<div class='profile-body' id='view-education' style='display:none'>
    <h3>Education</h3>
    $educationalContent
         
</div>
<!--Displays the data from the skills table -->
<div class='profile-body' id='view-skills' style='display:none'>
    <h3>Skills</h3>
    <br>
    $skillsContent
</div>

<div class='profile-body' id='view-interest' style='display:none'>
    <h3>Interest</h3>
    <br>
    $interestsContent
</div>


</div><!--container -->
<!-- Java script to make the nav bars respond correctly-->
<script>
function hideAllTabs() {
    const tabs = document.querySelectorAll('.profile-body');
    tabs.forEach(tab => {
        tab.style.display = 'none';
    });
}

function showMainNav() {
    hideAllTabs(); // Hide all tabs when returning to the main navigation
    document.getElementById('main-nav').style.display = 'flex';
    document.getElementById('sub-nav').style.display = 'none';
    document.getElementById('sub-nav-two').style.display = 'none';
    document.getElementById('sub-nav-three').style.display = 'none';
}

function showSubNav(action) {
    document.getElementById('main-nav').style.display = 'none';
    document.getElementById('sub-nav').style.display = 'none';
    document.getElementById('sub-nav-two').style.display = 'none';
    document.getElementById('sub-nav-three').style.display = 'none';

    if (action === 'add') {
        document.getElementById('sub-nav').style.display = 'flex';
    } else if (action === 'edit') {
        document.getElementById('sub-nav-two').style.display = 'flex';
    } else if (action === 'view') {
        document.getElementById('sub-nav-three').style.display = 'flex';
    } else {
        document.getElementById('main-nav').style.display = 'flex';
    }
}

function showTab(tabId) {
    hideAllTabs(); // Hide all tabs before showing the selected one
    document.getElementById(tabId).style.display = 'block';
}




</script>
</body><!--body -->
</html> <!--html -->
";

include('Application_Template.php');
$conn->close();
