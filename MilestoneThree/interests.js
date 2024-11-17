
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
     
 * interests.js
 * This script is loaded when a user navigates to the profile page and clicks on Interests. 
 * It is invoked by the `loadProfileNavBar` function and manages user interests, including 
 * adding, updating, and deleting interests.
 * 
 * Functions include:
 * 
 * 1. `loadInterests`: 
 *    This function is invoked by the navigation bar and simply calls the 
 *    `insertIntoInterests` function to handle the process of adding new 
 *    interests.
 * 
 * 2. `insertIntoInterests`: 
 *    This function displays a form for the signed-in user to add new interests. It retrieves 
 *    the user ID of the signed-in user and, upon submission, calls `insert_data.php`, passing 
 *    the form data to insert the new interest into the interests table.
 * 
 * 3. `updateDeleteInterests`: 
 *    This function accepts the user ID as a parameter and calls `update_interest.php`. It 
 *    performs a select statement to retrieve interests associated with the user ID and displays 
 *    them in a table with options to update or delete each interest.
 * 
 * 4. `updateInterest`: 
 *    When the update button is clicked, this function is called with the user ID, interest ID, 
 *    and interest name. It populates a form with the existing interest data, allowing the user 
 *    to edit the information. Upon submission, the updated data is sent back to `update_interest.php` 
 *    to update the database.
 * 
 * 5. `deleteInterest`: 
 *    When the delete button is clicked, this function prompts the user for confirmation. If 
 *    confirmed, it calls `delete_interest.php` to remove the record from the database.
 */


function interests() {
    insertIntoInterests();
}

/**
 * insertIntoInterest
* 
* This function is used to insert new data into the interest table.
* 
* 1. Form Creation:
*    It creates a form dynamically, allowing a user to input and submit new interest data.
*    The form includes fields for interest name and hidden fields for user ID and a flag to indicate the interest insertion.
* 
* 2. Handling Form Submission:
*    When the form is submitted, the function calls insert_data.php via a fetch API request.
*    The form data is sent using a POST request.
* 
* 3. Calling insert_data.php:
*    insert_data.php processes the form data and performs an INSERT operation 
*    into the interest table based on the data provided by the user.
*    The script checks if the form submission is valid using a $_POST['insert_interest'] 
*    if statement. If the data is valid, it gets inserted into the interest table. 
*    If there is an error during the insert operation, it throws an error message indicating 
*    that the insertion failed.
* 
* 4. Updating the Interest Table:
*    Upon successful insertion of the interest data, the function calls updateDeleteInterest 
*    to show any inserts and create a table. This table allows the user to either update or delete their interests.
*/

function insertIntoInterests() {
    console.log('User ID:', userId); // Log the user ID for debugging

    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Add Your Interests</h2>
        <form id="interests-form">
            <label for="interest_name">Interest:</label>
            <input type="text" id="interest_name" name="interest_name" required>
            <input type="hidden" name="Interest_userId" value="${userId}">
            <input type="hidden" name="insert_interest" value="true">
            <button type="submit">Submit</button>
        </form>
        <div id="interest-table"></div> <!-- Placeholder for the interest table -->
    `;

    const form = document.getElementById('interests-form'); // Corrected form ID
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
                updateDeleteInterest(userId); // Refresh the interest table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.log('Error inserting interest:', error.message);
            alert('Error inserting interest.');
        });
    });

    console.log(`Calling updateDeleteInterest with user ID: ${userId}`); // Log the call
    updateDeleteInterest(userId); // Use the correct user ID
}
/**
 * updateDeleteInterest
 * 
 * This function takes on the userId from index.php and makes a fetch request 
 * to update_interest.php with the userId as a query parameter.
 * 
 * 1. Fetching Interest Data:
 *    It calls fetchInterest in update_interest.php to retrieve the interest data from 
 *    the interest table for the specified userId. The retrieved data is then used 
 *    to populate a form with the interests associated with the logged-in user.
 * 
 * 2. Populating the Form:
 *    The function populates the interest table with the interest names and provides 
 *    buttons for updating and deleting each interest.
 * 
 * 3. Handling Update and Delete:
 *    - If the user chooses to update an interest, it sends the interest information to updateInterest.
 *    - updateInterest takes the information and displays it in a form where the user can update the interest.
 *    - Upon submission, it sends the updated interest data back to update_interest.php to update the interest table with the changes made.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request, it displays an error message in the interest table and logs the error.
 */

function updateDeleteInterest(userId) {
    fetch(`update_interests.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const interestTable = document.getElementById('interest-table');
            if (interestTable) {
                if (data.error) {
                    interestTable.innerHTML = '<p>Error fetching interest.</p>';
                    console.log('Error fetching interest:', data.error);
                    return;
                }
                if (data.interests.length === 0) {
                    interestTable.innerHTML = '<p>No interest found.</p>';
                    return;
                }
                interestTable.innerHTML = `
                    <h2>Update Interests</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Interest</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.interests.map(interest => `
                                <tr>
                                    <td>${interest.interest_name}</td>
                                    <td><button onclick="updateInterest(${interest.id}, ${interest.user_id}, '${interest.interest_name}')">Update</button></td>
                                    <td><button onclick="deleteInterest(${interest.id}, ${interest.user_id})">Delete</button></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
        })
        .catch(error => {
            const interestTable = document.getElementById('interest-table');
            if (interestTable) {
                interestTable.innerHTML = '<p>There was an error fetching the interests.</p>';
            }
            console.log('Error fetching interests:', error.message);
        });
}

/**
 * updateInterest
 * 
 * This function receives the interestId, userId, and interest name from updateDeleteInterest 
 * and passes them to a form.
 * 
 * 1. Dynamic Form Creation:
 *    It dynamically creates a form to edit the selected interest, pre-filling the form with the current 
 *    interest name. The form includes hidden fields for interestId and userId.
 * 
 * 2. Handling Form Submission:
 *    When the form is submitted, the function calls update_interest.php via a fetch API request.
 *    The form data is sent using a POST request.
 * 
 * 3. Processing the Update:
 *    The update_interest.php script uses the updateInterest function to update the interest table 
 *    with the changes made by the user.
 * 
 * 4. Error Handling:
 *    If there is an error during the fetch request or the update operation, it logs the error 
 *    and displays an error message to the user.
 */

function updateInterest(interestId, userId, interestName) {
    const dynamicContent = document.getElementById('dynamic-content');
    dynamicContent.innerHTML = `
        <h2>Edit Interest</h2>
        <form id="update-interest-form">
            <label for="interest_name">Interest:</label>
            <input type="text" id="interest_name" name="interest_name" value="${interestName}" required>
            <input type="hidden" name="interest_id" value="${interestId}">
            <input type="hidden" name="user_id" value="${userId}">
            <button type="submit">Save</button>
        </form>
    `;

    const form = document.getElementById('update-interest-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('update_interests.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Server response:', result); // Log the server response
            if (result.success) {
                alert(result.message);
                insertIntoInterests(); // Refresh the interest form and table
            } else {
                alert(`Error: ${result.error}`);
            }
        })
        .catch(error => {
            console.log('Error updating interest:', error.message);
            alert('Error updating interest.');
        });
    });
}
/**
 * deleteInterest
 * 
 * When updateDeleteInterest in interest.js gets called, it shows all the interests associated with the user.
 * If the user selects the interests they would like to delete, the userId and interestId are passed to deleteInterest in interest.js.
 * 
 * 1. Confirmation Message:
 *    It sends a message to the user asking if they are sure they would like to delete the interest.
 *    If the user confirms, the function proceeds with the deletion.
 * 
 * 2. Calling delete_interest.php:
 *    deleteInterest in interest.js then passes the userId and interestId to delete_interest.php.
 * 
 * 3. Deleting the Record:
 *    - delete_interest.php calls database.php to get the database connection.
 *    - It constructs an SQL statement to delete the record corresponding to the interestId and userId from the interest table.
 *    - The record is then removed from the interest table.
 * 
 * 4. Updating the Interest Table:
 *    After the deletion, updateDeleteInterest is called to refresh the interest table and show the changes.
 * 
 * 5. Displaying a Message:
 *    The function then shows a message stating that the interest has been successfully deleted.
 */

function deleteInterest(interestId, userId) {
    if (!confirm("Are you sure you want to delete this interest?")) {
        return;
    }

    fetch('delete_interest.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ interest_id: interestId, user_id: userId })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            updateDeleteInterest(userId); // Refresh the interests table
        } else {
            alert(`Error: ${result.error}`);
        }
    })
    .catch(error => {
        console.log('Error deleting interest:', error.message);
        alert('Error deleting interest.');
    });
}