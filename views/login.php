<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <div id="message" style="color: red;"></div> 
    <form id="loginForm">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>

    <p><a href="register">Register here.</a></p>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;

    fetch('/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('message');

        if (data.status === 200) {
            messageDiv.style.color = 'green';
            messageDiv.innerText = 'Login successful! Redirecting...';
            
            // Optionally store token in localStorage or cookies
            localStorage.setItem('token', data.token);

            // Redirect to home.php
            setTimeout(() => {
                window.location.href = '/home';
            }, 1000);
        } else {
            messageDiv.style.color = 'red';
            messageDiv.innerText = data.error || 'Login failed';
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
