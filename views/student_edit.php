<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body class="student-form-main">
    <h1>Edit Student</h1>
    <form action="/api/students/<?= $student['id'] ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        <button type="submit">Save</button>
    </form>
    <a href="/students">Back to list</a>
</body>
</html>