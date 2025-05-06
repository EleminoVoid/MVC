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

        const email = document.querySelector('input[name="username"]').value;
        const password = document.querySelector('input[name="password"]').value;

        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password }) // Send JSON data
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('message');
            if (data.status === 200) {
                // Display success message or redirect
                messageDiv.style.color = 'green';
                messageDiv.innerText = 'Login successful! Token: ' + data.token; // Display token or redirect
            } else {
                // Display error message
                messageDiv.style.color = 'red';
                messageDiv.innerText = data.error; // Show error message
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
