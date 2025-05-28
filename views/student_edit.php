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
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_SESSION['flash_error']); ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
    <form action="/api/students/<?= $student['id'] ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>" required>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        <button type="submit">Save</button>
    </form>
    <a href="/students">Back to list</a>
</body>
</html>