
/**
 * education.js
 * This script is called when a user navigates to the profile page and uses the 
 * `loadProfileNavBar`. It manages user education entries, including adding, 
 * updating, and deleting education data.
 * 
 * Functions include:
 * 
 * 1. `loadEducation`: 
 *    This function is invoked by the navigation bar and simply calls the 
 *    `insertIntoEducation` function to handle the process of adding new 
 *    education data.
 * 
 * 2. `insertIntoEducation`: 
 *    This function displays a form pre-filled with the user ID and fields from the 
 *    education database table, allowing the user to add new education data. 
 *    Upon submission, it calls `insert_data.php`, which inserts the new education 
 *    entry into the education table.
 * 
 * 3. `updateDeleteEducation`: 
 *    This function retrieves education data by calling `update_education.php`, 
 *    performing a select statement to load all entries from the education table. 
 *    It presents the data in an HTML table with options to update or delete each entry.
 * 
 * 4. `updateEducation`: 
 *    When the update button is clicked, this function is called with the education ID, 
 *    user ID, institution name, degree, and years attended. It populates a form with 
 *    the existing education data, allowing the user to edit the fields. Upon saving, 
 *    the updated data is sent back to `update_education.php` to update the education table.
 * 
 * 5. `deleteEducation`: 
 *    When the delete button is clicked, this function prompts the user for confirmation. 
 *    If confirmed, it calls `delete_education.php` to remove the record from the database.
 */


function education() {
    insertIntoEducation();
}

function insertIntoEducation() {
    console.log('User ID:', userId); // Log the user ID for debugging

    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Education</h2>
        <form id="education-form">
            <label for="school_name">School Name:</label>
            <input type="text" id="school_name" name="school_name" required>
            <label for="degree_obtained">Degree Obtained:</label>
            <input type="text" id="degree_obtained" name="degree_obtained" required>
            <label for="years_attended">Years Attended:</label>
            <input type="text" id="years_attended" name="years_attended" required>
            <input type="hidden" name="Education_userId" value="${userId}">
            <input type="hidden" name="insert_education" value="true">
            <button type="submit">Submit</button>
        </form>
        <div id="education-table"></div> <!-- Placeholder for the education table -->
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
                updateDeleteEducation(userId); // Refresh the education table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.log('Error inserting education:', error.message);
            alert('Error inserting education.');
        });
    });

    console.log(`Calling updateDeleteEducation with user ID: ${userId}`); // Log the call
    updateDeleteEducation(userId); // Use the correct user ID
}
function updateDeleteEducation(userId) {
    fetch(`update_education.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const educationTable = document.getElementById('education-table');
            if (educationTable) {
                if (data.error) {
                    educationTable.innerHTML = '<p>Error fetching Education.</p>';
                    console.log('Error fetching Education:', data.error);
                    return;
                }
                if (data.education.length === 0) {
                    educationTable.innerHTML = '<p>No education found.</p>';
                    return;
                }
                educationTable.innerHTML = `
                    <h2>Update Education</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>Degree</th>
                                <th>Years Attended</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.education.map(education => `
                                <tr>
                                    <td>${education.school_name}</td>
                                    <td>${education.degree_obtained}</td>
                                    <td>${education.years_attended}</td>
                                    <td><button onclick="updateEducation(${education.id}, ${education.user_id}, '${education.school_name}', '${education.degree_obtained}', '${education.years_attended}')">Update</button></td>
                                    <td><button onclick="deleteEducation(${education.id}, ${education.user_id})">Delete</button></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
        })
        .catch(error => {
            const educationTable = document.getElementById('education-table');
            if (educationTable) {
                educationTable.innerHTML = '<p>There was an error fetching the education.</p>';
            }
            console.log('Error fetching education:', error.message);
        });
}

function updateEducation(id, userId, schoolName, degreeObtained, yearsAttended) {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Edit Education</h2>
        <form id="update-education-form">
            <label for="school_name">School Name:</label>
            <input type="text" id="school_name" name="school_name" value="${schoolName}" required>
            <label for="degree_obtained">Degree Obtained:</label>
            <input type="text" id="degree_obtained" name="degree_obtained" value="${degreeObtained}" required>
            <label for="years_attended">Years Attended:</label>
            <input type="text" id="years_attended" name="years_attended" value="${yearsAttended}" required>
            <input type="hidden" name="education_id" value="${id}">
            <input type="hidden" name="user_id" value="${userId}">
            <button type="submit">Save</button>
        </form>
    `;

    const form = document.getElementById('update-education-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('update_education.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                insertIntoEducation(); // Re-render the insert form and education table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.log('Error updating education:', error.message);
            alert('Error updating education.');
        });
    });
}

function deleteEducation(id, userId) {
    if (!confirm("Are you sure you want to delete this education record?")) {
        return; // If the user cancels the confirmation, do nothing
    }

    fetch('delete_education.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ education_id: id, user_id: userId })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            updateDeleteEducation(userId); // Refresh the education table
        } else {
            alert(`Error: ${result.error}`);
        }
    })
    .catch(error => {
        console.log('Error deleting education:', error.message);
        alert('Error deleting education.');
    });
}