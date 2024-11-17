
/**Creator: Zach Fordahl
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
 *  */

     /**
 * This script handles the upload of a user's profile picture.
 * 
 * 1. Form Submission Event Listener:
 *    - The script adds an event listener to the 'upload-form' form element.
 *    - When the form is submitted, it prevents the default form submission behavior.
 * 
 * 2. Creating FormData Object:
 *    - It creates a new FormData object to hold the file data.
 *    - It gets the file input element with the ID 'profile_pic' and appends the selected file to the FormData object.
 * 
 * 3. Sending the File:
 *    - The script uses the fetch API to send the FormData object to `upload_profile_pic.php` via a POST request.
 * 
 * 4. Handling the Response:
 *    - If the response is successful, it displays a success message and updates the profile image in the user interface.
 *    - It selects the 'upload-status' element and updates its inner text to indicate a successful upload.
 *    - It updates the profile image by selecting the element with the class 'user-profile-image' and setting its inner HTML to display the new profile picture.
 * 
 * 5. Error Handling:
 *    - If there is an error, the script logs the error message to the console.
 *    - It updates the 'upload-status' element's inner text to display an error message indicating that the upload failed.
 */

document.getElementById('upload-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData();
    const fileInput = document.getElementById('profile_pic');
    formData.append('profile_pic', fileInput.files[0]);

    fetch('upload_profile_pic.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            document.getElementById('upload-status').innerText = 'Upload successful!';
            // Update the profile image
            const profileImageDiv = document.querySelector('.user-profile-image');
            profileImageDiv.innerHTML = `<img src="uploads/${result.fileName}" alt="profilePic">`;
        } else {
            document.getElementById('upload-status').innerText = `Error: ${result.error}`;
        }
    })
    .catch(error => {
        console.log('Error uploading image:', error.message);
        document.getElementById('upload-status').innerText = 'Error uploading image.';
    });
});
