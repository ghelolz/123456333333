<?php
session_start();

$servername = "localhost";
$username = "root"; // Your DB username
$password = ""; // Your DB password
$dbname = "it_resource_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            // Redirect to index.php
            header("Location: admin.php");
            exit(); // Ensure no further code is executed
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>

<form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>
