<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../../Database/db_connection.php';
function getUserCourses($conn, $token) {
    $courses = [];
    $stmt = $conn->prepare("SELECT u.id, u.username, c.title, c.description, c.image_url 
                            FROM users u
                            JOIN course_enrollments ce ON u.id = ce.student_id
                            JOIN courses c ON ce.course_id = c.id
                            WHERE u.token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    return $courses;
}
?>
