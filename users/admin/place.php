<?php
include '../../config.php';
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Place List</title>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

    <!-- Bootstrap modal form for adding place -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Place</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="crud.php">
                        <div class="form-group">
                            <label for="addPlaceName">Place Name:</label>
                            <input type="text" class="form-control" id="addPlaceName" name="addPlaceName">
                        </div>
                        <button type="submit" class="btn btn-primary" name="addPlace">Add Place</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap modal form for editing place -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Place</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="crud.php">
                        <div class="form-group" hidden>
                            <label for="editPlaceID">ID:</label>
                            <input type="text" class="form-control" id="editPlaceID" name="editPlaceID" readonly>
                        </div>
                        <div class="form-group">
                            <label for="editPlaceName">Place Name:</label>
                            <input type="text" class="form-control" id="editPlaceName" name="editPlaceName">
                        </div>
                        <button type="submit" class="btn btn-primary" name="editPlace">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap modal form for deleting place -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="crud.php">
                        <div class="form-group" hidden>
                            <label for="deletePlaceID">ID:</label>
                            <input type="text" class="form-control" id="deletePlaceID" name="deletePlaceID" readonly>
                        </div>
                        <p>Are you sure you want to delete this place?</p>
                        <button type="submit" class="btn btn-danger" name="deletePlace">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">

        <div class="row" style="margin-bottom: 1%;">
            <div class="col-md-6">
                <button class="btn btn-primary btn-sm btn-add-place">Add Place</button>
            </div>

            <div class="col-md-6">
                <?php
                if (isset($_SESSION['type']) && $_SESSION['type'] == 'success') {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['msg']; ?>
                    </div>
                    <?php
                }
                unset($_SESSION['type']);
                unset($_SESSION['msg']);
                ?>
            </div>
        </div>


        <table id="myTable" class="display table" width="100%">
            <thead>
                <tr>
                    <th hidden>ID</th>
                    <th>PLACE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM place";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td hidden><?php echo $row['id']; ?> </td>
                            <td><?php echo $row['place']; ?> </td>
                            </td>
                            <td>
                                <button class="btn btn-success edit-btn btn-sm" data-id="<?php echo $row['id']; ?>"
                                    data-name="<?php echo $row['place']; ?>">Edit</button>

                                <button class="btn btn-danger delete-btn btn-sm" data-id="<?php echo $row['id']; ?>"
                                    data-name="<?php echo $row['place']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No place found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#myTable').dataTable();

            // Show modal form when "Register new place" button is clicked
            $('.btn-register').click(function () {
                $('#myModal').modal('show');
            });

            // Edit button click event
            $('.edit-btn').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#editPlaceID').val(id);
                $('#editPlaceName').val(name);
                $('#editModal').modal('show');
            });

            // Delete button click event
            $('.delete-btn').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#deletePlaceID').val(id);
                $('#deleteModal').modal('show');
            });

            // Add Place button click event
            $('.btn-add-place').click(function () {
                $('#addModal').modal('show');
            });
        });
    </script>


</body>

</html>