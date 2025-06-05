<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main class="login-register-main">
        <form action="/api/login" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" >
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" >
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/register">Register here</a></p>
    </main>
</body>
</html>
