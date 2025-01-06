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
    <table id="myTable" class="display table" width="100%">
        <thead>
        <tr>
            <th hidden>USER ID</th>
            <th>LASTNAME</th>
            <th>FIRSTNAME</th>
            <th>MIDDLENAME</th>
            <th>EMAIL</th>
            <th>Yr</th>
            <th>COURSE</th>
            <th>ATHLETE?</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM users WHERE athlete = 0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                if ($row['year'] == 1) {
                    $yr = '1st';
                } elseif ($row['year'] == 2) {
                    $yr = '2nd';
                } elseif ($row['year'] == 3) {
                    $yr = '3rd';
                } elseif ($row['year'] == 4) {
                    $yr = '4th';
                }

                // Fetch course name from the 'courses' table
                $course_id = $row['course'];
                $course_sql = "SELECT name FROM course WHERE id = $course_id";
                $course_result = $conn->query($course_sql);
                if ($course_result && $course_result->num_rows > 0) {
                    $course_row = $course_result->fetch_assoc();
                    $course_name = $course_row['name'];
                } else {
                    $course_name = 'Unknown'; // Default value if course not found
                }
        ?>
        <tr>
            <td hidden><?php echo $row['id']; ?> </td>    
            <td><?php echo $row['lastname']; ?> </td>
            <td><?php echo $row['firstname']; ?> </td>
            <td><?php echo $row['middlename']; ?> </td>
            <td><?php echo $row['email']; ?> </td>
            <td><?php echo $yr.' year '; ?></td>
            <td><?php echo $course_name; ?> </td>
            <td><?php echo $row['athlete'] == 1 ? 'Yes' : 'No'; ?></td>
            <td>
                <button class="btn btn-success btn-sm edit-btn" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#editModal">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#deleteModal">Delete</button>
            </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='7'>No student found.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="crud.php">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-group">
                        <label for="editLastName">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="editFirstName">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="editMiddleName">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="middleName" required>
                    </div>
                    <div class="form-group">
                        <label for="editYear">Year</label>
                        <input type="number" class="form-control" id="editYear" name="year" min="1" max="4" required>
                    </div>
                    <div class="form-group">
                        <label for="editCourse">Course</label>
                        <select class="form-control" id="editCourse" name="course" required>
                            <!-- Options will be populated dynamically using PHP -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editAthlete">Athlete?</label>
                        <select class="form-control" id="editAthlete" name="athlete" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group" id="sportDropdown" style="display: none;">
                        <label for="editSport">Sport</label>
                        <select class="form-control" id="editSport" name="sport">
                            <!-- Options will be populated dynamically using PHP -->
                        </select>
                    </div>
                    <div class="form-group" id="teamDropdownList">
                        <select class="form-control" id="teamDropdown" name="team">
                            <!-- Options will be populated dynamically using PHP -->
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="editUser">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deleteForm" method="post" action="crud.php">
                    <div class="form-group" hidden>
                        <label for="deleteId">User ID</label>
                        <input type="text" class="form-control" id="deleteId" name="id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="deleteFullName">Full Name</label>
                        <input type="text" class="form-control" id="deleteFullName" name="fullName" readonly>
                    </div>
                    <p>Are you sure you want to delete this user?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" name="deleteUser">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    $('#myTable').dataTable();

    // Edit button click event
    $('.edit-btn').click(function(){
        var id = $(this).data('id');
        // AJAX request to fetch user data
        $.ajax({
            url: 'fetch_user.php',
            type: 'POST',
            data: {id: id},
            success: function(response){
                var data = JSON.parse(response);
                $('#editId').val(data.id);
                $('#editLastName').val(data.lastName);
                $('#editFirstName').val(data.firstName);
                $('#editMiddleName').val(data.middleName);
                $('#editYear').val(data.year);
                // Populate course dropdown options
                $('#editCourse').empty();
                $.each(data.courses, function(key, value) {
                    $('#editCourse').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
                $('#editCourse').val(data.course);
                // Select athlete status
                $('#editAthlete').val(data.athlete);
                // Show/hide sport dropdown based on athlete status
                if (data.athlete == 1) {
                    $('#sportDropdown').show();
                    // Populate sport dropdown options
                    populateSportDropdown();
                } else {
                    $('#sportDropdown').hide();
                    $('#teamDropdown').hide();
                }
            }
        });
    });

    // Delete button click event
    $('.delete-btn').click(function(){
        var id = $(this).data('id');
        // AJAX request to fetch user data
        $.ajax({
            url: 'fetch_user.php',
            type: 'POST',
            data: {id: id},
            success: function(response){
                var data = JSON.parse(response);
                $('#deleteId').val(data.id);
                $('#deleteFullName').val(data.fullName);
            }
        });
    });

    // Event listener for editAthlete select
    $('#editAthlete').change(function() {
        if ($(this).val() == 1) {
            $('#sportDropdown').show();
            populateSportDropdown();
        } else {
            $('#sportDropdown').hide();
            $('#teamDropdownList').hide();
        }
    });

    // Event listener for sportDropdown change
    $('#editSport').change(function() {
        var sportId = $(this).val();
        if (sportId) {
            populateTeamDropdown(sportId);
        } else {
            $('#teamDropdown').empty();
            $('#teamDropdownList').hide();
        }
    });

    // Function to populate sport dropdown
    function populateSportDropdown() {
        $.ajax({
            url: 'fetch_sports.php',
            type: 'GET',
            success: function(response){
                var sports = JSON.parse(response);
                $('#editSport').empty();
                $('#editSport').append('<option value="">Select Sport</option>');
                $.each(sports, function(key, value) {
                    $('#editSport').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    }
    // Function to populate team dropdown based on selected sport
    function populateTeamDropdown(sportId) {
        $.ajax({
            url: 'fetch_team.php',
            type: 'POST',
            data: {sportId: sportId},
            success: function(response){
                var teams = JSON.parse(response);
                var teamDropdown = $('#teamDropdown');
                teamDropdown.empty(); // Clear previous options
                teamDropdown.append('<option value="">Select Team</option>'); // Add default option
                // Append each team option to the dropdown
                $.each(teams, function(key, value) {
                    teamDropdown.append('<option value="'+value.id+'">'+value.name+'</option>');
                });
                teamDropdown.show(); // Show the dropdown
            }
        });
    }
});
</script>


</body>
</html>
