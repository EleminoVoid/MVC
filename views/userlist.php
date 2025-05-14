<?php
namespace mvc\views;

use mvc\models\UserRepository;
use mvc\models\Database;

// Initialize the database connection
$db = new Database('localhost', 'root', 'root', 'UDB');
$userRepository = new UserRepository($db);

// Pagination parameters
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of items per page

// Fetch paginated users
$totalUsers = $userRepository->countAll();
$totalPages = ceil($totalUsers / $perPage);
$offset = ($currentPage - 1) * $perPage;
$users = $userRepository->getPaginated($offset, $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <header>
        <h1>Users List</h1>
    </header>
    <main>
        <div class="user-count">
            Showing <?= ($offset + 1) ?> to <?= min($offset + $perPage, $totalUsers) ?> of <?= $totalUsers ?> users
        </div>
        
        <ul class="user-list">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <li><?php echo htmlspecialchars($user['name']) . " - " . htmlspecialchars($user['email']); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No users found.</li>
            <?php endif; ?>
        </ul>

        <!-- Pagination Navigation -->
        <ul class="pagination">
            <?php if ($currentPage > 1): ?>
                <li><a href="?page=1">&laquo; First</a></li>
                <li><a href="?page=<?= $currentPage - 1 ?>">Previous</a></li>
            <?php endif; ?>

            <?php 
            // Show page numbers (with some surrounding context)
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            
            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li>
                    <a href="?page=<?= $i ?>" <?= $i == $currentPage ? 'class="active"' : '' ?>>
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <li><a href="?page=<?= $currentPage + 1 ?>">Next</a></li>
                <li><a href="?page=<?= $totalPages ?>">Last &raquo;</a></li>
            <?php endif; ?>
        </ul>

        <div class="actions">
            <a href="create_user.php" class="btn">Create New User</a>
        </div>
    </main>
</body>
</html>