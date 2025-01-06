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



<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="verifyModalLabel">Verify as Athlete?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <form method="POST" action="crud.php">
                <div class="modal-body">
                    <div class="form-group" hidden>
                        <label for="userId">User ID:</label>
                        <input type="text" id="userId" class="form-control" name="verifyID" readonly>
                    </div>
                    <div class="form-group">
                        <label for="fullName">Fullname:</label>
                        <input type="text" id="fullName" class="form-control" readonly>
                    </div>
                    <!-- Add any additional fields or form elements here if needed -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="verifyAthlete">Accept</button>
                    <button type="submit" class="btn btn-danger" name="declineAthlete">Decline</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Waiver Modal -->
<div class="modal fade" id="waiverModal" tabindex="-1" role="dialog" aria-labelledby="waiverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="waiverModalLabel">Athlete's Waiver</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Waiver PDF will be shown here -->
                <iframe id="waiverPreview" src="" width="100%" height="500px"></iframe>
            </div>
        </div>
    </div>
</div>



<div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">
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
            <th>SPORT</th>
            <th>VERIFIED?</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
            <?php
            // Updated SQL query with JOIN to fetch the sport name
            $sql = "SELECT users.*, course.name AS course_name, sport.name AS sport_name 
                    FROM users 
                    LEFT JOIN course ON users.course = course.id
                    LEFT JOIN sport ON users.sport = sport.id
                    WHERE users.athlete = 1";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
        
                    // Determine the year label
                    switch ($row['year']) {
                        case 1:
                            $yr = '1st';
                            break;
                        case 2:
                            $yr = '2nd';
                            break;
                        case 3:
                            $yr = '3rd';
                            break;
                        case 4:
                            $yr = '4th';
                            break;
                        default:
                            $yr = 'Unknown';
                    }
            ?>
            <tr>
                <td hidden><?php echo $row['id']; ?> </td>    
                <td><?php echo $row['lastname']; ?> </td>
                <td><?php echo $row['firstname']; ?> </td>
                <td><?php echo $row['middlename']; ?> </td>
                <td><?php echo $row['email']; ?> </td>
                <td><?php echo $yr . ' year '; ?></td>
                <td><?php echo $row['course_name'] ?? 'Unknown'; ?> </td>
                <td><?php echo $row['sport_name'] ?? 'Unknown'; ?> </td>
                <td>
                    <?php echo ($row['verification'] == 1) ? 'Yes' : 'No'; ?>
                </td>
                <td>
                    <?php if ($row['verification'] == 0) { ?>
                        <button class="btn btn-success btn-sm verify-btn" data-toggle="modal" data-target="#verifyModal"
                                data-userid="<?php echo $row['id']; ?>"
                                data-fullname="<?php echo $row['firstname'] . ' ' . $row['lastname']; ?>">
                            VERIFY
                        </button>
                    <?php } ?>
        
                    <button class="btn btn-info btn-sm waiver-btn" data-toggle="modal" data-target="#waiverModal"
                            data-userid="<?php echo $row['id']; ?>">
                        Waiver
                    </button>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='9'>No athlete/s found.</td></tr>";
            }
        
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('#myTable').dataTable();
    });
</script>


<script>
    $(document).ready(function () {
        // Function to handle click event of Verify button
        $('.verify-btn').click(function () {
            var userId = $(this).data('userid'); // Get USER ID from data attribute
            var fullName = $(this).data('fullname'); // Get fullname from data attribute

            // Set the value of the input fields in the modal
            $('#userId').val(userId);
            $('#fullName').val(fullName);
        });
    });
</script>


<script>
    $(document).ready(function () {
        // Function to handle click event of Verify button
        $('.verify-btn').click(function () {
            var userId = $(this).data('userid'); // Get USER ID from data attribute
            var fullName = $(this).data('fullname'); // Get fullname from data attribute

            // Set the value of the input fields in the modal
            $('#userId').val(userId);
            $('#fullName').val(fullName);
        });

        // Function to handle click event of Waiver button
        $('.waiver-btn').click(function () {
            var userId = $(this).data('userid'); // Get USER ID from data attribute

            // Set the source of the iframe to the student's waiver file
            $('#waiverPreview').attr('src', '../waiver/' + userId + '.pdf');
        });
    });
</script>




</body>
</html>
