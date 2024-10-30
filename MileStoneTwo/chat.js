/**
 * chat.js
 * This script manages the chat functionalities for the messaging system, including loading 
 * user contact lists and handling message exchanges.
 * 
 * Functions include:
 * 
 * 1. `loadUserContactList`: 
 *    This function retrieves and displays a list of users who can be messaged. It calls 
 *    `loadContactDetails` to gather the necessary data.
 * 
 * 2. `loadContactDetails`: 
 *    This function calls `load-contact-list.php`, which fetches user IDs from the user 
 *    and profile database tables, populating the contact list with clickable user entries.
 * 
 * 3. `sendMessage`: 
 *    This function takes the selected user ID as a parameter and calls `send_message.php` 
 *    to facilitate sending a message to the chosen recipient.
 * 
 * 4. `loadChatWindow`: 
 *    When a user clicks on a contact, this function is invoked to check the message table 
 *    for any existing messages between the logged-in user and the selected user. It retrieves 
 *    the messages based on the sender ID (logged-in user) and receiver ID (selected user) 
 *    and displays them in the chat window.
 * 
 * 5. (Additional functions can be described here if applicable)
 */


function loadUserContactList() {
    const userContactList = document.querySelector('.user-contacts-list');
    fetch('load-contact-list.php')
        .then(response => response.json())
        .then(data => {
            if (data && !data.error) {
                userContactList.innerHTML = data.map(user => `
                    <div class="contact-container" data-user-id="${user.user_id}">
                        <div class="contact-item-image">
                            <img src="temp-profile-male.jpg" alt="profileImage">
                        </div>
                        <div class="contact-item">
                            ${user.first_name} ${user.last_name}
                        </div>
                    </div>
                `).join('');
                // Add click event listeners to each contact container
                document.querySelectorAll('.contact-container').forEach(container => {
                    container.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        console.log(`Contact item clicked. User ID: ${userId}`); // Log the user ID
                        loadContactDetails(userId); // Load contact details into dynamic-content
                    });
                });
            } else {
                userContactList.innerHTML = '<p>User contact not found.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching contact:', error);
        });
}





function loadContactDetails(userId) {
    const dynamicContent = document.getElementById('dynamic-content');

    var xhr = new XMLHttpRequest();
    xhr.open("GET", `get_messages.php?receiver=${userId}`, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log(data); // Log the data for debugging
            
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
                    <form id="message-form">
                        <input type="text" id="message-input" placeholder="Type your message here..." required>
                        <button type="submit">Send</button>
                    </form>
                </div>
            `;

            // Update the dynamic content
            dynamicContent.innerHTML = chatContent;

            // Initialize the message sending function
            sendMessage(userId);
        } else {
            console.error('Error fetching contact details:', this.statusText);
        }
    };

    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
}




function sendMessage(userId) {
    const messageForm = document.getElementById('message-form');
    messageForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value;
        console.log('Sending message:', message); // Log the message being sent
        // Send the message via AJAX
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
                    loadContactDetails(userId); // Reload chat to show new message
                } else {
                    console.error('Error sending message:', this.statusText);
                }
            }
        };
    });
}