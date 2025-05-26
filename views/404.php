<?php
use mvc\services\SessionAuthService;

$isAuth = SessionAuthService::isAuthenticated();
$redirectUrl = $isAuth ? '/home' : '/login';
$msg = $isAuth
    ? "Page not found. Redirecting to homepage..."
    : "Page not found. Redirecting to login...";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found</title>
    <meta http-equiv="refresh" content="2;url=<?= $redirectUrl ?>">
    <link rel="stylesheet" href="/styles.css">
</head>
<body id="notfound-main">
    <h1>404 Not Found</h1>
    <p><?= htmlspecialchars($msg) ?></p>
</body>
</html>