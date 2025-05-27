<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="/styles.css">
    <style>

    </style>
</head>
<body class="student-form-main">
    <div class="delete-center-wrapper delete-confirm-box">
        <h1>Delete Student</h1>
        <p>Are you sure you want to delete <strong><?= htmlspecialchars($student['name']) ?></strong>?</p>
        <form action="/api/students/<?= $student['id'] ?>?page=<?= isset($_GET['page']) ? (int)$_GET['page'] : 1 ?>" method="POST" style="display:inline;" class="delete-form">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="delete-btn">Yes, Delete</button>
        </form>
        <button onclick="window.location.href='/students?page=<?= isset($_GET['page']) ? (int)$_GET['page'] : 1 ?>'">Cancel</button>
    </div>
</body>
</html>