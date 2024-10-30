
/**
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