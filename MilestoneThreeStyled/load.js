/**
 * <!--
 * Creator: Zach Fordahl
 * Date: 11/12/2024
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
 *
 */



function loadPage(page) {
    const dynamicContent = document.getElementById('dynamic-content');
    const sidebarContent = document.getElementById('sidebar-content');
    console.log(`Loading page: ${page}`);
    /**
 * Loads the content for the home page.
 * When the home navigation item is clicked, the message
 * "Welcome to the homepage!" is displayed in the `dynamic-content` div.
 *
 * Additional features and content updates for the home page
 * will be implemented in the future.
 */
    if (page === 'home') {
        console.log('Home page logic start');
        dynamicContent.innerHTML = '<h2>Home Page</h2><div class="homepage-details">Home page details goes here....</div>';
        console.log(dynamicContent.innerHTML);
        sidebarContent.innerHTML = `<div class="nav-bar"><div id="home-navbar"></div></div>`;
        console.log('Sidebar content updated');
        loadSessionProfile(); loadHomeNavBar();
        console.log('Home page logic end');
    }

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

     else if (page === 'chat') {
        dynamicContent.innerHTML = '<h2>Chat Page</h2><div id="profile-details">Profile details will load here...</div><div id="chat-window">Chat window will load here...</div>';
        sidebarContent.innerHTML = `
            <div class="nav-bar">

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
                
                <div id = "admin-navbar" ></div>

            </div>
        `;

        loadSessionProfile();
        loadAdminNavBar();
    }
}
     /**
 * loadUserContactList
 *
 * This function calls load-contact-list.php to create a list of all the users
 * in the profile page that are clickable.
 *
 * 1. Fetching User Contacts:
 *    It makes a fetch request to load-contact-list.php to retrieve the list of users
 *    from the profile table.
 *
 * 2. Populating the Contact List:
 *    The function populates the contact list with user IDs, first names, and last names,
 *    making each entry clickable.
 *
 * 3. Handling User Selection:
 *    When a user clicks on a contact, it sends the userId of the selected person to the loadContactDetails function.
 */

function loadUserContactList() {
    const userContactList = document.querySelector('.user-contacts-list');
    fetch('load-contact-list.php')
        .then(response => response.json())
        .then(data => {
            if (data && !data.error) {
                userContactList.innerHTML = data.map(user => `
                    <div class="contact-container" data-user-id="${user.user_id}">
                        <div class="contact-item-image">
                            <img src="${user.profile_pic ? user.profile_pic : 'uploads/default.png'}" alt="Profile Picture">
                        </div>
                        <div class="contact-item">
                            ${user.first_name} ${user.last_name}
                        </div>
                    </div>
                `).join('');

                // Add click event listeners to each contact container
                document.querySelectorAll('.contact-container').forEach(container => {
                    container.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        console.log(`Contact item clicked. User ID: ${userId}`); // Log the user ID
                        loadContactDetails(userId); // Load contact details into dynamic-content
                    });
                });
            } else {
                userContactList.innerHTML = '<p>User contact not found.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching contact:', error);
        });
}

/**
 * loadContactDetails
 *
 * This function is called when a user clicks on a contact from the list generated by loadUserContactList.
 *
 * 1. Fetching Messages:
 *    It calls get_message.php with the selected user's userId as receiverID and the logged-in user's userId as senderID.
 *
 * 2. Displaying Messages:
 *    The retrieved messages are displayed in a chat window with the sender's name, timestamp, and message content. The chat window is scrollable.
 *
 * 3. Creating a Form for Sending Messages:
 *    The function also creates a form that the user can use to send messages.
 */





function loadContactDetails(userId) {
    const dynamicContent = document.getElementById('dynamic-content');

    var xhr = new XMLHttpRequest();
    xhr.open("GET", `get_messages.php?receiver=${userId}`, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log(data); // Log the data for debugging

            const currentUserId = "<?php echo $_SESSION['user_id']; ?>"; // Get the session user ID

            // Set the receiver's name
            let receiverName = 'Unknown';
            if (data.length > 0) {
                // Extract the receiver's name from the first message
                const firstMessage = data[0];
                receiverName = firstMessage.sender == currentUserId
                    ? `${firstMessage.receiver_first_name} ${firstMessage.receiver_last_name}`
                    : `${firstMessage.sender_first_name} ${firstMessage.sender_last_name}`;
            }

            // Build the chat window content
            let chatContent = `
                <h2>Chat with ${receiverName}</h2>
                <div class="chat-window">
                    ${data.map(message => {
                        const senderName = message.sender == currentUserId
                            ? 'Me'
                            : `${message.sender_first_name || 'Unknown Sender'} ${message.sender_last_name || ''}`;
                        return `
                            <div class="chat-message">
                                <p><strong>${senderName}:</strong> ${message.message || 'No message content'}</p>
                                <span class="timestamp">${message.timestamp || ''}</span>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;

            // Always show the message input form
            chatContent += `
                <div id="message-input-container">
                    <form id="message-form">
                        <input type="text" id="message-input" placeholder="Type your message here..." required>
                        <button type="submit">Send</button>
                    </form>
                </div>
            `;

            // Update the dynamic content
            dynamicContent.innerHTML = chatContent;

            // Initialize the message sending function
            sendMessage(userId);

        } else {
            console.error('Error fetching contact details:', this.statusText);
        }
    };

    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
}

/**
 * sendMessage
 *
 * This function is used to send a message typed by the user in the chat form.
 *
 * 1. Handling Form Submission:
 *    When the user types a message in the form and hits submit, the function collects the message data.
 *
 * 2. Calling send_message.php:
 *    The function calls send_message.php to update the message table with the new message.
 *
 * 3. Refreshing the Chat Window:
 *    After sending the message, the function calls loadContactDetails to refresh the chat window and show the newly sent message.
 */



function sendMessage(userId) {
    const messageForm = document.getElementById('message-form');
    messageForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value;
        console.log('Sending message:', message); // Log the message being sent
        // Send the message via AJAX
        var sendAjax = new XMLHttpRequest();
        sendAjax.open("POST", "send_message.php", true);
        sendAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        sendAjax.send(`receiver=${userId}&message=${encodeURIComponent(message)}`);
        sendAjax.onreadystatechange = function() {
            if (this.readyState === 4) {
                console.log('Response from server:', this.responseText); // Log the server response
                if (this.status === 200) {
                    console.log('Message sent successfully');
                    messageInput.value = ''; // Clear the input field

                    loadContactDetails(userId); // Reload chat to show new message


                } else {
                    console.error('Error sending message:', this.statusText);
                }
            }
        };
    });
}
