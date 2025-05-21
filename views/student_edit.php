<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="/styles.css">

</head>
<body>
    <h1>Edit Student</h1>
    <form action="/students/<?php echo $student['id']; ?>/edit" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <label>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required></label><br>
        <button type="submit">Update</button>
    </form>
    <a href="/students">Back to list</a>
</body>
</html>