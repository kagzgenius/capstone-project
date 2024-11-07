/**
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
 *    and passes it to `editUserProfile`. This function creates a form pre-populated 
 *    with the user's details, allowing the admin to edit and update the information 
 *    in both the user and profile database tables.
 */


function loadAdminNavBar() {
    const sidebarContent = document.getElementById('sidebar-content');
    sidebarContent.innerHTML = `
        <div class="nav-bar">
            <h3>Admin Page</h3>
            <ul>
                <li><a href="#" onclick="viewUsersProfile()">List Users</a></li>
               
            </ul>
        </div>
    `;
}






function editUsersProfile() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = '<h2>Edit Users Profile</h2><p>Content for editing users profile goes here...</p>';
    // Additional functionality can be added here
}

