<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h1 {
            color: #495057;
        }
        .btn-custom {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-login {
            background-color: #007bff;
        }
        .btn-logout {
            background-color: #dc3545;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Student Management System</h1>

    <!-- Dynamic Login/Logout Button -->
    <div class="text-center mt-4">
        <?php if (isset($_SESSION['user'])): ?>
            <!-- User is logged in, show logout button -->
            <a href="logout.php" class="btn btn-custom btn-logout">Logout</a>
        <?php else: ?>
            <!-- User is not logged in, show login button -->
            <a href="login.php" class="btn btn-custom btn-login">Login</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
