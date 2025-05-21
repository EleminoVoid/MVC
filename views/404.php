<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found</title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        body { text-align: center; padding: 50px; }
        h1 { font-size: 3em; color: #d9534f; }
        p { font-size: 1.2em; }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1em;
            cursor: pointer;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
    <script>
        function goBackOrHome() {
            if (window.history.length > 1) {
                window.history.back();
                setTimeout(function() {
                    if (document.title === "404 Not Found") {
                        window.location.href = "/";
                    }
                }, 500);
            } else {
                window.location.href = "/homepage";
            }
        }
    </script>
</head>
<body>
    <h1>404</h1>
    <p>Sorry, the page you are looking for does not exist.</p>
    <button class="back-btn" onclick="goBackOrHome();">Go Back</button>
</body>
</html>