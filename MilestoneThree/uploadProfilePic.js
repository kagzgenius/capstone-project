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
