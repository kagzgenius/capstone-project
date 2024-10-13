
<!--
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
 The messaging system uses get_contacts to get the user contact info. It uses send_message.php to send to the database. It also uses get_messages.php
 to get the messages from the user. Upload_cover_pic.php and upload_profile.php. DatabaseConnection.php creates a database, creates tables and then database.php 
 calls to get connection and upload_cover_pic.php,Upload_profile_pic, get_messages.php, get_contacts.php,send_message.php use database to get the connection for the
 database. 
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'database.php';

/**
 * The below sql calls to the database are used to grab the profile and user table information.
 * This is then used to populate the current user signed in UserID, name,profile_pic, cover_pic information
 * in the messaging program. 
 */
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM `users` WHERE id ='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch profile data
$query2 = "SELECT * FROM `profile` WHERE user_id ='$user_id'";
$result2 = mysqli_query($conn, $query2);
$profile = mysqli_fetch_assoc($result2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Messaging</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .chat-contents-container {
            max-width: 1100px;
            min-height: 700px;
            display: flex;
            margin: auto;
            padding-top: 100px;
        }

        .left-contents-pannel {
            min-height: 300px;
            background-color: #2c3e50;
            flex: 1;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
        }

        .left-contents-pannel img {
            width: 80%;
            border: solid thin white;
            border-radius: 50%;
            margin: 20px;
        }

        .left-contents-pannel label {
            width: 100%;
            height: 30px;
            display: block;
            background-color: #404b56;
            margin: 9px;
            border-radius: 12px;
            transition: .4s;
        }

        .left-contents-pannel label:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: solid thin red;
        }

        .right-contents-pannel {
            min-height: 500px;
            background-color: #ecf0f1;
            flex: 4;
            text-align: center;
        }

        .right-content-pannel-header {
            background-color: #3498db;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0px 20px;
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-left: 20px;
        }

        .right-content-pannel-header-container {
            display: flex;
            flex: 1;
            padding: 20px;
        }

        .right-content-pannel-header-container-inner-left-pannel {
            display: flex;
            flex-direction: column;
            min-height: 530px;
            background-color: #ffffff;
            flex: 1;
            margin-right: 10px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .right-content-pannel-header-container-inner-right-pannel {
            min-height: 530px;
            background-color: #ffffff;
            flex: 2;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: .4s;
            display: flex;
            flex-direction: row-reverse;
        }

        .chat-username {
            font-size: 20px;
            font-weight: bold;
        }

        .chat-title {
            font-size: 12px;
            font-style: italic;
        }

        .right-contents-pannel img {
            width: 10%;
            border: solid thin white;
            border-radius: 50%;
            margin: 20px;
        }

        /* Flex properties based on checked radio button */
        #right-content-pannel-header-container-inner-left-contacts:checked ~ .right-content-pannel-header-container-inner-right-pannel {
            flex: 0;
        }

        #right-content-pannel-header-container-inner-left-setting:checked ~ .right-content-pannel-header-container-inner-right-pannel {
            flex: 0;
        }

        .right-content-pannel-header-container-inner-right-pannel {
            flex: 8;
            transition: .4s;
        }

        /* Border color classes */
        .border-blue {
            border: 2px solid #3498db;
        }

        .border-red {
            border: 2px solid red;
        }

        .border-black {
            border: 2px solid black;
        }

        .contacts-container {
    display: flex;
    flex-direction: column; 
    max-height: 500px; 
    overflow-y: auto; /* Enable vertical scrolling if content exceeds max height */
    border: 2px solid #000; /* Adjust the border color and width as needed */
    padding: 10px; 
    box-sizing: border-box; /* Ensure padding and border are included in the width */
}

.contact-box {
    width: 100%; /* Make each contact box take up the full width of the container */
    border: 2px solid #000; /* Adjust the border color and width as needed */
    padding: 10px; /* Optional: Add some padding inside the box */
    box-sizing: border-box; /* Ensure padding and border are included in the width */
    display: flex; /* Use Flexbox to align items */
    align-items: center; /* Align items vertically in the center */
    margin-bottom: 10px; /* Optional: Add some space between the contact boxes */
    background-color: #2c3e50;
    color: #ecf0f1;
    transition: .5s;
    border-radius: 5%;
    display: block;
    margin-bottom: 10px;
}

.contact-box:hover,.contact-box .contact-image:hover {
    transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: solid 2px red;
            transition: .5s;
}
.contact-box .contact-image {
    width: 100px; /* Adjust the width as needed */
    height: 100px; /* Adjust the height as needed */
    border: solid  thin white; /* Add a black border around the image */
    margin-right: 10px; /* Optional: Add some space to the right of the image */
}

.contact-box .contact-info .contact-name {
    font-size: 8em; /* Increase the font size of the names */
    font-weight: bold; /* Optional: Make the names bold */
}

.contact-boxTwo {
    width: 30%; /* Make each contact box take up the full width of the container */
    height: 30%; /* Adjust the height as needed */
    border: 1px solid #ccc; /* Adjust the border color and width as needed */
    padding: 5px; /* Optional: Add some padding inside the box */
    box-sizing: border-box; /* Ensure padding and border are included in the width */
    display: flex; /* Use Flexbox to align items */
    align-items: center; /* Align items vertically in the center */
    margin-bottom: 5px; /* Optional: Add some space between the contact boxes */
    background-color: #f5f5f5; /* Light background color */
    color: #333; /* Dark text color */
    transition: 0.3s; /* Smooth transition for hover effects */
    border-radius: 10px; /* Rounded corners */
}

.contact-boxTwo:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border: 1px solid #007bff; /* Change border color on hover */
}

.contact-boxTwo .contact-imageTwo {
    width: 30px; /* Adjust the width to make the image smaller */
    height: 30px; /* Adjust the height to make the image smaller */
    border-radius: 50%; /* Make the image round */
    margin-right: 10px; /* Optional: Add some space to the right of the image */
}

.contact-boxTwo .contact-info {
    flex-grow: 1; /* Allow the contact info to take up remaining space */
}

.contact-boxTwo .contact-info p {
    margin: 0; /* Remove default margin */
    font-size: 12px; /* Adjust the font size to make the text smaller */
    font-weight: bold; /* Optional: Make the names bold */
}

.scrollable-box {
    width: 500px; /* Adjust the width as needed */
    height: 400px; /* Adjust the height as needed */
    overflow-y: scroll; /* Make the container scrollable */
    border: 1px solid #ccc; /* Optional: Add a border for better visibility */
    padding: 10px; /* Optional: Add padding for better spacing */
    background-color: #f9f9f9; /* Background color for the scrollable box */
    margin-left: 20px; /* Add some space between the contact boxes and the scrollable box */
    border-radius: 8px; /* Optional: Add rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
}

.button {
    background-color: #4CAF50; /* Green background */
    border: none; /* Remove borders */
    color: white; /* White text */
    padding: 10px 20px; /* Add some padding */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline */
    display: inline-block; /* Make the button inline */
    font-size: 16px; /* Increase font size */
    margin: 4px 2px; /* Add some margin */
    cursor: pointer; /* Add a pointer cursor on hover */
    border-radius: 8px; /* Optional: Add rounded corners */
    transition: background-color 0.3s ease; /* Optional: Add transition for hover effect */
}

.button:hover {
    background-color: #45a049; /* Darker green on hover */
}

.right-content-pannel-header-container-inner-right-pannel {
    display: flex;
    flex-direction: column; /* Arrange items in a row */
    justify-content: space-between; /* Space between the contact boxes and the scrollable box */
}

.Chat-Room-profile-container{
    display: flex;
    flex-direction: column;
    width: 100%;
}
.Chat-Room-profile-container-Upper{
    border: 2px solid red;
    flex: 1;
    width: 100%;
}
.Chat-Room-profile-container-lower{
    border: 2px solid blue;
    flex: 2;
    width: 100%;
}

    </style>
</head>
<body>
<!-- Navbar.php. Bring the template navigation to the page. -->
    <?php include('navbar.php'); ?> <!--Black header and navigation menu with Home,Messaging, profile,Admin and logout-->

    <div class="chat-contents-container"><!-- Container that holds all of different parts of messaging system-->
        <div class="left-contents-pannel">
<!-- This section is used to get userName, title and first and last name calls the user table and profile table to generate-->
<img src="uploads/<?php echo isset($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic']) : 'default.png'; ?>" alt="profilePic">
            <p class="chat-username"><?php echo htmlspecialchars($user['username']); ?></p>
            <p class="chat-title"><?php echo htmlspecialchars($profile['job_title']); ?></p>
            <p class="chat-name"><?php echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']); ?></p>

            <br>
            <div>
<!-- When you click on these lables it sends action to the radio buttons below and causes the screen to move to the Chat,Profile and settings-->
                <label for="right-content-pannel-header-container-inner-left-chat" name="right-content-pannel-header-container-inner-left-chat-id">Profile</label>
                <label for="right-content-pannel-header-container-inner-left-contacts" name="right-content-pannel-header-container-inner-left-contacts-id">Contacts and Chat</label>
                <label for="right-content-pannel-header-container-inner-left-setting" name="right-content-pannel-header-container-inner-left-setting-id">Settings</label>
            </div>
        </div>
        <div class="right-contents-pannel">
            <div class="right-content-pannel-header">
                <img src="Logo.png" alt="profilePic">
                Chat Room
            </div>
            <div class="right-content-pannel-header-container">
                <div class="right-content-pannel-header-container-inner-left-pannel" id="content-displayTwo">
                    <!-- Left panel content -->
                </div>
<!--The below radio buttions through flex cause the screen to move. If you look to the CSS it will show various flex:0-4 to move the screen a certain amount-->
                <input type="radio" id="right-content-pannel-header-container-inner-left-chat" name="myradio">
                <input type="radio" id="right-content-pannel-header-container-inner-left-contacts" name="myradio">
                <input type="radio" id="right-content-pannel-header-container-inner-left-setting" name="myradio">
                <div class="right-content-pannel-header-container-inner-right-pannel" id="content-display">
                <img src="uploads/<?php echo isset($_SESSION['cover_pic']) ? htmlspecialchars($_SESSION['cover_pic']) : 'default.png'; ?>" alt="coverpic" style="width:90%; height:90%; border-radius:0;"name="coverImage">
                

                  
                </div>
               
            </div>
        </div>
    </div>
    <script>
        /** when the radio buttons are clicked it will passthe ID content-display or content-displaytwo. This will tell
         * the screen to send the document to one of those two areas if you look below at the if else statment
         * it then sends This is where it chooses to send to profile,chat or settings and changes the screen border
         */
  document.querySelectorAll('input[name="myradio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        var contentDisplay = document.getElementById('content-display');
        var contentDisplayTwo = document.getElementById('content-displayTwo');
        
        // Reset border colors
        contentDisplay.classList.remove('border-blue');
        contentDisplayTwo.classList.remove('border-red', 'border-black');

        if (this.id === 'right-content-pannel-header-container-inner-left-chat') {
            contentDisplay.innerHTML = '<h1>Chat</h1>';
            contentDisplay.classList.add('border-blue');
            contentDisplayTwo.innerHTML = ''; // Clear content-displayTwo
        } else if (this.id === 'right-content-pannel-header-container-inner-left-contacts') {
    contentDisplayTwo.innerHTML = `<h1>Contacts</h1>
                                    <div id="contacts-container" class="contacts-container"></div>`;
    contentDisplayTwo.classList.add('border-black');
    contentDisplay.innerHTML = ''; // Clear content-display

    var ajax = new XMLHttpRequest();
    var method = "GET";
    var url = "get_contacts.php";
    var asynch = true;

    ajax.open(method, url, asynch);
    ajax.send();
    /**Calling the program get_contacts. get_contacts does a SQL to profile page and grabs the following: id, user_id, first_name, last_name,profile_pic
     * The below fuction has a for loop and grabs all users from the profile page. It looks with an if statement if
     * the profile page user is the user_id of the person that is signed in and ignores if so because this function below is how all
     * the people in the contact list gets generated and to not allow the person that is signed in to be one from the list
     */
    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log(data);
            var html = "";
            for (var a = 0; a < data.length; a++) {
                var firstName = data[a].first_name;
                var lastName = data[a].last_name;
                var userId = data[a].user_id;
                var profilePicture = data[a].profile_pic;//generates the profile pic of if in profile table
                if(userId!=<?php echo $_SESSION['user_id']; ?>){//remove the user_id of person signed in
                html += `<div class="contact-box" data-user-id="${userId}" data-first-name="${firstName}" data-last-name="${lastName}">
                            <img src="uploads/${profilePicture}" alt="Contact Image" class="contact-image">
                            <div class="contact-info">
                                <p>${firstName} ${lastName}</p>
                            </div>
                         </div>`;}
            }//sends the document to contact-container to be displayed for the users
            document.getElementById("contacts-container").innerHTML = html;

            // Add event listeners to contact boxes
            var contactBoxes = document.getElementsByClassName('contact-box');
            for (var i = 0; i < contactBoxes.length; i++) {
                contactBoxes[i].addEventListener('click', function() {
                    var userId = this.getAttribute('data-user-id');
                    var firstName = this.getAttribute('data-first-name');
                    var lastName = this.getAttribute('data-last-name');
                    console.log('User ID:', userId);
                    console.log('First Name:', firstName);
                    console.log('Last Name:', lastName);

                    // Display the user information in the target div
                    var targetDiv = document.getElementById('content-display'); // Replace 'target-div' with the actual ID of your target div
                    targetDiv.innerHTML = `
                                           <h3>Chat with ${firstName} ${lastName}</h3>
                                           <div id="scrollable-box" class="scrollable-box">
                                                <!-- Chat messages will go here -->
                                               
                                           </div>
                                           <div id="message-input-container">
                                                <form id="message-form">
                                                    <input type="text" id="message-input" placeholder="Type your message here..." required>
                                                    <button type="submit">Send</button>
                                                </form>
                                           </div>`;

                    // Fetch and display messages between the two users and displays it in the scrollable-box
                    var chatContainer = document.getElementById('scrollable-box');
                    var chatAjax = new XMLHttpRequest();
                    var chatMethod = "GET";
                    var chatUrl = `get_messages.php?sender=<?php echo $_SESSION['user_id']; ?>&receiver=${userId}`; // Adjust the URL as needed
                    var chatAsynch = true;

                    console.log('Fetching messages for sender:', '<?php echo $_SESSION['user_id']; ?>', 'and receiver:', userId);

                    chatAjax.open(chatMethod, chatUrl, chatAsynch);
                    chatAjax.send();


                    /**Shows sender and receiver profile box and messages */
                    chatAjax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var chatData = JSON.parse(this.responseText);
        console.log('Fetched messages:', chatData);
        var chatHtml = "";
        for (var b = 0; b < chatData.length; b++) {
            var message = chatData[b].message;
            var sender = chatData[b].sender;
            var receiver = chatData[b].receiver;

            // Determine the sender's contact info
            var senderInfo = data.find(user => user.user_id == sender);
            //var receiverInfo = data.find(user => user.user_id == receiver);

            // Display sender's message
          /** Below is how the box that shows the name and the message gets populated. SenderInfo.UserID,senderInfo.firstName and senderInfo.lastname calls the message table 
           * if get_message.php has a request for the senderId or recieverid it displays the message
          */
            chatHtml += `<div class="chat-message">
                            <div class="contact-boxTwo" data-user-id="${senderInfo.user_id}" data-first-name="${senderInfo.first_name}" data-last-name="${senderInfo.last_name}">
                                <img src="profile pic.png" alt="Contact Image" class="contact-imageTwo">
                                
                                <div class="contact-info">
                                    <p>${senderInfo.first_name} ${senderInfo.last_name}</p>
                                </div>
                            </div>
                            <p><strong>${senderInfo.first_name}:</strong> ${message}</p>
                         </div>`;

        }
        chatContainer.innerHTML = chatHtml;
    }
};




                    // Handle form submission to send a message
                    /**If someone sends a message from the text box available and hits the submit button the message get pushed to send_message.php.
                     * this then will insert into the table. 
                     */
                    var messageForm = document.getElementById('message-form');
                    messageForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        var messageInput = document.getElementById('message-input');
                        var message = messageInput.value;

                        var sendAjax = new XMLHttpRequest();
                        var sendMethod = "POST";
                        var sendUrl = "send_message.php"; // Adjust the URL as needed
                        var sendAsynch = true;

                        sendAjax.open(sendMethod, sendUrl, sendAsynch);
                        sendAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        sendAjax.send(`sender=<?php echo $_SESSION['user_id']; ?>&receiver=${userId}&message=${message}`);

                        sendAjax.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                console.log('Message sent:', message);
                                messageInput.value = ''; // Clear the input field

                                // Fetch and update the chat messages
                                chatAjax.open(chatMethod, chatUrl, chatAsynch);
                                chatAjax.send();
                            }
                        };
                    });
                });
            }
        }
    };
}


 else if (this.id === 'right-content-pannel-header-container-inner-left-setting') {
    contentDisplayTwo.innerHTML = '<h1>Settings</h1>';
    contentDisplayTwo.classList.add('border-red');
    contentDisplay.innerHTML = ''; // Clear content-display
}

    });
});
</script>





</body>
</html>