<?php
include '../Database/db_connection.php'; // Include the database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $user_email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $user_password = isset($_POST['password']) ? $_POST['password'] : '';
    $user_role = isset($_POST['role']) ? $_POST['role'] : '';

    $user_password = password_hash($user_password, PASSWORD_DEFAULT); // Hash password

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $user_username, $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or Email already exists.";
        $stmt->close();
        $conn->close();
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user_username, $user_email, $user_password, $user_role);

    // Execute and check
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>