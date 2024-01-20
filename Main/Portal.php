<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../Database/db_connection.php';

function checkToken($conn, $token) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return false; // Token not found in database
    }
    return true; // Token is valid
}

if (!isset($_COOKIE["user_token"]) || !checkToken($conn, $_COOKIE["user_token"])) {
    header("Location: ../Login/index.html"); // Redirect to login if not logged in
    exit;
} else {
    $token = $_COOKIE["user_token"];
    $stmt = $conn->prepare("SELECT role FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $role = $user['role']; // Fetch the role from the result

        if ($role == "Teacher") {
            header("Location: Teacher/index.html");
            exit;
        } else {
            header("Location: Student/index.html");
            exit;
        }
    } else {
        // Handle the case where the role is not found
        echo "User role not found.";
        exit;
    }
}
?>
