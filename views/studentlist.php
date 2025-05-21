<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Students List</h1>
    </header>
    <main>
        <ul class="student-list">
            <?php foreach ($students as $student): ?>
                <li>
                    <?= htmlspecialchars($student['name']) ?> - <?= htmlspecialchars($student['email']) ?>
                    <div class="actions">
                        <a href="/students/<?= $student['id'] ?>/edit" class="btn">Edit</a>
                        <form action="/students/<?= $student['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Delete this student?')">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="/students/create">Create New Student</a>
    </main>
</body>
</html>
