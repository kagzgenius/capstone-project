/**
 * load.js
 * This script dynamically manages the navigation bar and page content 
 * for Home, Chat, Profile, and Admin sections.
 * 
 * Functions include:
 * 
 * 1. `loadPage`: 
 *    This function is called when a navigation item is clicked. It receives 
 *    the `page` variable and uses an if-else statement to determine which 
 *    content to load based on the clicked item (Home, Chat, Profile, or Admin).
 *    Depending on the selection, it displays a corresponding message and 
 *    populates the `dynamic-content` div.
 * 
 * 2. (Additional functions can be described here if applicable)
 */



function loadPage(page) {
    const dynamicContent = document.getElementById('dynamic-content');
    const sidebarContent = document.getElementById('sidebar-content');
    /**
 * Loads the content for the home page.
 * When the home navigation item is clicked, the message 
 * "Welcome to the homepage!" is displayed in the `dynamic-content` div.
 * 
 * Additional features and content updates for the home page
 * will be implemented in the future.
 */
    if (page === 'home') {
        dynamicContent.innerHTML = '<h1>Welcome Home!</h1><p>This is the home page content.</p>';
        sidebarContent.innerHTML = '';

        /**
 * Loads the content for the chat page.
 * 
 * When the chat page is accessed, the following actions occur:
 * 1. The profile details of the signed-in user are displayed in the `profile-details` div.
 *    This is managed by the `loadSessionProfile.js` script.
 * 2. A scrollable list of users from the profile table appears in the navigation bar.
 * 3. When a user is clicked from this list, the `loadUserContactList.js` function is called, 
 *    passing the selected user's details to the `chat-window` div.
 * 
 * The chat window will display:
 * - The signed-in user and the selected user.
 * - If messages exist between the two users, those messages will be fetched from the message 
 *   database using the sender_id (the signed-in user) and the receiver_id (the selected user).
 * - If messages are found, they will be displayed in the `chat-window` div.
 * - If no messages exist, an empty message will be shown along with a text box 
 *   and a send button for the user to initiate a conversation.
 */

    } else if (page === 'chat') {
        dynamicContent.innerHTML = '<h2>Chat Page</h2><div id="profile-details">Profile details will load here...</div><div id="chat-window">Chat window will load here...</div>';
        sidebarContent.innerHTML = `
            <div class="nav-bar">
                <h3>Chat Page</h3>
                <h4>User Contacts List</h4>
                <div class="user-contacts-list"></div>
            </div>
        `;
        loadUserContactList();
        loadSessionProfile();
        /**
 * Loads the content for the profile page.
 * 
 * When the profile page is accessed, the following actions occur:
 * 1. The signed-in user's profile details are loaded using the `loadSessionProfile.js` function.
 * 2. A navigation bar is displayed in the `nav-bar` div, which is populated by the 
 *    `loadProfileNavBar.js` function. This navigation bar includes options for:
 *    - Experience
 *    - Education
 *    - Skills
 *    - Interests
 * 3. Depending on the selected option from the navigation bar, users can insert, update, 
 *    or delete records from the respective database tables (experience, education, skills, interests).
 * 4. As the user selects any of these options, the corresponding forms and tables will be displayed 
 *    in the `dynamic-content` window for easy interaction and data management.
 */

    } else if (page === 'profile') {
        dynamicContent.innerHTML = '<h2>Profile Page</h2><div id="profile-details">Profile details will load here...</div>';
        sidebarContent.innerHTML = `
            <div class="nav-bar">
                <h3>Profile Page</h3>
                
            </div>
        `;
        
        loadSessionProfile();
        loadProfileNavBar();

        /**
 * Loads the content for the admin page.
 * 
 * When the admin page is accessed, the following actions occur:
 * 1. The signed-in user's profile details are loaded using the `loadSessionProfile.js` function.
 * 2. The navigation bar on the admin page is populated with the `loadAdminNavBar.js` function.
 * 3. The navigation bar includes a button labeled "List Users." When clicked, this button:
 *    - Retrieves data from the profile database table, listing all users.
 *    - Generates a clickable list of all users.
 * 4. When an admin clicks on a user from this list, the corresponding user data from both 
 *    the user table and the profile table is displayed in a designated area.
 * 5. An "Edit Profile" button is available for each user, which, when clicked:
 *    - Populates a form with the user's details, allowing the admin to edit specific fields.
 *    - After making changes, the admin can submit the form to update both the user 
 *      and profile database tables.
 */

    } else if (page === 'admin') {
        dynamicContent.innerHTML = '<h2>Admin Page</h2><div id="profile-details">Profile details will load here...</div>';
        sidebarContent.innerHTML = `
            <div class="nav-bar">
                <h3>Admin Page</h3>
                <div id = "admin-navbar" ></div>
                
            </div>
        `;
        
        loadSessionProfile();
        loadAdminNavBar();
    }
}



