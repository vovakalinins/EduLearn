<?php
// db_connection.php
$servername = "localhost";
$username = "fitzsoft_eduser";
$password = "X0!=1l_Z#&hv";
$dbname = "fitzsoft_edulearn";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
