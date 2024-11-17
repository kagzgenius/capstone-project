/**
 * <!--
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

/**
 * insertIntoSkills
 * 
 * This function is used to insert new data into the skills table.
 * 
 * 1. Form Creation:
 *    It creates a form dynamically, allowing a user to input and submit new skill data.
 *    The form includes fields for skill name and hidden fields for user ID and a flag to indicate the skill insertion.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls insert_data.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Calling insert_data.php:
 *    insert_data.php processes the form data and performs an INSERT operation 
 *    into the skills table based on the data provided by the user.
 *    The script checks if the form submission is valid using a $_POST['insert_skill'] 
 *    if statement. If the data is valid, it gets inserted into the skills table. 
 *    If there is an error during the insert operation, it throws an error message indicating 
 *    that the insertion failed.
 * 
 * 4. Updating the Skills Table:
 *    Upon successful insertion of the skill, the function calls updateDeleteSkills 
 *    to show any inserts and create a table. This table allows the user to either update or delete their skills.
 */

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

/**
 * updateDeleteSkills
 * 
 * This function takes on the userId from index.php and makes a fetch request 
 * to update_skills.php with the userId as a query parameter.
 * 
 * 1. Fetching Skills Data:
 *    It calls fetchSkills in update_skills.php to retrieve the skills data from 
 *    the skills table for the specified userId. The retrieved data is then used 
 *    to populate a form with the skills associated with the logged-in user.
 * 
 * 2. Populating the Form:
 *    The function populates the skills table with the skill names and provides 
 *    buttons for updating and deleting each skill.
 * 
 * 3. Handling Update and Delete:
 *    - If the user chooses to update a skill, it sends the skill information to updateSkill.
 *    - updateSkill takes the information and displays it in a form where the user can update the skill.
 *    - Upon submission, it sends the updated skill data back to update_skills.php to update the skills table with the changes made.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request, it displays an error message in the skills table and logs the error.
 */



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
/**
 * updateSkill
 * 
 * This function receives the skillId, userId, and skill name from updateDeleteSkills 
 * and passes them to a form.
 * 
 * 1. Dynamic Form Creation:
 *    It dynamically creates a form to edit the selected skill, pre-filling the form with the current 
 *    skill name. The form includes hidden fields for skillId and userId.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls update_skills.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Processing the Update:
 *    The update_skills.php script uses the updateSkill function to update the skills table 
 *    with the changes made by the user.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request or the update operation, it logs the error 
 *    and displays an error message to the user.
 */

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

/**
 * deleteSkill
 * 
 * This function takes on the skillId and userId sent from updateDeleteSkills.
 * 
 * 1. Confirmation Message:
 *    It sends a message to the user asking if they are sure they would like to delete the skill.
 *    If the user confirms, the function proceeds with the deletion.
 * 
 * 2. Calling delete_skills.php:
 *    If the user confirms the deletion, the function calls delete_skills.php.
 *    delete_skills.php takes the skillId and userId and deletes the corresponding entry from the skills table.
 * 
 * 3. Updating the Skills Table:
 *    After the deletion, deleteSkills calls updateDeleteSkills to refresh the skills table and show the changes.
 * 
 * 4. Displaying a Message:
 *    The function then shows a message stating that the skill has been successfully deleted.
 */

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

