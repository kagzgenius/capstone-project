/**
 * <!--
 * Creator: Zach Fordahl
 * Date: 10/26/2024
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

/**
 * insertIntoExperience
 * 
 * This function is used to insert new data into the experience table.
 * 
 * 1. Form Creation:
 *    It creates a form dynamically, allowing a user to input and submit new experience data.
 *    The form includes fields for job title, company name, years worked, and hidden fields for user ID and a flag to indicate the experience insertion.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls insert_data.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Calling insert_data.php:
 *    insert_data.php processes the form data and performs an INSERT operation 
 *    into the experience table based on the data provided by the user.
 *    The script checks if the form submission is valid using a $_POST['insert_experience'] 
 *    if statement. If the data is valid, it gets inserted into the experience table. 
 *    If there is an error during the insert operation, it throws an error message indicating 
 *    that the insertion failed.
 * 
 * 4. Updating the Experience Table:
 *    Upon successful insertion of the experience data, the function calls updateDeleteExperience 
 *    to show any inserts and create a table. This table allows the user to either update or delete their experience records.
 */


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

/**
 * updateDeleteExperience
 * 
 * This function takes on the userId from index.php and makes a fetch request 
 * to update_experience.php with the userId as a query parameter.
 * 
 * 1. Fetching Experience Data:
 *    It calls fetchExperience in update_experience.php to retrieve the experience data from 
 *    the experience table for the specified userId. The retrieved data is then used 
 *    to populate a form with the experience records associated with the logged-in user.
 * 
 * 2. Populating the Form:
 *    The function populates the experience table with the experience records and provides 
 *    buttons for updating and deleting each record.
 * 
 * 3. Handling Update and Delete:
 *    - If the user chooses to update an experience record, it sends the information to updateExperience.
 *    - updateExperience takes the information and displays it in a form where the user can update the record.
 *    - Upon submission, it sends the updated experience data back to update_experience.php to update the experience table with the changes made.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request, it displays an error message in the experience table and logs the error.
 */

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

/**
 * updateExperience
 * 
 * This function receives the experienceId, userId, job title, company name, and years worked 
 * from updateDeleteExperience and passes them to a form.
 * 
 * 1. Dynamic Form Creation:
 *    It dynamically creates a form to edit the selected experience record, pre-filling the form with the current 
 *    experience details. The form includes hidden fields for experienceId and userId.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls update_experience.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Processing the Update:
 *    The update_experience.php script uses the updateExperience function to update the experience table 
 *    with the changes made by the user.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request or the update operation, it logs the error 
 *    and displays an error message to the user.
 */

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

/**
 * deleteExperience
 * 
 * When updateDeleteExperience in experience.js gets called, it shows all the experience records associated with the user.
 * If the user selects the records they would like to delete, the userId and experienceId are passed to deleteExperience in experience.js.
 * 
 * 1. Confirmation Message:
 *    It sends a message to the user asking if they are sure they would like to delete the experience record.
 *    If the user confirms, the function proceeds with the deletion.
 * 
 * 2. Calling delete_experience.php:
 *    deleteExperience in experience.js then passes the userId and experienceId to delete_experience.php.
 * 
 * 3. Deleting the Record:
 *    - delete_experience.php calls database.php to get the database connection.
 *    - It constructs an SQL statement to delete the record corresponding to the experienceId and userId from the experience table.
 *    - The record is then removed from the experience table.
 * 
 * 4. Updating the Experience Table:
 *    After the deletion, updateDeleteExperience is called to refresh the experience table and show the changes.
 * 
 * 5. Displaying a Message:
 *    The function then shows a message stating that the experience record has been successfully deleted.
 */

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


