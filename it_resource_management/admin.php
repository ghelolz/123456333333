<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "it_resource_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Resource
if (isset($_POST['resource_name'])) {
    $resource_name = $_POST['resource_name'];
    $type = $_POST['type'];
    $specification = $_POST['specification'];
    $status = $_POST['status'];
    $allocated_to = $_POST['allocated_to'];
    $date_allocated = $_POST['date_allocated'];

    // Get the maximum ID from the resources table
    $result = $conn->query("SELECT MAX(id) as max_id FROM resources");
    $row = $result->fetch_assoc();
    $new_id = $row['max_id'] + 1;

    $sql = "INSERT INTO resources (id, resource_name, type, specification, status, allocated_to, date_allocated)
            VALUES ('$new_id', '$resource_name', '$type', '$specification', '$status', '$allocated_to', '$date_allocated')";

    $conn->query($sql);
}

// Delete Resource
if (isset($_POST['delete_resource'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM resources WHERE id=$id";
    $conn->query($sql);

    // Reorder IDs after deletion/////////////////////////////////////////////////////////////////////////////////////////
    $result = $conn->query("SELECT id FROM resources ORDER BY id ASC");
    $new_id = 1;
    while ($row = $result->fetch_assoc()) {
        $current_id = $row['id'];
        $conn->query("UPDATE resources SET id=$new_id WHERE id=$current_id");
        $new_id++;
    }
}

// Clear All Data
if (isset($_POST['clear_all'])){
    $sql = "DELETE FROM resources";
    $conn->query($sql);

}

// Fetch all resources
$result = $conn->query("SELECT * FROM resources");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>IT Resource Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>

<script type="text/javascript">
    function confirmClearAll(){
        return confirm('Are you sure you want to delete all data?')
    }
</script>
</head>
<body>

<h3>Add New Resource</h3>
<form action="admin.php" method="post">
    <input type="text" name="resource_name" placeholder="Resource Name" required>
    <input type="text" name="type" placeholder="Type" required>
    <input type="text" name="specification" placeholder="Specification" required>
    <input type="text" name="status" placeholder="Status" required>
    <input type="text" name="allocated_to" placeholder="Allocated To">
    <input type="date" name="date_allocated">
    <input type="submit" name="add_resource" value="Add Resource">
</form>

<h3>Resources</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Resource Name</th>
        <th>Type</th>
        <th>Specification</th>
        <th>Status</th>
        <th>Allocated To</th>
        <th>Date Allocated</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['resource_name']; ?></td>
            <td><?php echo $row['type']; ?></td>
            <td><?php echo $row['specification']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['allocated_to']; ?></td>
            <td><?php echo $row['date_allocated']; ?></td>
            <td>
            <form style="display:inline;" action="edit_resource.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="submit" value="Edit">
                </form>

                <form style="display:inline;" action="admin.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="delete_resource" value="Delete">
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<form action="admin.php" method="post" onsubmit="return confirmClearAll()">
    <input type="submit" name="clear_all" value="Clear All">
</form>

<p><a href="index.php">Back to Resource List</a></p>

<?php
$conn->close();
?>

</body>
</html>