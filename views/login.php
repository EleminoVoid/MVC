<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <div id="message" style="color: red;"></div> <!-- Div to display messages -->
    <form id="loginForm">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const username = document.querySelector('input[name="username"]').value; // Get username
        const password = document.querySelector('input[name="password"]').value;

        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password }) // Send JSON data
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('message');
            // Always display the JSON response
            messageDiv.innerText = JSON.stringify(data); // Display the entire JSON response

            if (data.status === 200) {
                messageDiv.style.color = 'green';
                // Optionally, you can redirect or perform other actions here
            } else {
                messageDiv.style.color = 'red'; // Keep the error message in red
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('message').innerText = 'An error occurred. Please try again.';
        });
    });
    </script>
</body>
</html>
