<!DOCTYPE html>
<html>
<head>
    <title>Create Student</title>
    <link rel="stylesheet" href="/styles.css">

</head>
<body>
    <h1>Create Student</h1>
    <form action="/students/create" method="POST">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Create</button>
    </form>
    <a href="/students">Back to list</a>
</body>
</html>