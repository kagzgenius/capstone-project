
/**
 * 
 * <!--
 * Creator: Zach Fordahl
 * Date: 10/12/2024
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

/**
 * insertIntoEducation
 * 
 * This function is used to insert new data into the education table.
 * 
 * 1. Form Creation:
 *    It creates a form dynamically, allowing a user to input and submit new education data.
 *    The form includes fields for school name, degree obtained, and hidden fields for user ID 
 *    and a flag to indicate the education insertion.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls insert_data.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Calling insert_data.php:
 *    insert_data.php processes the form data and performs an INSERT operation 
 *    into the education table based on the data provided by the user.
 *    The script checks if the form submission is valid using a $_POST['insert_education'] 
 *    if statement. If the data is valid, it gets inserted into the education table. 
 *    If there is an error during the insert operation, it throws an error message indicating 
 *    that the insertion failed.
 * 
 * 4. Updating the Education Table:
 *    Upon successful insertion of the education data, the function calls updateDeleteEducation 
 *    to show any inserts and create a table. This table allows the user to either update or delete their education records.
 */

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

/**
 * updateDeleteEducation
 * 
 * This function takes on the userId from index.php and makes a fetch request 
 * to update_education.php with the userId as a query parameter.
 * 
 * 1. Fetching Education Data:
 *    It calls fetchEducation in update_education.php to retrieve the education data from 
 *    the education table for the specified userId. The retrieved data is then used 
 *    to populate a form with the education records associated with the logged-in user.
 * 
 * 2. Populating the Form:
 *    The function populates the education table with the education records and provides 
 *    buttons for updating and deleting each record.
 * 
 * 3. Handling Update and Delete:
 *    - If the user chooses to update an education record, it sends the information to updateEducation.
 *    - updateEducation takes the information and displays it in a form where the user can update the record.
 *    - Upon submission, it sends the updated education data back to update_education.php to update the education table with the changes made.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request, it displays an error message in the education table and logs the error.
 */

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
/**
 * updateEducation
 * 
 * This function receives the educationId, userId, school name, degree obtained, and years attended 
 * from updateDeleteEducation and passes them to a form.
 * 
 * 1. Dynamic Form Creation:
 *    It dynamically creates a form to edit the selected education record, pre-filling the form with the current 
 *    education details. The form includes hidden fields for educationId and userId.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls update_education.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Processing the Update:
 *    The update_education.php script uses the updateEducation function to update the education table 
 *    with the changes made by the user.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request or the update operation, it logs the error 
 *    and displays an error message to the user.
 */

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
/**
 * deleteEducation
 * 
 * When updateDeleteEducation in education.js gets called, it shows all the education records associated with the user.
 * If the user selects the records they would like to delete, the userId and educationId are passed to deleteEducation in education.js.
 * 
 * 1. Confirmation Message:
 *    It sends a message to the user asking if they are sure they would like to delete the education record.
 *    If the user confirms, the function proceeds with the deletion.
 * 
 * 2. Calling delete_education.php:
 *    deleteEducation in education.js then passes the userId and educationId to delete_education.php.
 * 
 * 3. Deleting the Record:
 *    - delete_education.php calls database.php to get the database connection.
 *    - It constructs an SQL statement to delete the record corresponding to the educationId and userId from the education table.
 *    - The record is then removed from the education table.
 * 
 * 4. Updating the Education Table:
 *    After the deletion, updateDeleteEducation is called to refresh the education table and show the changes.
 * 
 * 5. Displaying a Message:
 *    The function then shows a message stating that the education record has been successfully deleted.
 */

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