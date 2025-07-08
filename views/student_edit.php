<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body class="student-form-main">
    <header>
        <h2>Edit Student</h2>
        <a href="/logout" style="float:right; margin-top:-2.5em; margin-right:1em;" class="logout-btn">Logout</a>
    </header>
    <h1>Edit Student</h1>
    <form action="/students/<?= $student['id'] ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" >
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" >
        <button type="submit">Save</button>
    </form>
    <a href="/students">Back to list</a>
</body>
</html>