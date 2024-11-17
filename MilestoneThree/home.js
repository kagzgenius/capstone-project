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

 * home.js
 * This script manages the home page functionalities, allowing users to search for other users 
 * with similar skills, interests, degrees, companies, titles, and colleges. It displays users 
 * with the same similarities and enables the logged-in user to view profiles, send messages, 
 * and display messages.
 * 
 * Functions include:
 * 
 * 1. `loadHomeNavBar`: 
 *    This function initializes the home navigation bar, allowing the user to navigate between 
 *    different group categories such as Skills, Interests, Degrees, Companies, Titles, and Colleges.
 * 
 * 2. `viewSameSkills`: 
 *    Fetches and displays skills related to the provided user ID. Adds event handlers to skill items 
 *    for fetching and displaying users with the same skill.
 * 
 * 3. `viewSameInterests`: 
 *    Fetches and displays interests related to the provided user ID. Adds event handlers to interest items 
 *    for fetching and displaying users with the same interest.
 * 
 * 4. `viewSameDegrees`: 
 *    Fetches and displays degrees related to the provided user ID. Adds event handlers to degree items 
 *    for fetching and displaying users with the same degree.
 * 
 * 5. `viewSameCompanies`: 
 *    Fetches and displays companies related to the provided user ID. Adds event handlers to company items 
 *    for fetching and displaying users with the same company.
 * 
 * 6. `viewSameTitles`: 
 *    Fetches and displays titles related to the provided user ID. Adds event handlers to title items 
 *    for fetching and displaying users with the same title.
 * 
 * 7. `viewSameColleges`: 
 *    Fetches and displays colleges related to the provided user ID. Adds event handlers to college items 
 *    for fetching and displaying users with the same college.
 * 
 * 8. `fetchUsersWithSkill`: 
 *    Fetches and displays users who have the specified skill. Adds event handlers to buttons for 
 *    sending messages and viewing profiles of these users.
 * 
 * 9. `fetchUsersWithSameInterests`: 
 *    Fetches and displays users who have the specified interest. Adds event handlers to buttons for 
 *    sending messages and viewing profiles of these users.
 * 
 * 10. `fetchUsersWithSameDegrees`: 
 *     Fetches and displays users who have the specified degree. Adds event handlers to buttons for 
 *     sending messages and viewing profiles of these users.
 * 
 * 11. `fetchUsersWithSameCompanies`: 
 *     Fetches and displays users who work at the specified company. Adds event handlers to buttons for 
 *     sending messages and viewing profiles of these users.
 * 
 * 12. `fetchUsersWithSameTitles`: 
 *     Fetches and displays users who hold the specified title. Adds event handlers to buttons for 
 *     sending messages and viewing profiles of these users.
 * 
 * 13. `fetchUsersWithSameColleges`: 
 *     Fetches and displays users who attended the specified college. Adds event handlers to buttons for 
 *     sending messages and viewing profiles of these users.
 * 
 * 14. `fetchUsersWithSameEducation`: 
 *    Fetches and displays users who have similar educational backgrounds. Adds event handlers to buttons 
 *    for sending messages and viewing profiles of these users.
 * 
 * 15. `fetchUsersWithSameExperience`: 
 *    Fetches and displays users who have similar professional experiences. Adds event handlers to buttons 
 *    for sending messages and viewing profiles of these users.
 * 
 * 16. `buildUserProfile`: 
 *     Constructs and displays the profile of a user based on their user ID. This function is used when 
 *     viewing another user's profile.
 * 
 * 17. `loadHomeMessages`: 
 *     Fetches and displays messages related to a specific receiver ID. Adds event handlers to send messages 
 *     within the home context.
 * 
 * 18. `sendHomeMessages`: 
 *     Sends a message to a specific receiver ID within the home context.
 */


function loadHomeNavBar() {
    const homeNavbar = document.getElementById('home-navbar'); // This gets the correct element
    homeNavbar.innerHTML = `  
        <div class="nav-bar">
            <h3>Groups</h3>
            <ul>
                <li><a href="#" onclick="viewSameSkills(userId)">Skills</a></li>
                <li><a href="#" onclick="viewSameInterests(userId)">Interests</a></li>
                <li><a href="#" onclick="viewSameDegrees(userId)">Degree</a></li>
                <li><a href="#" onclick="viewSameCompanies(userId)">Company</a></li>
                <li><a href="#" onclick="viewSameTitles(userId)">Title</a></li>
                <li><a href="#" onclick="viewSameColleges(userId)">College</a></li>
            </ul>
        </div>
    `;
}
/**
 * viewSameSkills
 * 
 * This function fetches and displays skills related to the provided user ID.
 * When a user clicks on a skill in the navigation bar, the user ID is passed from index.php.
 * It calls update_skills.php with that user_id, which calls fetchSkills in update_skills.php
 * to select and return the id, skill_name, and user_id from the skills table.
 * The skill names for the logged-in user are displayed as clickable items.
 * Clicking a skill name calls fetchUsersWithSkill with that skill name and searches 
 * through the user table for all users (excluding the logged-in user) with the selected skill name,
 * then returns the results.
 * 
 * The fetchUsersWithSkill function displays the users with the selected skill, providing buttons 
 * for viewing the profile and sending a message. It calls loadHomeMessages to get the messages 
 * between the logged-in user and the selected person. It also calls buildUserProfile to display 
 * the user profile information when the view profile button is clicked and sendHomeMessage to 
 * allow the logged-in user to send a message to the selected person.
 */



function viewSameSkills(userId) {
    fetch(`update_skills.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching skills.</p>';
                    console.log('Error fetching skills:', data.error);
                    return;
                }
                if (data.skills.length === 0) {
                    homepageDetails.innerHTML = '<p>No skills found.</p>';
                    return;
                }
                homepageDetails.innerHTML = '<h2>Skills</h2>';
                const skillItems = data.skills.map(skill => `<div class="skill-item" data-skill="${skill.skill_name}">${skill.skill_name}</div>`).join('');
                homepageDetails.innerHTML += `<div class="skills-container">${skillItems}</div>`;
                
                // Add click event handlers to skill items
                document.querySelectorAll('.skill-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const skillName = this.getAttribute('data-skill');
                        fetchUsersWithSkill(skillName);
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching the skills.</p>';
            }
            console.log('Error fetching skills:', error.message); // Log the error for debugging
        });
}

function fetchUsersWithSkill(skillName) {
    console.log(`Fetching users with skill: ${skillName}`);
    fetch(`update_skills.php?skill_name=${skillName}`)
        .then(response => response.json())
        .then(data => {
            console.log('fetchUsersWithSkill response:', data);
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching users with the skill.</p>';
                    console.log('Error fetching users with the skill:', data.error);
                    return;
                }
                if (!data.users || data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this skill.</p>';
                    console.log(`No users found with skill: ${skillName}`);
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with Skill: ' + skillName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-skills" data-user="${user.id}" data-skill="${user.skill_name}">
                        <span class="username">${user.username}</span>
                        <span class="skill">${user.skill_name}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                        <button class="view-profile" data-user="${user.id}">View Profile</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-skills-container">${userItems}</div>`;
                
                // Add click event handlers to send message buttons
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        loadHomeMessages(receiverId); // Load messages in home context
                    });
                });

                // Add click event handlers to view profile buttons
                document.querySelectorAll('.view-profile').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-user');
                        buildUserProfile(userId); // Function to build and show user profile
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching users with the skill.</p>';
            }
            console.log('Error fetching users with the skill:', error.message);
        });
}


/**
 * viewSameInterests
 * 
 * This function fetches and displays interests related to the provided user ID.
 * When a user clicks on an interest in the navigation bar, the user ID is passed from index.php.
 * It calls update_interests.php with that user_id, which calls fetchInterests in update_interests.php
 * to select and return the id, interest_name, and user_id from the interests table.
 * The interest names for the logged-in user are displayed as clickable items.
 * Clicking an interest name calls fetchUsersWithInterest with that interest name and searches 
 * through the user table for all users (excluding the logged-in user) with the selected interest name,
 * then returns the results.
 * 
 * The fetchUsersWithInterest function displays the users with the selected interest, providing buttons 
 * for viewing the profile and sending a message. It calls loadHomeMessages to get the messages 
 * between the logged-in user and the selected person. It also calls buildUserProfile to display 
 * the user profile information when the view profile button is clicked and sendHomeMessage to 
 * allow the logged-in user to send a message to the selected person.
 */




function viewSameInterests(userId) {
    fetch(`update_interests.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching interests.</p>';
                    console.log('Error fetching interests:', data.error);
                    return;
                }
                if (data.interests.length === 0) {
                    homepageDetails.innerHTML = '<p>No interests found.</p>';
                    return;
                }
                homepageDetails.innerHTML = '<h2>Interests</h2>';
                const interestItems = data.interests.map(interest => `<div class="interest-item" data-interest="${interest.interest_name}">${interest.interest_name}</div>`).join('');
                homepageDetails.innerHTML += `<div class="interests-container">${interestItems}</div>`;
                
                // Add click event handlers to interest items
                document.querySelectorAll('.interest-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const interestName = this.getAttribute('data-interest');
                        fetchUsersWithInterest(interestName);
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching the interests.</p>';
            }
            console.log('Error fetching interests:', error.message); // Log the error for debugging
        });
}
function fetchUsersWithInterest(interestName) {
    console.log(`Fetching users with interest: ${interestName}`);
    fetch(`update_interests.php?interest_name=${interestName}`)
        .then(response => response.json())
        .then(data => {
            console.log('fetchUsersWithInterest response:', data);
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching users with the interest.</p>';
                    console.log('Error fetching users with the interest:', data.error);
                    return;
                }
                if (!data.users || data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this interest.</p>';
                    console.log(`No users found with interest: ${interestName}`);
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with Interest: ' + interestName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-interests" data-user="${user.id}" data-interest="${user.interest_name}">
                        <span class="username">${user.username}</span>
                        <span class="interest">${user.interest_name}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                        <button class="view-profile" data-user="${user.id}">View Profile</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-interests-container">${userItems}</div>`;
                
                // Add click event handlers to send message buttons
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        loadHomeMessages(receiverId); // Load messages in home context
                    });
                });

                // Add click event handlers to view profile buttons
                document.querySelectorAll('.view-profile').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-user');
                        buildUserProfile(userId); // Function to build and show user profile
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching users with the interest.</p>';
            }
            console.log('Error fetching users with the interest:', error.message);
        });
}

/**
 * viewSameDegrees
 * 
 * This function fetches and displays degrees related to the provided user ID.
 * When a user clicks on a degree in the navigation bar, the user ID is passed from index.php.
 * It calls update_education.php with that user_id, which calls fetchDegrees in update_education.php
 * to select and return the id, degree_name, and user_id from the degrees table.
 * The degree names for the logged-in user are displayed as clickable items.
 * Clicking a degree name calls fetchUsersWithDegree with that degree name and searches 
 * through the user table for all users (excluding the logged-in user) with the selected degree name,
 * then returns the results.
 * 
 * The fetchUsersWithDegree function displays the users with the selected degree, providing buttons 
 * for viewing the profile and sending a message. It calls loadHomeMessages to get the messages 
 * between the logged-in user and the selected person. It also calls buildUserProfile to display 
 * the user profile information when the view profile button is clicked and sendHomeMessage to 
 * allow the logged-in user to send a message to the selected person.
 */


function viewSameDegrees(userId) {
    console.log(`Fetching degrees for user ID: ${userId}`); // Log user ID
    fetch(`update_education.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            console.log('viewSameDegrees response:', data); // Log the response
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching degrees.</p>';
                    console.log('Error fetching degrees:', data.error);
                    return;
                }
                if (data.education.length === 0) {
                    homepageDetails.innerHTML = '<p>No degrees found.</p>';
                    console.log(`No degrees found for user ID: ${userId}`); // Log no degrees found
                    return;
                }
                homepageDetails.innerHTML = '<h2>Degrees</h2>';
                const degreeItems = data.education.map(degree => `<div class="degree-item" data-degree="${degree.degree_obtained}">${degree.degree_obtained}</div>`).join('');
                homepageDetails.innerHTML += `<div class="degrees-container">${degreeItems}</div>`;
                
                // Add click event handlers to degree items
                document.querySelectorAll('.degree-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const degreeName = this.getAttribute('data-degree');
                        console.log(`Degree clicked: ${degreeName}`); // Log clicked degree
                        fetchUsersWithDegree(degreeName);
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching the degrees.</p>';
            }
            console.log('Error fetching degrees:', error.message); // Log the error for debugging
        });
}
function fetchUsersWithDegree(degreeName) { 
    console.log(`Fetching users with degree: ${degreeName}`); 
    fetch(`update_education.php?degree_name=${degreeName}`)
        .then(response => response.json()) 
        .then(data => { 
            console.log('fetchUsersWithDegree response:', data); 
            const homepageDetails = document.querySelector('.homepage-details'); 
            if (homepageDetails) {
                if (data.error) { 
                    homepageDetails.innerHTML = '<p>Error fetching users with the degree.</p>'; 
                    console.log('Error fetching users with the degree:', data.error); 
                    return; 
                } 
                if (!data.users || data.users.length === 0) { 
                    homepageDetails.innerHTML = '<p>No users found with this degree.</p>'; 
                    console.log(`No users found with degree: ${degreeName}`); 
                    return; 
                }
                homepageDetails.innerHTML = '<h2>Users with Degree: ' + degreeName + '</h2>'; 
                const userItems = data.users.map(user => ` 
                    <div class="user-degrees" data-user="${user.id}" data-degree="${user.degree_obtained}"> 
                        <span class="username">${user.username}</span> 
                        <span class="degree">${user.degree_obtained}</span> 
                        <button class="send-message" data-receiver="${user.id}">Send Message</button> 
                        <button class="view-profile" data-user="${user.id}">View Profile</button> 
                    </div> 
                `).join('');
                homepageDetails.innerHTML += `<div class="users-degrees-container">${userItems}</div>`;
                
                // Add click event handlers to send message buttons
                document.querySelectorAll('.send-message').forEach(button => { 
                    button.addEventListener('click', function () { 
                        const receiverId = this.getAttribute('data-receiver');
                        loadHomeMessages(receiverId); // Load messages in home context
                    }); 
                });

                // Add click event handlers to view profile buttons 
                document.querySelectorAll('.view-profile').forEach(button => { 
                    button.addEventListener('click', function () { 
                        const userId = this.getAttribute('data-user'); 
                        buildUserProfile(userId); // Function to build and show user profile
                    }); 
                }); 
            } 
        }) 
        .catch(error => { 
            const homepageDetails = document.querySelector('.homepage-details'); 
            if (homepageDetails) { 
                homepageDetails.innerHTML = '<p>There was an error fetching users with the degree.</p>'; 
            } 
            console.log('Error fetching users with the degree:', error.message); 
        }); 
}

/**
 * viewSameCompanies
 * 
 * This function fetches and displays companies related to the provided user ID.
 * When a user clicks on a company in the navigation bar, the user ID is passed from index.php.
 * It calls update_experience.php with that user_id, which calls fetchCompanies in update_experience.php
 * to select and return the id, company_name, and user_id from the companies table.
 * The company names for the logged-in user are displayed as clickable items.
 * Clicking a company name calls fetchUsersWithCompany with that company name and searches 
 * through the user table for all users (excluding the logged-in user) with the selected company name,
 * then returns the results.
 * 
 * The fetchUsersWithCompany function displays the users with the selected company, providing buttons 
 * for viewing the profile and sending a message. It calls loadHomeMessages to get the messages 
 * between the logged-in user and the selected person. It also calls buildUserProfile to display 
 * the user profile information when the view profile button is clicked and sendHomeMessage to 
 * allow the logged-in user to send a message to the selected person.
 */



function viewSameCompanies(userId) {
    console.log(`Fetching companies for user ID: ${userId}`); // Log user ID
    fetch(`update_experience.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            console.log('viewSameCompanies response:', data); // Log the response
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<h2>Companies</h2>';
                const companyItems = data.experience.map(company => `<div class="company-item" data-company="${company.job_name}">${company.job_name}</div>`).join('');
                homepageDetails.innerHTML += `<div class="companies-container">${companyItems}</div>`;
                
                // Add click event handlers to company items
                document.querySelectorAll('.company-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const companyName = this.getAttribute('data-company');
                        console.log(`Company clicked: ${companyName}`); // Log clicked company
                        fetchUsersWithCompany(companyName);
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching the companies.</p>';
            }
            console.log('Error fetching companies:', error.message); // Log the error for debugging
        });
}



function fetchUsersWithCompany(companyName) {
    console.log(`Fetching users with company: ${companyName}`);
    fetch(`update_experience.php?company_name=${companyName}`)
        .then(response => response.json())
        .then(data => {
            console.log('fetchUsersWithCompany response:', data);
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching users with the company.</p>';
                    console.log('Error fetching users with the company:', data.error);
                    return;
                }
                if (!data.users || data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this company.</p>';
                    console.log(`No users found with company: ${companyName}`);
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with Company: ' + companyName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-companies" data-user="${user.id}" data-company="${user.company_name}">
                        <span class="username">${user.username}</span>
                        <span class="company">${user.company_name}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                        <button class="view-profile" data-user="${user.id}">View Profile</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-companies-container">${userItems}</div>`;
                
                // Add click event handlers to send message buttons
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        loadHomeMessages(receiverId); // Load messages in home context
                    });
                });

                // Add click event handlers to view profile buttons
                document.querySelectorAll('.view-profile').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-user');
                        buildUserProfile(userId); // Function to build and show user profile
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching users with the company.</p>';
            }
            console.log('Error fetching users with the company:', error.message);
        });
}

/**
 * viewSameTitles
 * 
 * This function fetches and displays titles related to the provided user ID.
 * When a user clicks on a title in the navigation bar, the user ID is passed from index.php.
 * It calls update_experience.php with that user_id, which calls fetchTitles in update_experience.php
 * to select and return the id, title_name, and user_id from the titles table.
 * The title names for the logged-in user are displayed as clickable items.
 * Clicking a title name calls fetchUsersWithTitle with that title name and searches 
 * through the user table for all users (excluding the logged-in user) with the selected title name,
 * then returns the results.
 * 
 * The fetchUsersWithTitle function displays the users with the selected title, providing buttons 
 * for viewing the profile and sending a message. It calls loadHomeMessages to get the messages 
 * between the logged-in user and the selected person. It also calls buildUserProfile to display 
 * the user profile information when the view profile button is clicked and sendHomeMessage to 
 * allow the logged-in user to send a message to the selected person.
 */




function viewSameTitles(userId) {
    console.log(`Fetching job titles for user ID: ${userId}`); // Log user ID
    fetch(`update_experience.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            console.log('viewSameTitles response:', data); // Log the response
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<h2>Job Titles</h2>';
                const titleItems = data.experience.map(title => `<div class="title-item" data-title="${title.job_title}">${title.job_title}</div>`).join('');
                homepageDetails.innerHTML += `<div class="titles-container">${titleItems}</div>`;
                
                // Add click event handlers to title items
                document.querySelectorAll('.title-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const titleName = this.getAttribute('data-title');
                        console.log(`Job Title clicked: ${titleName}`); // Log clicked title
                        fetchUsersWithTitle(titleName);
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching the job titles.</p>';
            }
            console.log('Error fetching job titles:', error.message); // Log the error for debugging
        });
}
function fetchUsersWithTitle(titleName) {
    console.log(`Fetching users with job title: ${titleName}`);
    fetch(`update_experience.php?title_name=${titleName}`)
        .then(response => response.json())
        .then(data => {
            console.log('fetchUsersWithTitle response:', data);
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching users with the job title.</p>';
                    console.log('Error fetching users with the job title:', data.error);
                    return;
                }
                if (data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this job title.</p>';
                    console.log(`No users found with job title: ${titleName}`);
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with Job Title: ' + titleName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-titles" data-user="${user.id}" data-title="${user.job_title}">
                        <span class="username">${user.username}</span>
                        <span class="title">${user.job_title}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                        <button class="view-profile" data-user="${user.id}">View Profile</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-titles-container">${userItems}</div>`;
                
                // Add click event handlers to send message buttons
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        loadHomeMessages(receiverId); // Load messages in home context
                    });
                });

                // Add click event handlers to view profile buttons
                document.querySelectorAll('.view-profile').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-user');
                        buildUserProfile(userId); // Function to build and show user profile
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching users with the job title.</p>';
            }
            console.log('Error fetching users with the job title:', error.message);
        });
}
/**
 * viewSameColleges
 * 
 * This function fetches and displays colleges related to the provided user ID.
 * When a user clicks on a college in the navigation bar, the user ID is passed from index.php.
 * It calls update_education.php with that user_id, which calls fetchColleges in update_education.php
 * to select and return the id, college_name, and user_id from the colleges table.
 * The college names for the logged-in user are displayed as clickable items.
 * Clicking a college name calls fetchUsersWithCollege with that college name and searches 
 * through the user table for all users (excluding the logged-in user) with the selected college name,
 * then returns the results.
 * 
 * The fetchUsersWithCollege function displays the users with the selected college, providing buttons 
 * for viewing the profile and sending a message. It calls loadHomeMessages to get the messages 
 * between the logged-in user and the selected person. It also calls buildUserProfile to display 
 * the user profile information when the view profile button is clicked and sendHomeMessage to 
 * allow the logged-in user to send a message to the selected person.
 */


function viewSameColleges(userId) {
    console.log(`Fetching colleges for user ID: ${userId}`); // Log user ID
    fetch(`update_education.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            console.log('viewSameColleges response:', data); // Log the response
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching colleges.</p>';
                    console.log('Error fetching colleges:', data.error);
                    return;
                }
                if (data.education.length === 0) { // Ensure you're checking the correct key
                    homepageDetails.innerHTML = '<p>No colleges found.</p>';
                    console.log(`No colleges found for user ID: ${userId}`); // Log no colleges found
                    return;
                }
                homepageDetails.innerHTML = '<h2>Colleges</h2>';
                const collegeItems = data.education.map(college => `<div class="college-item" data-college="${college.school_name}">${college.school_name}</div>`).join('');
                homepageDetails.innerHTML += `<div class="colleges-container">${collegeItems}</div>`;
                
                // Add click event handlers to college items
                document.querySelectorAll('.college-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const collegeName = this.getAttribute('data-college');
                        console.log(`College clicked: ${collegeName}`); // Log clicked college
                        fetchUsersWithCollege(collegeName);
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching the colleges.</p>';
            }
            console.log('Error fetching colleges:', error.message); // Log the error for debugging
        });
}



function fetchUsersWithCollege(collegeName) {
    console.log(`Fetching users with college: ${collegeName}`);
    fetch(`update_education.php?college_name=${collegeName}`)
        .then(response => response.json())
        .then(data => {
            console.log('fetchUsersWithCollege response:', data);
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = '<p>Error fetching users with the college.</p>';
                    console.log('Error fetching users with the college:', data.error);
                    return;
                }
                if (data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this college.</p>';
                    console.log(`No users found with college: ${collegeName}`);
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with College: ' + collegeName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-colleges" data-user="${user.id}" data-college="${user.school_name}">
                        <span class="username">${user.username}</span>
                        <span class="college">${user.school_name}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                        <button class="view-profile" data-user="${user.id}">View Profile</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-colleges-container">${userItems}</div>`;
                
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        loadHomeMessages(receiverId); // Call your existing function for sending home messages
                    });
                });

                document.querySelectorAll('.view-profile').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.getAttribute('data-user');
                        buildUserProfile(userId); // Function to build and show user profile
                    });
                });
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>There was an error fetching users with the college.</p>';
            }
            console.log('Error fetching users with the college:', error.message);
        });
}




// Example function to handle viewing user profile
function viewUserProfile(userId) {
    // Logic to display user's profile details
    console.log(`Viewing profile for user: ${userId}`);
}

/**
 * buildUserProfile
 * 
 * This function calls get_user_profile.php to retrieve user profile data.
 * get_user_profile.php queries the user, skills, interest, education, experience, and profile tables
 * and joins the data together.
 * 
 * buildUserProfile constructs a table that displays an image of the user's profile picture along with 
 * their first name, last name, username, bio, job title, address, phone number, experience, skills, 
 * interests, and education. If any of these fields (skills, interests, education, or experience) are 
 * not available, it displays a message indicating that no interests, skills, education, or experience 
 * are found.
 */



function buildUserProfile(userId) {
    fetch(`get_user_profile.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            console.log('buildUserProfile response:', data);
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                if (data.error) {
                    homepageDetails.innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                // Initialize profile content with profile and cover pictures if available
                let profileContent = '';
                if (data.profile_pic) {
                    profileContent += `<img src="${data.profile_pic}" alt="Profile Picture" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">`;
                }
                if (data.cover_pic) {
                    profileContent += `<img src="${data.cover_pic}" alt="Cover Picture" style="width: 100%; height: 200px; object-fit: cover;">`;
                }

                // Add user profile details below the images
                profileContent += `
                    <h2>${data.first_name} ${data.last_name}</h2>
                    <p>Username: ${data.username}</p>
                    <p>Bio: ${data.bio}</p>
                    <p>Job Title: ${data.job_title}</p>
                    <p>Address: ${data.address}</p>
                    <p>Phone Number: ${data.phone_number}</p>
                    <p>Experience: ${data.experience ? data.experience.split('|').join('<br>') : 'No experience listed'}</p>
                    <p>Skills: ${data.skills ? data.skills : 'No skills listed'}</p>
                    <p>Interests: ${data.interests ? data.interests : 'No interests listed'}</p>
                    <p>Education: ${data.education ? data.education.split('|').join('<br>') : 'No education listed'}</p>
                `;

                // Update the homepage details with the constructed profile content
                homepageDetails.innerHTML = profileContent;
            }
        })
        .catch(error => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<p>Error loading profile details.</p>';
            }
            console.log('Error fetching user profile:', error.message);
        });
}
/**
 * loadHomeMessages
 * 
 * This function takes a userId and passes it to get_messages.php as the receiverId.
 * get_messages.php queries the messages table in the database to retrieve all messages 
 * between the logged-in user (as the sender) and the selected user (as the receiver).
 * 
 * The messages are then displayed in a scrollable container that shows all the messages 
 * exchanged between the sender and the receiver.
 */


function loadHomeMessages(userId) {
    const homepageDetails = document.querySelector('.homepage-details');

    var xhr = new XMLHttpRequest();
    xhr.open("GET", `get_messages.php?receiver=${userId}`, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log('Messages loaded:', data); // Log the data for debugging

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
                    <form id="home-message-form">
                        <input type="text" id="home-message-input" placeholder="Type your message here..." required>
                        <button type="submit">Send</button>
                    </form>
                </div>
            `;

            // Update the dynamic content
            homepageDetails.innerHTML = chatContent;

            // Initialize the message sending function
            sendHomeMessage(userId);

        } else {
            console.error('Error fetching messages:', this.statusText);
        }
    };

    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
}

/**
 * sendHomeMessage
 * 
 * This function grabs the userId and calls send_message.php, 
 * which sends a message to the user selected.
 * 
 * It also calls loadHomeMessages to display the message that was just sent.
 */


function sendHomeMessage(userId) {
    const messageForm = document.getElementById('home-message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            const messageInput = document.getElementById('home-message-input');
            let message = messageInput.value.trim(); // Get the message input and trim whitespace

            if (!message) {
                message = "This is a default message"; // Set a default message if the input is empty
            }

            console.log(`Sending message: ${message}`);

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

                        loadHomeMessages(userId); // Reload chat to show new message
                    } else {
                        console.error('Error sending message:', this.statusText);
                    }
                }
            };
        });
    } else {
        console.error('Message form not found');
    }
}
