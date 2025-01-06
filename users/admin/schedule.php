<?php  
    include '../../config.php';
    session_start();
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



<!-- Bootstrap Result modal form -->
<div id="resultModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter Result</h4>
            </div>
            <div class="modal-body">
                <form id="resultForm" method="post" action="crud.php">
                    <input type="hidden" id="scheduleIdInput" name="schedule_id" readonly>
                    <div class="form-group">
                        <label for="winner">Select Winner</label>
                        <select class="form-control" id="winner" name="winnerID">
                            <option value="">Please select winner</option>
                            <!-- Populate with options -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loser">Select Loser</label>
                        <input type="hidden" class="form-control" name="loserID" readonly>
                        <input type="text" class="form-control" name="loserName" readonly>
                    </div>
                    <div class="form-group" hidden>
                        <label for="scheduleDateTime">Schedule Date and Time</label>
                        <input type="text" class="form-control" id="scheduleDateTime" name="scheduleDateTime" readonly>
                    </div>
                    <div class="form-group" hidden>
                        <label for="sport_id">Sport ID</label>
                        <input type="text" class="form-control" id="sport_id" name="sport_id" readonly>
                    </div>
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" form="resultForm" class="btn btn-primary" name="updateResult">Save</button>
                    </div>
                </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        // Handle click on "Result" button
        $('.edit-btn').click(function(){
            // Get the team names, IDs, and sport ID from the clicked row
            var team1Name = $(this).closest('tr').find('td:nth-child(4)').text();
            var team1Id = $(this).closest('tr').find('td:nth-child(5)').text();
            var team2Name = $(this).closest('tr').find('td:nth-child(6)').text();
            var team2Id = $(this).closest('tr').find('td:nth-child(7)').text();
            var sportId = $(this).closest('tr').find('td:nth-child(2)').text(); // Assuming sport ID is in the second column
            var scheduleDateTime = $(this).closest('tr').find('td:nth-child(8)').text(); // Assuming schedule date and time is in the eighth column

            // Set the schedule ID, sport ID, and schedule date and time in the respective input fields
            $('#scheduleIdInput').val($(this).closest('tr').find('td:first').text());
            $('#sport_id').val(sportId);
            $('#scheduleDateTime').val(scheduleDateTime);

            // Populate the winner select option with team1 and team2
            $('#winner').html('<option value="' + team1Id + '">' + team1Name + '</option><option value="' + team2Id + '">' + team2Name + '</option>');

            // Show the modal
            $('#resultModal').modal('show');
        });

        // Handle change on "Winner" select option
        $('#winner').change(function(){
            // Get the selected winner's ID and Name
            var winnerId = $(this).val();
            var winnerName = $(this).find('option:selected').text();

            // Get the ID and Name of the unselected team
            var loserId = ($(this).val() == $('#winner option:first').val()) ? $('#winner option:nth-child(2)').val() : $('#winner option:first').val();
            var loserName = ($(this).val() == $('#winner option:first').val()) ? $('#winner option:nth-child(2)').text() : $('#winner option:first').text();

            // Populate the loserID and loserName text input fields with the ID and Name of the unselected team
            $('input[name="loserID"]').val(loserId);
            $('input[name="loserName"]').val(loserName);
        });


        $('.resched-btn').click(function(){
            $('#rescheduleIdInput').val($(this).closest('tr').find('td:first').text());
            // Show the reschedule modal
            $('#rescheduleModal').modal('show');
        });

    });


</script>




<!-- Bootstrap modal form for rescheduling -->
<div id="rescheduleModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reschedule</h4>
            </div>
            <form id="rescheduleForm" method="post" action="crud.php">
                <!-- Hidden input field for schedule ID -->
                <input type="hidden" id="rescheduleIdInput" name="rescheduleId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newScheduleDateTime">New Schedule Date and Time</label>
                        <input type="datetime-local" class="form-control" id="rescheduleDateTime" name="newScheduleDateTime" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" form="rescheduleForm" class="btn btn-primary" id="rescheduleSubmitBtn">Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>






<!-- Bootstrap modal form -->
<div id="createScheduleModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Schedule</h4>
      </div>
      <div class="modal-body">
        <!-- Your form elements go here -->
        <form id="scheduleForm" method="post" action="crud.php">
            <div class="form-group">
                <label>SPORT</label>
                <select class="form-control" name="sport">
                    <option>Please select sport</option>
                     <?php
                     $sql = "SELECT * FROM sport";
                     $result = $conn->query($sql);

                     if ($result->num_rows > 0) {
                         // Output data of each row
                         while($row = $result->fetch_assoc()) {
                             ?>
                             <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                             <?php
                         }
                     } else {
                         echo "<option value=''>No sports found</option>";
                     }
                     ?>
                </select>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label>Place</label>
                    <select id="placeSelect" class="form-control" name="place" style="margin-bottom: 1%;">
                        <option>Please select place</option>
                        <?php
                        $sql = "SELECT * FROM place";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                ?>
                                <option><?php echo $row['place']; ?></option>
                                <?php
                            }
                        } else {
                            echo "<option value=''>No place registered</option>";
                        }
                        ?>
                        <option value="0">Other</option>
                    </select>
                    <input type="text" id="otherPlaceInput" class="form-control" name="otherplace" style="display: none;" placeholder="Please specify">
                </div>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" name="scheduleDate" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" form="scheduleForm" name="createschedule" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>




<div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">
    <div class="row" style="margin-bottom: 5px;">
        <?php 
            if (isset($_SESSION['type']) && $_SESSION['type'] == 'success'){
        ?>
                <div class="alert alert-success col-md-4" role="alert">
                  <?php echo $_SESSION['msg']; ?>
                </div>
        <?php
            }
            else if (isset($_SESSION['type']) && $_SESSION['type'] == 'danger'){
        ?>
                <div class="alert alert-danger col-md-4" role="alert">
                  <?php echo $_SESSION['msg']; ?>
                </div>
        <?php
            }
            unset($_SESSION['type']);
            unset($_SESSION['msg']);
        ?>
        

        <button id="createScheduleBtn" class="btn btn-primary btn-sm edit-btn col-md-2" style="float: right; right: 20px;">Create schedule</button>
    </div>

    <table id="myTable" class="display table" width="100%">
        <thead>
            <tr>
                <th hidden>ID</th>
                <th hidden>Sport ID</th> <!-- New column for sport ID -->
                <th>Sport</th> <!-- Displaying the sport -->
                <th>TEAM 1</th>
                <th hidden>TEAM 1 ID</th>
                <th>TEAM 2</th>
                <th hidden>TEAM 2 ID</th>
                <th>SCHEDULE</th>
                <th>PLACE</th>
                <th>STAGE</th> <!-- New Stage column -->
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT s.id, s.sport AS sport_id, sp.name AS sport_name, t1.name AS team1_name, t1.id AS team1_id, t2.name AS team2_name, t2.id AS team2_id, s.schedule, s.place, sp.elimination
                    FROM schedule s
                    INNER JOIN sport sp ON s.sport = sp.id
                    LEFT JOIN team t1 ON s.team1 = t1.id
                    LEFT JOIN team t2 ON s.team2 = t2.id
                    WHERE s.winner IS NULL";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td hidden><?php echo $row['id']; ?></td>
                        <td hidden><?php echo $row['sport_id']; ?></td> <!-- Displaying the sport ID -->
                        <td><?php echo $row['sport_name']; ?></td>
                        <td><?php echo $row['team1_name']; ?></td>
                        <td hidden><?php echo $row['team1_id']; ?></td>
                        <td><?php echo $row['team2_name']; ?></td>
                        <td hidden><?php echo $row['team2_id']; ?></td>
                        <td>
                            <?php
                            $scheduleDateTime = strtotime($row['schedule']);
                            $formattedSchedule = date("M. d, Y g:ia", $scheduleDateTime);
                            echo $formattedSchedule;
                            ?>
                        </td>
                        <td><?php echo $row['place']; ?></td>
                        <td>
                            <?php
                            // Determine the elimination type
                            if ($row['elimination'] == 1) {
                                echo "Single Elimination";
                            } elseif ($row['elimination'] == 2) {
                                echo "Double Elimination";

                                // Fetch the stage of team 1 for Double Elimination
                                $team_stage_query = "SELECT stage FROM team WHERE id = '{$row['team1_id']}'";
                                $team_stage_result = $conn->query($team_stage_query);

                                if ($team_stage_result->num_rows > 0) {
                                    $team_stage = $team_stage_result->fetch_assoc()['stage'];
                                    if ($team_stage == 2) {
                                        echo " (Upper bracket)";
                                    } elseif ($team_stage == 1) {
                                        echo " (Lower bracket)";
                                    }
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-success edit-btn btn-sm">Result</button>
                            <button class="btn btn-warning resched-btn btn-sm">Reschedule</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>No schedule found.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript" src="assets/js/schedule.js"></script>


<script>
    $(document).ready(function(){
        $('#placeSelect').change(function(){
            if($(this).val() == '0'){
                $('#otherPlaceInput').show();
            }else{
                $('#otherPlaceInput').hide();
            }
        });
    });
</script>

<script type="text/javascript">
    
</script>


</body>
</html>
