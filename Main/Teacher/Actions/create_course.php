<?php
include '../../../Database/db_connection.php';
include 'generate_course_code.php'; // Assume this is where the function generateCourseCode is stored

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $teacher_id = $_POST['teacher_id'];
    $course_code = generateCourseCode(); // Auto-generate the course code
    
    // Check for and create the uploads directory
    $uploads_dir = '../../../uploads';
    if (!file_exists($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }

    // Handle the uploaded file
    $target_file = $uploads_dir . '/' . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $target_file = basename($_FILES["image"]["name"]);

    // Insert into the database
    // Use a valid teacher_id for the logged-in teacher
    $stmt = $conn->prepare("INSERT INTO courses (title, description, image_url, teacher_id, course_code) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $description, $target_file, $teacher_id, $course_code);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "New course created successfully with code: $course_code";
    } else {
        // Handle any SQL errors here
        echo "SQL Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
