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
        <a href="/logout" style="float:right; margin-top:-2.5em; margin-right:1em;" class="logout-btn">Logout</a>
    </header>
    <main id="student-list-main">
        <button onclick="window.location.href='/home'">Go Back Home</button>
        <div class="student-table-wrapper">
            <ul class="student-table">
                <li class="student-table-header">
                    <span>Name</span>
                    <span>Email</span>
                    <span>Actions</span>
                </li>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <li class="student-table-row">
                            <span><?= htmlspecialchars($student['name']) ?></span>
                            <span><?= htmlspecialchars($student['email']) ?></span>
                            <span>
                                <a href="/students/<?= $student['id'] ?>/edit">Edit</a>
                                <a href="/students/<?= $student['id'] ?>/delete" >Delete</a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><span colspan="3">No students found.</span></li>
                <?php endif; ?>
            </ul>
        </div>
        <a href="/students/create">Create New Student</a>
        <div class="pagination">
            <?= $paginationLinks ?>
        </div>
    </main>
</body>
</html>
