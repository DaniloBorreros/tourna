<?php  
    include '../../config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor List</title>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">
    <div class="row" style="margin-bottom: 0.5%;">
        <div class="col-md-6">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSportModal">Add Sport</button>
        </div>
        <div class="col-md-6">
            <?php
            session_start();
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
            <th>SPORT</th>
            <th>ELIMINATION</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM sport";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td hidden><?php echo $row['id']; ?></td>    
            <td><?php echo $row['name']; ?></td>
            <td>
                <?php
                    if ($row['elimination'] == 1){
                        echo "Single Elimination";
                    }
                    elseif ($row['elimination'] == 2){
                        echo "Double Elimination";
                    }
                ?>
            </td>
            <td>
                <button class="btn btn-success edit-btn btn-sm" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>">Edit</button>
                <button class="btn btn-danger delete-btn btn-sm" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>">Delete</button>
            </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='3'>No sports found.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>


<!-- Add Sport Modal -->
<div class="modal fade" id="addSportModal" tabindex="-1" role="dialog" aria-labelledby="addSportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSportModalLabel">Add Sport</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="crud.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sportName">Sport Name:</label>
                        <input type="text" class="form-control" id="sportName" name="sportName" required>
                    </div>
                    <div class="form-group">
                        <label for="elimination">Elimination:</label>
                        <select class="form-control" id="elimination" name="elimination">
                            <option value="1">Single Elimination</option>
                            <option value="2">Double Elimination</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="addSport">Add Sport</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Sport</h4>
            </div>
            <form method="post" action="crud.php">
                <div class="modal-body">
                    <div class="form-group" hidden>
                        <label for="edit-id">ID:</label>
                        <input type="text" class="form-control" id="edit-id" name="editSportID" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit-name">Name:</label>
                        <input type="text" class="form-control" id="edit-name" name="sportName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveChanges" name="editSport">Save Changes</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm Delete</h4>
            </div>
            <form method="post" action="crud.php">
                <div class="modal-body">
                    <div class="form-group" hidden>
                        <label for="delete-id">ID:</label>
                        <input type="text" class="form-control" id="delete-id" name="deleteSportID" readonly>
                    </div>
                    <div class="form-group">
                        <label for="delete-sport-name">Sport Name:</label>
                        <span id="delete-sport-name"></span>
                    </div>
                    <p>Are you sure you want to delete this sport?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDelete" name="deleteSport">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#myTable').dataTable();

        // Edit button click event
        $('.edit-btn').click(function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#edit-id').val(id);
            $('#edit-name').val(name);
            $('#editModal').modal('show');
        });

        // Delete button click event
        $('.delete-btn').click(function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-id').val(id);
            $('#delete-sport-name').text(name);
            $('#deleteModal').modal('show');
        });

        // Confirm delete button click event
        $('#confirmDelete').click(function(){
            var id = $('#delete-id').val();
            // Perform delete operation using AJAX or form submission
            // Once deleted, reload the page or update the table using AJAX
        });
    });
</script>

</body>
</html>
