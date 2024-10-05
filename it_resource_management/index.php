<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Resource Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>IT Resources</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Resource Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "it_resource_management";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id, resource_name, type, status FROM resources";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['resource_name'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#resourceModal' data-id='" . $row['id'] . "'>Select</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No resources found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="resourceModal" tabindex="-1" aria-labelledby="resourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resourceModalLabel">Resource Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $('#resourceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var resourceID = button.data('id');

        $.ajax({
            url: 'get_resource_details.php',
            type: 'GET',
            data: { id: resourceID },
            success: function(data) {
                $('#modal-body').html(data);
            },
            error: function() {
                $('#modal-body').html('<p>Error loading resource details.</p>');
            }
        });
    });
</script>
</body>
</html>