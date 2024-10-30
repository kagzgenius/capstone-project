/**
 * profile.js
 * This script manages the user's profile page functionalities, including loading user data 
 * and managing user-related sections such as Experience, Education, Skills, and Interests.
 * 
 * Functions include:
 * 
 * 1. `loadProfileNavBar`: 
 *    This function initializes the profile navigation bar, which allows the user to access 
 *    different sections (Experience, Education, Skills, Interests).
 * 
 * 2. `loadSessionProfile`: 
 *    Loads the signed-in user's profile information, including username, job title, 
 *    first name, and last name.
 * 
 * 3. `loadUserProfile`: 
 *    Retrieves data from the profile table and loads the user's contact list for messaging.
 * 
 * 4. `viewUsersProfile`: 
 *    Loads the profile information of the selected user for the admin page.
 * 
 * 5. `loadUserProfileDetails`: 
 *    Creates a table with detailed user and profile information for the admin page.
 * 
 * 6. `editUserProfile`: 
 *    Allows the admin to edit the user profile, populating a form with user and profile data 
 *    that can then be updated in the database.
 */


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
function loadUserProfile() {
    const userContactList = document.querySelector('.user-contacts-list');
    fetch('load-contact-list.php')
        .then(response => response.json())
        .then(data => {
            if (data && !data.error) {
                userContactList.innerHTML = data.map(user => `
                    <div class="contact-container" data-user-id="${user.user_id}">
                        <div class="contact-item-image">
                            <img src="uploads/${user.profile_pic ? user.profile_pic : 'default.png'}" alt="profilePic">
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
                            <div class="user-profile-image">
                                <img src="uploads/${user.profile_pic ? user.profile_pic : 'default.png'}" alt="userImg">
                            </div>
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
                    <tr><th>Password</th><td>${user.password}</td></tr>
                    <tr><th>Admin</th><td>${user.is_admin}</td></tr>
                    <tr><th>Active status</th><td>${user.is_active}</td></tr>
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
                    <label>Password:</label>
                    <input type="text" id="password" name="password" value="${user.password}">
                     <label>Admin:</label>
                    <input type="text" id="admin" name="admin" value="${user.is_admin}">
                     <label>Active Status:</label>
                    <input type="text" id="active" name="active" value="${user.is_active}">
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
