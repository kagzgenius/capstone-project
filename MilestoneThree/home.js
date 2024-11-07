
// home.js

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
                if (data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this skill.</p>';
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with Skill: ' + skillName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-skills" data-user="${user.id}" data-skill="${user.skill_name}">
                        <span class="username">${user.username}</span>
                        <span class="skill">${user.skill_name}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-skills-container">${userItems}</div>`;
                
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        createAndShowMessageForm(receiverId);
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
                if (data.users.length === 0) {
                    homepageDetails.innerHTML = '<p>No users found with this interest.</p>';
                    return;
                }
                homepageDetails.innerHTML = '<h2>Users with Interest: ' + interestName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-interests" data-user="${user.id}" data-interest="${user.interest_name}">
                        <span class="username">${user.username}</span>
                        <span class="interest">${user.interest_name}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-interests-container">${userItems}</div>`;
                
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        createAndShowMessageForm(receiverId);
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
                if (data.users.length === 0) {
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
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-degrees-container">${userItems}</div>`;
                
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        createAndShowMessageForm(receiverId);
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
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-companies-container">${userItems}</div>`;
                
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        createAndShowMessageForm(receiverId);
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
    fetch(`update_experience.php?title_name=${titleName}`)
        .then(response => response.json())
        .then(data => {
            const homepageDetails = document.querySelector('.homepage-details');
            if (homepageDetails) {
                homepageDetails.innerHTML = '<h2>Users with Job Title: ' + titleName + '</h2>';
                const userItems = data.users.map(user => `
                    <div class="user-titles" data-user="${user.id}" data-title="${user.job_title}">
                        <span class="username">${user.username}</span>
                        <span class="title">${user.job_title}</span>
                        <button class="send-message" data-receiver="${user.id}">Send Message</button>
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-titles-container">${userItems}</div>`;
                
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        createAndShowMessageForm(receiverId); // Ensure form is created
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
                    </div>
                `).join('');
                homepageDetails.innerHTML += `<div class="users-colleges-container">${userItems}</div>`;
                
                // Add click event handlers to send message buttons
                document.querySelectorAll('.send-message').forEach(button => {
                    button.addEventListener('click', function () {
                        const receiverId = this.getAttribute('data-receiver');
                        createAndShowMessageForm(receiverId);
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


function createAndShowMessageForm(receiverId) {
    const existingFormContainer = document.getElementById('message-form-container');
    if (existingFormContainer) {
        existingFormContainer.remove();
    }

    const formContainer = document.createElement('div');
    formContainer.id = 'message-form-container';
    formContainer.style.position = 'relative';
    formContainer.style.padding = '10px';
    formContainer.style.border = '1px solid #ccc';
    formContainer.style.borderRadius = '8px';
    formContainer.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    formContainer.style.backgroundColor = '#f9f9f9';
    formContainer.style.marginTop = '20px';
    formContainer.style.width = '300px';
    formContainer.style.maxHeight = '300px';
    formContainer.style.overflowY = 'auto';

    const form = document.createElement('form');
    form.id = 'message-form';

    const input = document.createElement('input');
    input.type = 'text';
    input.id = 'message-input';
    input.placeholder = 'Type your message...';
    input.style.width = '100%';
    input.style.padding = '10px';
    input.style.marginBottom = '10px';
    input.style.border = '1px solid #ccc';
    input.style.borderRadius = '4px';

    const button = document.createElement('button');
    button.type = 'submit';
    button.textContent = 'Send';
    button.style.width = '100%';
    button.style.padding = '10px';
    button.style.backgroundColor = '#007bff';
    button.style.border = 'none';
    button.style.borderRadius = '4px';
    button.style.color = '#fff';
    button.style.cursor = 'pointer';

    form.appendChild(input);
    form.appendChild(button);
    formContainer.appendChild(form);

    const homepageDetails = document.querySelector('.homepage-details');
    homepageDetails.insertAdjacentElement('afterend', formContainer);

    form.setAttribute('data-receiver', receiverId);

    form.removeEventListener('submit', handleFormSubmit);
    form.addEventListener('submit', handleFormSubmit);

    input.focus();
}

function handleFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const receiverId = form.getAttribute('data-receiver');
    const message = form.querySelector('#message-input').value;
    sendMessage(receiverId, message);
}

function sendMessage(receiverId, message) {
    const sendAjax = new XMLHttpRequest();
    sendAjax.open("POST", "send_message.php", true);
    sendAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    sendAjax.send(`receiver=${receiverId}&message=${encodeURIComponent(message)}`);
    sendAjax.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 200) {
                document.getElementById('message-input').value = '';
                loadContactDetails(receiverId);
            } else {
                console.error('Error sending message:', this.statusText);
            }
        }
    };
}
