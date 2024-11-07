/**
 * skills.js
 * This script is utilized by the `profile.js` page when the `loadProfileNavBar` 
 * function is called. It manages user skills, including displaying, adding, 
 * updating, and deleting skills.
 * 
 * Functions include:
 * 
 * 1. `loadSkills`: 
 *    Called by the navigation bar, this function initializes the process for managing 
 *    user skills and calls `insertIntoSkills` to handle adding new skills.
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



function skills() {
   
    insertIntoSkills();   
    
}
function insertIntoSkills() {
    console.log('User ID:', userId); // Log the user ID for debugging

    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Skills</h2>
        <form id="skills-form">
            <label for="skill_name">Skill:</label>
            <input type="text" id="skill_name" name="skill_name" required>
            <input type="hidden" name="Skill_userId" value="${userId}">
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
                updateDeleteSkills(userId); // Refresh the skills table with the correct user ID
            } else {
                alert(`Error: ${result.error}`);
            }
        });
    });

    console.log(`Calling updateDeleteSkills with user ID: ${userId}`); // Log the call
    updateDeleteSkills(userId); // Use the correct user ID
}

// Continue with other functions...


function updateDeleteSkills(userId) {
    fetch(`update_skills.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const skillsTable = document.getElementById('skills-table');
            if (skillsTable) {
                if (data.error) {
                    skillsTable.innerHTML = '<p>Error fetching skills.</p>';
                    console.log('Error fetching skills:', data.error);
                    return;
                }
                if (data.skills.length === 0) {
                    skillsTable.innerHTML = '<p>No skills found.</p>';
                    return;
                }
                skillsTable.innerHTML = `
                    <h2>Update Skills</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Skill</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.skills.map(skill => `
                                <tr>
                                    <td>${skill.skill_name}</td>
                                    <td><button onclick="updateSkill(${skill.id}, ${skill.user_id}, '${skill.skill_name}')">Update</button></td>
                                    <td><button onclick="deleteSkill(${skill.id}, ${skill.user_id})">Delete</button></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
        })
        .catch(error => {
            const skillsTable = document.getElementById('skills-table');
            if (skillsTable) {
                skillsTable.innerHTML = '<p>There was an error fetching the skills.</p>';
            }
            console.log('Error fetching skills:', error.message); // Log the error for debugging
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
            console.log('Server response:', result); // Log the server response
            if (result.success) {
                alert(result.message);
                insertIntoSkills(); // Refresh the skills form and table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.log('Error updating skill:', error.message);
            alert('Error updating skill.');
        });
    });
}

function deleteSkill(skillId, userId) {
    if (!confirm("Are you sure you want to delete this skill?")) {
        return; // If the user cancels the confirmation, do nothing
    }

    fetch('delete_skill.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ skill_id: skillId, user_id: userId })
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
        console.log('Error deleting skill:', error.message);
        alert('Error deleting skill.');
    });
}

