<?php

if (isset($_GET['id'])){
    $id = $_GET['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "it_resource_management";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM resources WHERE id=$id";
    $result = $conn->query($sql);
    

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p><strong>Resource Name:</strong> " . ($row['resource_name']) . "</p>";
        echo "<p><strong>Type:</strong> " . ($row['type']) . "</p>";
        echo "<p><strong>Specification:</strong> " . ($row['specification']) . "</p>";
        echo "<p><strong>Status:</strong> " . ($row['status']) . "</p>";
        echo "<p><strong>Allocated to:</strong> " . ($row['allocated_to']) . "</p>";
        echo "<p><strong>Date allocated:</strong> " . ($row['date_allocated']) . "</p>";
    } else {
        echo "<p>Resource not found.</p>";
    }

    // Close the statement
    $conn->close();
} else {
    echo "<p>No valid resource ID provided.</p>";
}
