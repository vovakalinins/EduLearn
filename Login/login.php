<?php
include '../Database/db_connection.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $user_password = isset($_POST['password']) ? $_POST['password'] : '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Invalid username.";
    } else {
        $user = $result->fetch_assoc();
        if (password_verify($user_password, $user['password'])) {
            $token = bin2hex(random_bytes(64)); // Generate a secure token
            setcookie("user_token", $token, time() + (86400 * 30), "/"); // Set cookie for 30 days
            // Save the token in the database
            $stmt = $conn->prepare("UPDATE users SET token = ? WHERE username = ?");
            $stmt->bind_param("ss", $token, $user_username);
            $stmt->execute();

            echo "Login successful!";
            // Start a session and set user data here if needed
        } else {
            echo "Invalid password.";
        }
    }

    $stmt->close();
    $conn->close();
}
