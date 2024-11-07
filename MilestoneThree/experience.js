/**
 * experience.js
 * This script is called when a user navigates to the profile page and uses the 
 * `loadProfileNavBar`. It manages user experience entries, including adding, 
 * updating, and deleting experience data.
 * 
 * Functions include:
 * 
 * 1. `loadExperience`: 
 *    This function is invoked by the navigation bar and simply calls the 
 *    `insertIntoExperience` function to handle the process of adding new 
 *    experience data.
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


function experience() {
    insertIntoExperience();
   
}

function insertIntoExperience() {
    
    console.log('User ID:', userId); // Log the user ID for debugging

    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Experience</h2>
        <form id="experience-form">
            <label for="job_name">Company Name:</label>
            <input type="text" id="job_name" name="job_name" required>
            <label for="job_title">Title:</label>
            <input type="text" id="job_title" name="job_title" required>
            <label for="years_worked">Years Worked:</label>
            <input type="text" id="years_worked" name="years_worked" required>
            <input type="hidden" name="experience_userId" value="${userId}">
            <input type="hidden" name="insert_experience" value="true">
            <button type="submit">Submit</button>
        </form>
        <div id="experience-table"></div> <!-- Placeholder for the experience table -->
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
            console.log('Server response:', result); // Log the server response for debugging
            if (result.success) {
                alert(result.message);
                updateDeleteExperience(userId); // Refresh the experience table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.error('Error inserting experience:', error);
            alert('Error inserting experience.');
        });
    });

    console.log(`Calling updateDeleteExperience with user ID: ${userId}`); // Log the call
    updateDeleteExperience(userId); // Use the correct user ID
}

function updateDeleteExperience(userId) {
    fetch(`update_experience.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const experienceTable = document.getElementById('experience-table');
            if (experienceTable) {
                if (data.error) {
                    experienceTable.innerHTML = '<p>Error fetching experience.</p>';
                    console.log('Error fetching experience:', data.error);
                    return;
                }
                if (data.experience.length === 0) {
                    experienceTable.innerHTML = '<p>No experience found.</p>';
                    return;
                }
                experienceTable.innerHTML = `
                    <h2>Update Experience</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Title</th>
                                <th>Years Worked</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.experience.map(exp => `
                                <tr>
                                    <td>${exp.job_name}</td>
                                    <td>${exp.job_title}</td>
                                    <td>${exp.years_worked}</td>
                                    <td><button onclick="updateExperience(${exp.id}, ${exp.user_id}, '${exp.job_name}', '${exp.job_title}', '${exp.years_worked}')">Update</button></td>
                                    <td><button onclick="deleteExperience(${exp.id}, ${exp.user_id})">Delete</button></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
        })
        .catch(error => {
            const experienceTable = document.getElementById('experience-table');
            if (experienceTable) {
                experienceTable.innerHTML = '<p>There was an error fetching the experience data.</p>';
            }
            console.log('Error fetching experience:', error.message); // Log the error for debugging
        });
}

function updateExperience(experienceId, userId, jobName, jobTitle, yearsWorked) {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Edit Experience</h2>
        <form id="update-experience-form">
            <label for="job_name">Company Name:</label>
            <input type="text" id="job_name" name="job_name" value="${jobName}" required>
            <label for="job_title">Title:</label>
            <input type="text" id="job_title" name="job_title" value="${jobTitle}" required>
            <label for="years_worked">Years Worked:</label>
            <input type="text" id="years_worked" name="years_worked" value="${yearsWorked}" required>
            <input type="hidden" name="experience_id" value="${experienceId}">
            <input type="hidden" name="user_id" value="${userId}">
            <button type="submit">Save</button>
        </form>
    `;

    const form = document.getElementById('update-experience-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('update_experience.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Server response:', result); // Log the server response
            if (result.success) {
                alert(result.message);
                insertIntoExperience(); // Refresh the experience form and table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.log('Error updating experience:', error.message);
            alert('Error updating experience.');
        });
    });
}

function deleteExperience(experienceId, userId) {
    if (!confirm("Are you sure you want to delete this experience?")) {
        return; // If the user cancels the confirmation, do nothing
    }

    fetch('delete_experience.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ experience_id: experienceId, user_id: userId })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            updateDeleteExperience(userId); // Refresh the experience table
        } else {
            alert(`Error: ${result.error}`);
        }
    })
    .catch(error => {
        console.log('Error deleting experience:', error.message);
        alert('Error deleting experience.');
    });
}


