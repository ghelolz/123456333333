<?php
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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: Username already exists. Please choose another.";
    } else {
        // Proceed with registration
        $stmt->close(); // Close the previous statement

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Register">
</form>

<p>Log in now <a href="login.php">Log in</a></p>