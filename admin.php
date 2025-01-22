<?php
session_start();

// Redirect if not authenticated or not an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['user']['username'];
require 'db.php';

// Fetch Courses
$courses = $conn->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Students
$students = $conn->query("SELECT s.student_id, s.student_name, s.email, c.course_name 
                          FROM students s 
                          LEFT JOIN courses c ON s.course_id = c.course_id")->fetchAll(PDO::FETCH_ASSOC);

// Handle Add Student Form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['student_name'];
    $email = $_POST['student_email'];
    $password = $_POST['student_password'];
    $course_id = $_POST['student_course'];

    // Insert into `users` table
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, 'student', ?)");
    $stmt->execute([$email, $password, $email]);

    // Insert into `students` table
    $stmt = $conn->prepare("INSERT INTO students (student_name, email, course_id) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $course_id]);

    $message = "Student added successfully!";
}

// Handle Edit Student Details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['edit_student_name'];
    $email = $_POST['edit_student_email'];
    $course_id = $_POST['edit_student_course'];

    // Update `students` table
    $stmt = $conn->prepare("UPDATE students SET student_name = ?, email = ?, course_id = ? WHERE student_id = ?");
    $stmt->execute([$name, $email, $course_id, $student_id]);

    // Update `users` table
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE email = ?");
    $stmt->execute([$email, $email, $_POST['original_email']]);

    $message = "Student details updated successfully!";
}

// Handle Add Course Form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $course_duration = $_POST['course_duration'];

    // Insert into `courses` table
    $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, course_duration) VALUES (?, ?, ?)");
    $stmt->execute([$course_name, $course_code, $course_duration]);

    $message = "Course added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Admin Dashboard</h1>
    <p>Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>

    <?php if (isset($message)): ?>
        <div class="alert alert-success mt-4"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Add Student and Course Buttons -->
    <div class="mt-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add Student</button>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
    </div>

    <!-- Student List -->
    <h3 class="mt-4">All Students</h3>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['course_name']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editStudentModal" 
                            data-student-id="<?= $student['student_id'] ?>" 
                            data-student-name="<?= htmlspecialchars($student['student_name']) ?>" 
                            data-student-email="<?= htmlspecialchars($student['email']) ?>" 
                            >Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Name</label>
                        <input type="text" name="student_name" id="studentName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentEmail" class="form-label">Email</label>
                        <input type="email" name="student_email" id="studentEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentPassword" class="form-label">Password</label>
                        <input type="text" name="student_password" id="studentPassword" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentCourse" class="form-label">Course</label>
                        <select name="student_course" id="studentCourse" class="form-select" required>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="courseName" class="form-label">Course Name</label>
                        <input type="text" name="course_name" id="courseName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="courseCode" class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="courseCode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="courseDuration" class="form-label">Course Duration</label>
                        <input type="text" name="course_duration" id="courseDuration" class="form-control" required>
                    </div>
                    <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="student_id" id="editStudentId">
                    <div class="mb-3">
                        <label for="editStudentName" class="form-label">Name</label>
                        <input type="text" name="edit_student_name" id="editStudentName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStudentEmail" class="form-label">Email</label>
                        <input type="email" name="edit_student_email" id="editStudentEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStudentCourse" class="form-label">Course</label>
                        <select name="edit_student_course" id="editStudentCourse" class="form-select" required>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="original_email" id="originalEmail">
                    <button type="submit" name="edit_student" class="btn btn-primary">Update Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass student data to the edit modal
    $('#editStudentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var studentId = button.data('student-id');
        var studentName = button.data('student-name');
        var studentEmail = button.data('student-email');
        var studentCourseId = button.data('student-course-id');
        
        var modal = $(this);
        modal.find('#editStudentId').val(studentId);
        modal.find('#editStudentName').val(studentName);
        modal.find('#editStudentEmail').val(studentEmail);
        modal.find('#editStudentCourse').val(studentCourseId);
        modal.find('#originalEmail').val(studentEmail);
    });
</script>

</body>
</html>
