<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php'; //database connection
if (!isset($_SESSION['user_id'])) { //check if the user_id is valid and if not send to login
    header('Location: login.php');
    exit();
}
$userid = $_SESSION['user_id']; //loads user_id
$adminFlag = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false; // controls if the user is active admin and show if is_admin is 1

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <title>My Website</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
   
</head>
<style>.user-profile-details{height: 30%;}.user-profile{height:60%;}.user-contacts-list{height: 30%;};</style>
<body>
    <header class="left-nav">
        <nav>
            <ul>
                <li><a href="#" onclick="loadPage('home')">Home</a></li>
                <li><a href="#" onclick="loadPage('chat')">Chat</a></li>
                <li><a href="#" onclick="loadPage('profile')">Profile</a></li>
                <li><a href="#" onclick="loadPage('admin')" style="<?php echo $adminFlag ? '' : 'display:none;'; ?>">Admin</a></li> <!--checks if is_admin is 1 or 0 and loads if they are an admin-->
                <li><a href="#" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-content">
    <aside class="right-nav">
        <div class="user-profile">
            <div class="user-profile-image"></div>
          
       
            <div class="user-profile-details"></div>
        </div>
       
        <div id="sidebar-content"></div>
    </aside>
    <div id="dynamic-content">
        <h1>Welcome!</h1>
        <p>Select a page to get started.</p>
    </div>
</div>

<script>


function experience() { // gets called by experience.js to load insertIntoExperience
    insertIntoExperience();
   
}
function skills() { //gets called by skills.js to load insertIntoSkills
   
   insertIntoSkills();
   
   
   
}



const userId = <?php echo json_encode($_SESSION['user_id']); ?>;/** This is used by the load.js, profile.js, admin.js to get the user_id from the session variable */



</script>
<script src="load.js"></script> <!-- 
This script dynamically updates the navigation bar based on user interaction. 
It handles the following sections: Home, Chat, Profile, and Admin. 
When a user clicks on a navigation item, the `loadPage` function is called, 
which retrieves the corresponding content and displays it in the `dynamic-content` div.
-->
<script src="profile.js"></script><!--/**
 * profile.js
 * This script contains various functions to manage user profiles and related functionalities.
 * 
 * Functions include:
 * 
 * 1. `loadProfileNavBar`: 
 *    Loads a navigation bar featuring options for Education, Experience, Skills, 
 *    and Interests. Each option calls its respective execution functions to load 
 *    the associated tables and forms.
 * 
 * 2. `loadSessionProfile`: 
 *    Retrieves and displays the signed-in user's details, including username, 
 *    job title, first name, and last name.
 * 
 * 3. `loadUserProfile`: 
 *    Accesses the profile table to load and display the user's contact list for the 
 *    messaging system. This uses loadUserContactdetail also with chat.js. 
 * 
 * 4. `viewUsersProfile`: 
 *    Loads the profile list for the admin page, allowing admins to view all user profiles.
 * 
 * 5. `loadUserProfileDetails`: 
 *    Creates and populates a table for the admin page that displays detailed user information.
 * 
 * 6. `editUserProfile`: 
 *    Enables the admin to edit user profiles by populating a form with the user's details,
 *    allowing for updates to be made in the user profile.
 */-->

<script src="chat.js"></script><!--/**
 * chat.js
 * This script manages chat functionalities, including loading contact lists and sending messages.
 * 
 * Functions include:
 * 
 * 1. `loadUserContactList`: 
 *    This function retrieves the user's contact list by calling `load-contact-list.php`.
 *    It fetches user IDs from the user and profile database tables and displays them as clickable entries.
 *    When a contact is clicked, the corresponding user ID is sent to the `loadUserContactList` function.
 * 
 * 2. `loadContactDetails`: 
 *    Called by `loadUserContactList`, this function checks the message table to find existing messages 
 *    between the logged-in user (sender_id) and the selected contact (receiver_id). 
 *    It displays the message history in the chat window.
 * 
 * 3. `sendMessage`: 
 *    This function accepts a user ID as a parameter and uses `send_message.php` to send a message 
 *    to the specified receiver ID from the selected contact.
 */
-->
<script src="admin.js"></script><!--/**
 * admin.js
 * This script manages administrative functionalities, including loading user profiles 
 * and editing user information.
 * 
 * Functions include:
 * 
 * 1. `loadAdminNavBar`: 
 *    Loads the navigation bar for the admin page, which utilizes the `viewUserProfile` 
 *    function from `profile.js` to display user information.
 * 
 * 2. `viewUserProfile`: 
 *    This function calls `load-contact-list.php` to retrieve data from the user and 
 *    profile tables. It displays the user's image, first name, and last name as clickable entries.
 *    When a user is clicked, it retrieves the corresponding user ID and sends it to the 
 *    `loadUserProfileDetails` function.
 * 
 * 3. `loadUserProfileDetails`: 
 *    This function creates and displays a table containing all relevant user and profile 
 *    data for the selected user ID.
 * 
 * 4. `editUserProfile`: 
 *    When the "Edit Profile" button is clicked, this function retrieves the user ID 
 *    and passes it to `editUserProfile`. 
 *    This function creates a form pre-populated with the user's details, allowing the admin 
 *    to edit and update the information in both the user and profile database tables.
 */
-->
<script src="skills.js"></script><!--/**
 * skills.js
 * This script is utilized by the `profile.js` page when the `loadProfileNavBar` 
 * function is called. It manages user skills, including displaying, adding, 
 * updating, and deleting skills.
 * 
 * Functions include:
 * 
 * 1. `loadSkills`: 
 *    Called by the navigation bar, this function loads the insert form for adding new skills 
 *    and populates a table with the current skills listed for the signed-in user.
 * 
 * 2. `insertIntoSkills`: 
 *    This function retrieves the user ID of the signed-in user and displays a form that allows 
 *    the user to add a new skill. Upon submission, it calls `insert_data.php`, which inserts 
 *    the new skill into the skills table in the database based on the user ID.
 * 
 * 3. `updateDeleteSkills`: 
 *    This function takes the user ID as a parameter and calls `update_skills.php`. It performs 
 *    a select statement to retrieve the skills associated with the user ID, displaying them 
 *    in a table with options to update or delete each skill.
 * 
 * 4. `updateSkills`: 
 *    When the update button is clicked, this function is called with the skill ID, user ID, 
 *    and skill name. It populates a form with the existing skill data, allowing the user to 
 *    edit the information. Upon submission, the updated data is sent back to `update_skills.php` 
 *    to update the database.
 * 
 * 5. `deleteSkills`: 
 *    When the delete button is clicked, this function prompts the user for confirmation. 
 *    If confirmed, it calls `delete_skills.php` to remove the record from the database.
 */
-->
<script src="interests.js"></script><!--/**
 * interests.js
 * This script is loaded when a user navigates to the profile page and clicks on Interests. 
 * It is invoked by the `loadProfileNavBar` function and manages user interests, including 
 * adding, updating, and deleting interests.
 * 
 * Functions include:
 * 
 * 1. `loadInterests`: 
 *    This function is called by the navigation bar and initializes the process for managing 
 *    user interests.
 * 
 * 2. `insertIntoInterests`: 
 *    This function displays a form for the signed-in user to add new interests. It retrieves 
 *    the user ID of the signed-in user and, upon submission, calls `insert_data.php`, passing 
 *    the form data to insert the new interest into the interests table.
 * 
 * 3. `updateDeleteInterests`: 
 *    This function accepts the user ID as a parameter and calls `update_interest.php`. It 
 *    performs a select statement to retrieve interests associated with the user ID and displays 
 *    them in a table with options to update or delete each interest.
 * 
 * 4. `updateInterest`: 
 *    When the update button is clicked, this function is called with the user ID, interest ID, 
 *    and interest name. It populates a form with the existing interest data, allowing the user 
 *    to edit the information. Upon submission, the updated data is sent back to `update_interest.php` 
 *    to update the database.
 * 
 * 5. `deleteInterest`: 
 *    When the delete button is clicked, this function prompts the user for confirmation. If 
 *    confirmed, it calls `delete_interest.php` to remove the record from the database.
 */
-->
<script src="experience.js"></script><!--/**
 * experience.js
 * This script is called when a user navigates to the profile page and uses the 
 * `loadProfileNavBar`. It manages user experience entries, including adding, 
 * updating, and deleting experience data.
 * 
 * Functions include:
 * 
 * 1. `loadExperience`: 
 *    This function is invoked by the navigation bar and initializes the process 
 *    for managing user experience entries.
 * 
 * 2. `insertIntoExperience`: 
 *    This function displays a form pre-filled with the user ID and fields from the 
 *    experience database table, allowing the user to add new experience data. 
 *    Upon submission, it calls `insert_data.php`, which inserts the new experience 
 *    entry into the experience table.
 * 
 * 3. `updateDeleteExperience`: 
 *    This function retrieves experience data by calling `update_experience.php`, 
 *    performing a select statement to load all entries from the experience table. 
 *    It presents the data in an HTML table with options to update or delete each entry.
 * 
 * 4. `updateExperience`: 
 *    When the update button is clicked, this function is called with the experience ID, 
 *    user ID, job name, title, and years worked. It populates a form with the existing 
 *    experience data, allowing the user to edit the fields. Upon saving, the updated 
 *    data is sent back to `update_experience.php` to update the experience table.
 * 
 * 5. `deleteExperience`: 
 *    When the delete button is clicked, this function prompts the user for confirmation. 
 *    If confirmed, it calls `delete_experience.php` to remove the record from the database.
 */
-->
<script src="education.js"></script><!--/**
 * education.js
 * This script is called when a user navigates to the profile page and uses the 
 * `loadProfileNavBar`. It manages user education entries, including adding, 
 * updating, and deleting education data.
 * 
 * Functions include:
 * 
 * 1. `loadEducation`: 
 *    This function is invoked by the navigation bar and initializes the process 
 *    for managing user education entries.
 * 
 * 2. `insertIntoEducation`: 
 *    This function displays a form pre-filled with the user ID and fields from the 
 *    education database table, allowing the user to add new education data. 
 *    Upon submission, it calls `insert_data.php`, which inserts the new education 
 *    entry into the education table.
 * 
 * 3. `updateDeleteEducation`: 
 *    This function retrieves education data by calling `update_education.php`, 
 *    performing a select statement to load all entries from the education table. 
 *    It presents the data in an HTML table with options to update or delete each entry.
 * 
 * 4. `updateEducation`: 
 *    When the update button is clicked, this function is called with the education ID, 
 *    user ID, institution name, degree, and years attended. It populates a form with 
 *    the existing education data, allowing the user to edit the fields. Upon saving, 
 *    the updated data is sent back to `update_education.php` to update the education table.
 * 
 * 5. `deleteEducation`: 
 *    When the delete button is clicked, this function prompts the user for confirmation. 
 *    If confirmed, it calls `delete_education.php` to remove the record from the database.
 */
-->
<script src="logout.js"></script><!--/**
 * logout.js
 * This script manages the logout process for the user.
 * 
 * When executed, it calls `logout.php`, which handles the 
 * logout functionality by closing the user session and 
 * redirecting them to the appropriate page.
 */
-->

</body>
</html>