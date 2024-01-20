<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../../../Database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_code = $_POST['course_code'];

    // Check if the user_token cookie is set
    if (isset($_COOKIE['user_token'])) {
        $token = $_COOKIE['user_token'];

        // Retrieve the student's ID using the token
        $stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows == 1) {
            $student_data = $user_result->fetch_assoc();
            $student_id = $student_data['id'];

            // Check if the course code exists
            $stmt = $conn->prepare("SELECT id FROM courses WHERE course_code = ?");
            $stmt->bind_param("s", $course_code);
            $stmt->execute();
            $course_result = $stmt->get_result();

            if ($course_result->num_rows == 1) {
                $course = $course_result->fetch_assoc();
                $course_id = $course['id'];

                // Check if the student is already enrolled in the course
                $enrollment_check = $conn->prepare("SELECT * FROM course_enrollments WHERE student_id = ? AND course_id = ?");
                $enrollment_check->bind_param("ii", $student_id, $course_id);
                $enrollment_check->execute();
                $enrollment_check_result = $enrollment_check->get_result();

                if ($enrollment_check_result->num_rows == 0) {
                    // The student is not enrolled in this course, proceed with enrollment
                    $enrollment_stmt = $conn->prepare("INSERT INTO course_enrollments (student_id, course_id) VALUES (?, ?)");
                    $enrollment_stmt->bind_param("ii", $student_id, $course_id);
                    if ($enrollment_stmt->execute()) {
                        echo "You have joined the class successfully!";
                    } else {
                        echo "Error: " . $enrollment_stmt->error;
                    }
                    $enrollment_stmt->close();
                } else {
                    echo "You are already enrolled in this course.";
                }
                $enrollment_check->close();
            } else {
                echo "Invalid course code.";
            }
            $stmt->close();
        } else {
            echo "Invalid token or user does not exist.";
        }
    } else {
        echo "User token is not set.";
    }
}

$conn->close();
?>
