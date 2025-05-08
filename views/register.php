<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form id="registerForm">
        <label for="id">ID:</label>
        <input type="text" name="ID" required>
        <br>
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Register</button>
    </form>
    <p><a href="login">Login here.</a></p>

    <p id="message"></p>

    <script>
        const form = document.getElementById('registerForm');
        const message = document.getElementById('message');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.status === 201) {
                    message.textContent = response.message;
                    message.style.color = 'green';
                    form.reset();
                } else {
                    message.textContent = response.error || 'Registration failed';
                    message.style.color = 'red';
                }
            })
            // .catch(error => {
            //     message.textContent = 'An error occurred. Please try again.';
            //     message.style.color = 'red';
            //     console.error(error);
            // });
        });
    </script>
</body>
</html>
