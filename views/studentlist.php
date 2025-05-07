<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Students List</h1>
    </header>
    <main>
        <ul>
            <?php
            // Sample student data (replace with actual data from the database)
            $students = [
                ['name' => 'Student 1', 'email' => 'student1@example.com'],
                ['name' => 'Student 2', 'email' => 'student2@example.com'],
                ['name' => 'Student 3', 'email' => 'student3@example.com'],
            ];

            foreach ($students as $student) {
                echo "<li>{$student['name']} - {$student['email']}</li>";
            }
            ?>
        </ul>
        <a href="create_student.php">Create New Student</a>
    </main>
</body>
</html>
