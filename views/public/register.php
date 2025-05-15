<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="views\public\styles.css">
</head>
<body>
    <div id="modal" class="modal hidden">
        <div class="modal-content">
            <p id="modalMessage">Registered successfully!</p>
        </div>
    </div>

    <div class="container">
        <h1>Register</h1>
        <form id="registerForm">
            <label for="id">ID:</label>
            <input type="text" name="ID" required>
            
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login">Login here.</a></p>
        <p id="message"></p>
    </div>

    <script>
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
        .then(res => {
            return res.json().then(body => {
                return { status: res.status, body };
            });
        })
        .then(({ status, body }) => {
            if (status === 201) {
                form.reset();
                document.getElementById('modalMessage').textContent = body.message || 'Registration successful!';
                document.getElementById('modal').classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload(); // or use window.location.href = '/login';
                }, 2000);
            } else {
                message.textContent = body.error || 'Registration failed';
                message.style.color = 'red';
            }
        })
        .catch(error => {
            message.textContent = 'An error occurred. Please try again.';
            message.style.color = 'red';
            console.error(error);
        });
    });


    </script>
</body>
</html>