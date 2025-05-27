<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Student</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body class="student-form-main">
    <header>
        <h2>Create Student</h2>
        <a href="/logout" style="float:right; margin-top:-2.5em; margin-right:1em;" class="logout-btn">Logout</a>
    </header>
    <h1>Create New Student</h1>
    <form action="/api/students" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Create</button>
    </form>
        <a href="/students">Back to list</a>

</body>
</html>