<?php
use mvc\services\SessionAuthService;
session_start();
if (SessionAuthService::isAuthenticated()) {
    header('Refresh: 2; url=/home');
    $msg = "Page not found. Redirecting to homepage...";
} else {
    header('Refresh: 2; url=/login');
    $msg = "Page not found. Redirecting to login...";
}
?>
<!DOCTYPE html>
<html>
<head><title>404 Not Found</title></head>
<body id="notfound-main">
    <h1>404 Not Found</h1>
    <p><?= $msg ?></p>
</body>
</html>