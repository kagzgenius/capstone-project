<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <title>My Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh; /* Make the body full height */
        }

        /* Left Navigation Bar */
        .left-nav {
            background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(112,112,210,1) 35%, rgba(0,212,255,1) 100%);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            width: 250px; /* Width of the left nav */
           
        }

        .left-nav a {
            display: inline-block; /* Use inline-block for button effect */
            padding: 15px 30px; /* Add padding for size */
            margin: 10px; /* Spacing between labels */
            border-radius: 30px; /* Rounded corners */
            background-color: #ecf0f1; /* Button background color */
            color: #2c3e50; /* Darker text color */
            text-align: center; /* Center text */
            cursor: pointer; /* Change cursor to pointer */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6); /* Subtle shadow for depth */
            transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
            text-decoration: none;
        }

        .left-nav a:hover {
            background-color: rgba(51, 240, 255 );
        }
        .left-nav ul{
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
        }

        /* Right Navigation Bar */
        .right-nav {
            background-color: RGB(254, 245, 231);
            color: black;
            padding: 20px;
            display: flex;
            flex-direction: column;
            width: 250px; /* Width of the left nav */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
            text-align: Center;
            
        }

        .right-nav a {
            display: inline-block; /* Use inline-block for button effect */
            padding: 10px 20px; /* Add padding for size */
            margin: 10px; /* Spacing between labels */
            border-radius: 30px; /* Rounded corners */
            background-color: #ecf0f1; /* Button background color */
            color: #2c3e50; /* Darker text color */
            text-align: center; /* Center text */
            cursor: pointer; /* Change cursor to pointer */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6); /* Subtle shadow for depth */
            transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
            text-decoration: none;
        }

        .right-nav a:hover {
            background-color: gray; /* Hover color */
        }

        .right-nav ul{
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
        }

        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: row;
            background: white; /* White background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6); /* Lifted effect */
            margin-left: 10px; /* Space between left nav and main content */
            margin-right: 10px; /* Space between right nav and main content */
        }

        .user-profile{
            display: flex;
            flex-direction: column;
            background: white; /* White background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6); /* Lifted effect */
            margin-left: 10px; /* Space between left nav and main content */
            margin-right: 10px; /* Space between right nav and main content */
            height: 50%;
            justify-content: center;
            align-items: center;
            text-align: center;

        }

        .user-profile-image{

            height: 50%;
            width: 80%;
            border: 2px solid black;
            border-radius: 50%;
            background-color: rgb(242, 244, 244) ;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Lifted effect */
        }

        .user-profile-details{
            height: 25%;
            width: 80%;
            border: 2px solid black;
            border-radius: 2%;
            background-color: rgb(242, 244, 244) ;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Lifted effect */
            margin-top: 10px;
            padding-top: 10%;
        }
        .chat-username {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 0;
}

        .chat-title {
            font-size: 16px;
            font-style: italic;
            color: #777;
            margin: 5px 0 10px;
}

    .chat-name {
        font-size: 18px;
        color: #555;
        margin: 0;
}

        #dynamic-content {
            flex: 1; /* Allow the content to grow */
            padding: 20px;
        }

        /* General styles for the sidebar */
        .sidebar {
            width: 100%; /* Full width */
            display: flex;
            flex-direction: column; /* Stack sidebar items */
        }
        /* Add this to your CSS file or within a <style> tag in your HTML */
        .user-contacts-list {
            max-height: 200px; 
            overflow-x: scroll; 
            border: 1px solid #ccc; 
            padding: 10px; 
            display: flex;
            flex-direction: row;
           
        }
        .user-contacts-list::-webkit-scrollbar{
            display:none;
        }
        .contact-container{
            display: flex;
            flex-direction: row;
            padding: 15%;
            margin: 15%;
            border: 1px solid #ddd;
            height: 100%;
            width: auto;
            cursor: pointer;

        }
        .contact-container:hover{
            background-color: #f1f1f1;

        }
    .contact-item-image {
    width: 80px; /* Set desired width */
    height: 80px; /* Set desired height */
    overflow: hidden; /* Hide any overflow */
    border-radius: 50%; /* Make it circular, optional */
    display: flex; /* Aligns children (image) centrally */
    justify-content: center; /* Centers image horizontally */
    align-items: center; /* Centers image vertically */
}

.contact-item-image img {
    width: 100%; /* Make image fill the container */
    height: auto; /* Maintain aspect ratio */
    border-radius: 50%; /* Optional: round the image */
}
        .contact-item {
            cursor: pointer;
            padding: 15px;
           
            height: 100%;
            width: auto;
           
           
        }
        .contact-item:hover {
            background-color: #f1f1f1;
        }

    

        .chat-window{
            max-height: 400px; 
            width: 400px;
            overflow-y: auto;
            border: 1px solid #ccc; 
            padding: 10px; 
             margin-bottom: 10px; 
             box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
             border-radius: 10%;

        }
      
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 18px;
        text-align: left;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }
    table, th, td {
        border: 1px solid #dddddd;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;}
    
        .user-profile{
            margin: 20px;
            border: 50%;
        }
    
    .user-profile-details-admin{
        display: flex;
        flex-direction: row;
        
    }
   
    form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    form label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    form input[type="text"],
    form input[type="email"],
    form textarea {
        width: calc(100% - 24px);
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        box-sizing: border-box;
    }

    form textarea {
        height: 100px;
        resize: vertical;
    }

    form button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    form button:hover {
        background-color: }

 

    </style>
</head>
<body>
    <header class="left-nav">
        <nav>
            <ul>
                <li><a href="#" onclick="loadPage('home')">Home</a></li>
                <li><a href="#" onclick="loadPage('chat')">Chat</a></li>
                <li><a href="#" onclick="loadPage('profile')">Profile</a></li>
                <li><a href="#" onclick="loadPage('admin')">Admin</a></li>
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
function loadPage(page) {
    const dynamicContent = document.getElementById('dynamic-content');
    const sidebarContent = document.getElementById('sidebar-content');
    if (page === 'home') {
        dynamicContent.innerHTML = '<h1>Welcome Home!</h1><p>This is the home page content.</p>';
        sidebarContent.innerHTML = '';
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
    } else if (page === 'profile') {
        dynamicContent.innerHTML = '<h2>Profile Page</h2><div id="profile-details">Profile details will load here...</div>';
        sidebarContent.innerHTML = `
            <div class="nav-bar">
                <h3>Profile Page</h3>
                
            </div>
        `;
        
        loadSessionProfile();
        loadProfileNavBar();
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

function loadUserContactList() {
    const userContactList = document.querySelector('.user-contacts-list');
    fetch('load-contact-list.php')
        .then(response => response.json())
        .then(data => {
            if (data && !data.error) {
                userContactList.innerHTML = data.map(user => `
                    <div class="contact-container" data-user-id="${user.user_id}">
                        <div class="contact-item-image">
                            <img src="temp-profile-male.jpg" alt="profileImage">
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

function loadContactDetails(userId) {
    const dynamicContent = document.getElementById('dynamic-content');

    var xhr = new XMLHttpRequest();
    xhr.open("GET", `get_messages.php?receiver=${userId}`, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log(data); // Log the data for debugging
            if (data.length > 0) {
                const currentUserId = "<?php echo $_SESSION['user_id']; ?>"; // Get the session user ID
                // Extract the receiver's name from the first message
                const firstMessage = data[0];
                const receiverName = firstMessage.sender == currentUserId 
                     ? `${firstMessage.receiver_first_name} ${firstMessage.receiver_last_name}` 
                     : `${firstMessage.sender_first_name} ${firstMessage.sender_last_name}`;
                
                dynamicContent.innerHTML = `
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
                    <div id="message-input-container">
                        <form id="message-form">
                            <input type="text" id="message-input" placeholder="Type your message here..." required>
                            <button type="submit">Send</button>
                        </form>
                    </div>
                `;
                sendMessage(userId); // Initialize the message sending form
            } else {
                dynamicContent.innerHTML = '<p>No messages found.</p>';
            }
        } else {
            console.error('Error fetching contact details:', this.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
}

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


function loadSessionProfile() {
    const userProfileDetails = document.querySelector('.user-profile-details');
    fetch('load-user-profile.php')
        .then(response => response.json())
        .then(data => {
            if (data) {
                userProfileDetails.innerHTML = `
                    <p class="chat-username">${data.username}</p>
                    <p class="chat-title">${data.job_title}</p>
                    <p class="chat-name">${data.first_name} ${data.last_name}</p>
                `;
            } else {
                userProfileDetails.innerHTML = '<p>User profile not found.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching user profile:', error);
        });
}


function loadAdminNavBar() {
    const sidebarContent = document.getElementById('sidebar-content');
    sidebarContent.innerHTML = `
        <div class="nav-bar">
            <h3>Admin Page</h3>
            <ul>
                <li><a href="#" onclick="viewUsersProfile()">View/Edit Users Profile</a></li>
                <li><a href="#" onclick="resetUsersPassword()">Reset Users Password</a></li>
                <li><a href="#" onclick="activateDeleteUserAccount()">Activate/Deactivate User Account</a></li>
            </ul>
        </div>
    `;
}



function loadUserProfile() {
    const userContactList = document.querySelector('.user-contacts-list');
    fetch('load-contact-list.php')
        .then(response => response.json())
        .then(data => {
            if (data && !data.error) {
                userContactList.innerHTML = data.map(user => `
                    <div class="contact-container" data-user-id="${user.user_id}">
                        <div class="contact-item-image">
                            <img src="temp-profile-male.jpg" alt="profileImage">
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


function editUsersProfile() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = '<h2>Edit Users Profile</h2><p>Content for editing users profile goes here...</p>';
    // Additional functionality can be added here
}

function resetUsersPassword() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = '<h2>Reset Users Password</h2><p>Content for resetting users password goes here...</p>';
    // Additional functionality can be added here
}

function activateDeleteUserAccount() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = '<h2>Activate/Deactivate User Account</h2><p>Content for activating or deactivating user accounts goes here...</p>';
    // Additional functionality can be added here
}



function loadProfileNavBar() {
    const sidebarContent = document.getElementById('sidebar-content');
    sidebarContent.innerHTML = `
        <div class="nav-bar">
            <h3>Admin Page</h3>
            <ul>
                <li><a href="#" onclick="education()">Education</a></li>
                <li><a href="#" onclick="experience()">Experience</a></li>
                <li><a href="#" onclick="skills()">Skills</a></li>
                <li><a href="#" onclick="interests()">Interests</a></li>
            </ul>
        </div>
    `;
}


function education() {
    insertIntoEducation();
}
function experience() {
    insertIntoExperience();
   
}
function skills() {
    insertIntoSkills();
    
    
    
}
function interests() {
    insertIntoInterests();
}

function viewUsersProfile() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = '<h2>View Users Profile</h2><div id="user-profile-list">Loading users...</div>';

    fetch('load-contact-list.php')
        .then(response => response.json())
        .then(data => {
            const userProfileList = document.getElementById('user-profile-list');
            if (data && data.length > 0) {
                userProfileList.innerHTML = data.map(user => `
                    
                    <div class="user-profile" data-user-id="${user.user_id}">
                    
                        <div class="user-profile-details-admin">
                        <div class ="user-profile-image"><img src ="" alt="userImg"></div>
                            <h4>${user.first_name} ${user.last_name}</h4>
                        </div>
                    </div>
                `).join('');

                // Add click event to each user profile
                document.querySelectorAll('.user-profile').forEach(profile => {
                    profile.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        console.log(`Clicked user ID (viewUsersProfile): ${userId}`); // Log the user ID for debugging
                        loadUserProfileDetails(userId); // Load details for the selected user
                    });
                });
            } else {
                userProfileList.innerHTML = '<p>No users found.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching user profiles:', error);
            document.getElementById('user-profile-list').innerHTML = '<p>Error loading user profiles.</p>';
        });
}


function loadUserProfileDetails(userId) {
    const dynamicContent = document.getElementById('dynamic-content');
    console.log(`Loading profile for user ID (loadUserProfileDetails): ${userId}`);

    fetch(`profileData.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(user => {
            console.log(`User data fetched (loadUserProfileDetails):`, user);
            if (user.error) {
                dynamicContent.innerHTML = `<p>Error: ${user.error}</p>`;
                return;
            }
            dynamicContent.innerHTML = `
                <h2>${user.first_name} ${user.last_name}</h2>
                <table>
                    <tr><th>Username</th><td>${user.username}</td></tr>
                    <tr><th>Email</th><td>${user.email}</td></tr>
                    <tr><th>First Name</th><td>${user.first_name}</td></tr>
                    <tr><th>Last Name</th><td>${user.last_name}</td></tr>
                    <tr><th>Bio</th><td>${user.bio}</td></tr>
                    <tr><th>Job Title</th><td>${user.job_title}</td></tr>
                    <tr><th>Address</th><td>${user.address}</td></tr>
                    <tr><th>Phone Number</th><td>${user.phone_number}</td></tr>
                </table>
                <button onclick="editUserProfile(${user.user_id})">Edit Profile</button>
            `;
        })
        .catch(error => {
            console.error('Error fetching user details:', error);
            dynamicContent.innerHTML = '<p>Error loading user details.</p>';
        });
}

function editUserProfile(userId) {
    fetch(`profileData.php?user_id=${userId}`)
        .then(response => response.json())
        .then(user => {
            if (user.error) {
                const dynamicContent = document.getElementById('dynamic-content');
                dynamicContent.innerHTML = `<p>Error: ${user.error}</p>`;
                return;
            }

            const dynamicContent = document.getElementById('dynamic-content');
            dynamicContent.innerHTML = `
                <h2>Edit Profile for ${user.first_name} ${user.last_name}</h2>
                <form id="edit-profile-form">
                    <label>Username:</label>
                    <input type="text" id="username" name="username" value="${user.username}" required>
                    <label>Email:</label>
                    <input type="email" id="email" name="email" value="${user.email}" required>
                    <label>First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="${user.first_name}" required>
                    <label>Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="${user.last_name}" required>
                    <label>Bio:</label>
                    <textarea id="bio" name="bio">${user.bio}</textarea>
                    <label>Job Title:</label>
                    <input type="text" id="job_title" name="job_title" value="${user.job_title}">
                    <label>Address:</label>
                    <input type="text" id="address" name="address" value="${user.address}">
                    <label>Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" value="${user.phone_number}">
                    <button type="submit">Save</button>
                </form>
            `;

            const form = document.getElementById('edit-profile-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                formData.append('user_id', userId);

                fetch('updateProfile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Profile updated successfully.');
                        loadUserProfileDetails(userId); // Reload the updated profile
                    } else {
                        alert(`Error: ${result.error}`);
                    }
                })
                .catch(error => {
                    console.error('Error updating profile:', error);
                    alert('Error updating profile.');
                });
            });
        })
        .catch(error => {
            console.error('Error fetching user details:', error);
            const dynamicContent = document.getElementById('dynamic-content');
            dynamicContent.innerHTML = '<p>Error loading user details.</p>';
        });
}

function insertIntoSkills() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Skills</h2>
        <form id="skills-form">
            <label for="skill_name">Skill:</label>
            <input type="text" id="skill_name" name="skill_name" required>
            <input type="hidden" name="Skill_userId" value="1"> <!-- Replace 1 with the actual user ID -->
            <input type="hidden" name="insert_skill" value="true">
            <button type="submit">Submit</button>
        </form>
        <div id="skills-table"></div> <!-- Placeholder for the skills table -->
    `;

    const form = document.getElementById('skills-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('insert_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                updateDeleteSkills(1); // Refresh the skills table (replace 1 with the actual user ID)
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.error('Error inserting skill:', error);
            alert('Error inserting skill.');
        });
    });

    // Initially load the skills table
    console.log(`Calling updateDeleteSkills with user ID: 1`); // Log the call
    updateDeleteSkills(1); // Replace 1 with the actual user ID
}





function insertIntoEducation(){
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Education</h2>
        <form id="education-form">
            <label for="school_name">School Name:</label>
            <input type="text" id="school_name" name="school_name" required>
            <label for="degree_obtained">Degree obtained:</label>
            <input type="text" id="degree_obtained" name="degree_obtained" required>
            <label for="years_attended">Year Attended:</label>
            <input type="text" id="years_attended" name="years_attended" required>

           <input type="hidden" name="Education_userId" value="1"> <!-- Replace 1 with the actual user ID -->
            <input type="hidden" name="insert_education" value="true">
            <button type="submit">Submit</button>
        </form>
    `;

    const form = document.getElementById('education-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch('insert_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                // Optionally, you can add code here to refresh the page or update the content
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.error('Error inserting education:', error);
            alert('Error inserting education.');
        });
    });
}

function insertIntoExperience() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Experience</h2>
        <form id="experience-form">
            <label for="job_name">Company Name:</label>
            <input type="text" id="job_name" name="job_name" required>
            <label for="job_title">Title:</label>
            <input type="text" id="job_title" name="job_title" required>
            <label for="years_worked">Years worked:</label>
            <input type="text" id="years_worked" name="years_worked" required>
            <input type="hidden" name="experience_userId" value="1"> <!-- Replace 1 with the actual user ID -->
            <input type="hidden" name="insert_experience" value="true">
            <button type="submit">Submit</button>
        </form>
    `;

    const form = document.getElementById('experience-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch('insert_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                // Optionally, you can add code here to refresh the page or update the content
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.error('Error inserting experience:', error);
            alert('Error inserting experience.');
        });
    });
}


    

function insertIntoInterests() {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Interests</h2>
        <form id="interests-form">
            <label for="interest_name">Interest:</label>
            <input type="text" id="interest_name" name="interest_name" required>
            <input type="hidden" name="Interest_userId" value="1"> <!-- Replace 1 with the actual user ID -->
            <input type="hidden" name="insert_interest" value="true">
            <button type="submit">Submit</button>
        </form>
    `;

    const form = document.getElementById('interests-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch('insert_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                // Optionally, you can add code here to refresh the page or update the content
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.error('Error inserting interest:', error);
            alert('Error inserting interest.');
        });
    });
}











function updateDeleteSkills(userId) {
    fetch(`update_skills.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then(text => { throw new Error(text); });
            }
        })
        .then(data => {
            const skillsTable = document.getElementById('skills-table');
            if (data.error) {
                skillsTable.innerHTML = ''; // Clear the table if there's an error
                console.log('Error fetching skills:', data.error);
                return;
            }
            if (data.skills.length === 0) {
                skillsTable.innerHTML = '<p>No skills found.</p>'; // Display message if no skills found
                return;
            }
            skillsTable.innerHTML = `
                <h2>Update Skills</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Skill</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.skills.map(skill => `
                            <tr>
                                <td>${skill.skill_name}</td>
                                <td><button onclick="updateSkill(${skill.id}, ${skill.user_id}, '${skill.skill_name}')">Update</button></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        })
        .catch(error => {
            const skillsTable = document.getElementById('skills-table');
            skillsTable.innerHTML = '<p>There was an error fetching the skills.</p>'; // Clear the table if there's an error
            console.log('Error fetching skills:', error); // Log the error for debugging
        });
}


function updateSkill(skillId, userId, skillName) {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Edit Skill</h2>
        <form id="update-skill-form">
            <label for="skill_name">Skill:</label>
            <input type="text" id="skill_name" name="skill_name" value="${skillName}" required>
            <input type="hidden" name="skill_id" value="${skillId}">
            <input type="hidden" name="user_id" value="${userId}">
            <button type="submit">Save</button>
        </form>
    `;

    const form = document.getElementById('update-skill-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('update_skills.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                updateDeleteSkills(userId); // Refresh the skills table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.error('Error updating skill:', error);
            alert('Error updating skill.');
        });
    });
}
</script>


</body>
</html>