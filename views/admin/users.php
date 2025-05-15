<?php
// if (!isset($_SESSION['user'])) {
//     header('Location: /login'); // Redirect to login if not authenticated
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>Users List</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>