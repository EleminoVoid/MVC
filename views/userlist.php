<?php
namespace mvc\views;

use mvc\models\UserRepository;
use mvc\models\DBORM;

// Initialize the database connection
$db = new DBORM('localhost', 'root', 'root', 'UDB');
$userRepository = new UserRepository($db);

// Fetch all users
$users = $userRepository->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Users List</h1>
    </header>
    <main>
        <ul>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <li><?php echo htmlspecialchars($user['name']) . " - " . htmlspecialchars($user['email']); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No users found.</li>
            <?php endif; ?>
        </ul>
        <a href="create_user.php">Create New User</a>
    </main>
</body>
</html>
