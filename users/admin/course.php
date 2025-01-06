<?php  
    include '../../config.php';
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course List</title>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>


    <!-- Bootstrap modal form for registering a new course -->
<div id="registerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Register New Course</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="crud.php">
                    <div class="form-group">
                        <label for="registerCourseCode">Code:</label>
                        <input type="text" class="form-control" id="registerCourseCode" name="registerCourseCode">
                    </div>
                    <div class="form-group">
                        <label for="registerCourseName">Name:</label>
                        <input type="text" class="form-control" id="registerCourseName" name="registerCourseName">
                    </div>
                    <button type="submit" class="btn btn-primary" name="registerCourse">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap modal form for editing course -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Course</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="crud.php">
                    <div class="form-group">
                        <label for="editCourseID">ID:</label>
                        <input type="text" class="form-control" id="editCourseID" name="editCourseID" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editCourseCode">Code:</label>
                        <input type="text" class="form-control" id="editCourseCode" name="editCourseCode">
                    </div>
                    <div class="form-group">
                        <label for="editCourseName">Name:</label>
                        <input type="text" class="form-control" id="editCourseName" name="editCourseName">
                    </div>
                    <button type="submit" class="btn btn-primary" name="editCourse">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap modal form for deleting course -->
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
                    <div class="form-group">
                        <label for="deleteCourseID">ID:</label>
                        <input type="text" class="form-control" id="deleteCourseID" name="deleteCourseID" readonly>
                    </div>
                    <p>Are you sure you want to delete this course?</p>
                    <button type="submit" class="btn btn-danger" name="deleteCourse">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">
    <div class="row" style="margin-bottom: 0.5%;">
        <div class="col-md-6">
            <button class="btn btn-primary btn-sm btn-register-course">Register new course</button>
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
            <th>CODE</th>
            <th>COURSE</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM course";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
                <td hidden><?php echo $row['id']; ?> </td>   
                <td><?php echo $row['code']; ?> </td> 
                <td><?php echo $row['name']; ?> </td>
                </td>
                <td>
                    <button class="btn btn-success edit-btn btn-sm" data-id="<?php echo $row['id']; ?>" data-code="<?php echo $row['code']; ?>" data-name="<?php echo $row['name']; ?>">Edit</button>

                    <button class="btn btn-danger delete-btn btn-sm" data-id="<?php echo $row['id']; ?>" data-code="<?php echo $row['code']; ?>" data-name="<?php echo $row['name']; ?>">Delete</button>
                </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='4'>No courses found.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('#myTable').dataTable();

        // Edit button click event
        $('.edit-btn').click(function(){
            var id = $(this).data('id');
            var code = $(this).data('code');
            var name = $(this).data('name');
            $('#editCourseID').val(id);
            $('#editCourseCode').val(code);
            $('#editCourseName').val(name);
            $('#editModal').modal('show');
        });

        // Delete button click event
        $('.delete-btn').click(function(){
            var id = $(this).data('id');
            var code = $(this).data('code');
            var name = $(this).data('name');
            $('#deleteCourseID').val(id);
            $('#deleteModal').modal('show');
        });

        // Register new course button click event
        $('.btn-register-course').click(function(){
            $('#registerModal').modal('show');
        });

    });
</script>

</body>
</html>
