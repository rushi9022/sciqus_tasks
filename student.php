<?php
session_start();

// Redirect if not authenticated or not a student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['user']['username'];
$userEmail = $_SESSION['user']['email'];

// Database connection (replace with your actual connection details)
require 'db.php';

// Fetch student details
$stmt = $conn->prepare("SELECT s.student_id, s.student_name, c.course_name, c.course_code, c.course_duration 
                        FROM students s 
                        JOIN courses c ON s.course_id = c.course_id
                        WHERE s.email = ?");
$stmt->execute([$userEmail]);
$studentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$studentDetails) {
    // If no details found, redirect or show an error message
    echo "No student details found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Welcome to Student Dashboard</h1>
    <p>Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>

    <!-- Student Details Section -->
    <div class="mt-4">
        <h3>Your Details:</h3>
        <ul class="list-group">
            <li class="list-group-item"><strong>Student Name:</strong> <?= htmlspecialchars($studentDetails['student_name']) ?></li>
            <li class="list-group-item"><strong>Course Name:</strong> <?= htmlspecialchars($studentDetails['course_name']) ?></li>
            <li class="list-group-item"><strong>Course Code:</strong> <?= htmlspecialchars($studentDetails['course_code']) ?></li>
            <li class="list-group-item"><strong>Course Duration:</strong> <?= htmlspecialchars($studentDetails['course_duration']) ?></li>
        </ul>
    </div>
</div>
</body>
</html>
