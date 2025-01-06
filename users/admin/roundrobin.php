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


<!-- Bootstrap modal form -->
<div id="createScheduleModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Round Robin Schedule</h4>
      </div>
      <div class="modal-body">
        <!-- Your form elements go here -->
        <form id="scheduleForm" method="post" action="crud.php">
          <div class="form-group">
            <label>SPORT</label>
            <select class="form-control" name="sport">
              <option value="">Please select sport</option>
              <?php
              $sql = "SELECT * FROM sport";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                  // Output data of each row
                  while ($row = $result->fetch_assoc()) {
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

          <div class="form-group">
            <label>Start Date:</label>
            <input type="datetime-local" class="form-control" name="startDate">
          </div>

          <div class="form-group">
            <label>Place</label>
            <select class="form-control" name="place" id="placeSelect">
              <option value="">Please select place</option>
              <?php
              $sql = "SELECT * FROM place";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                  // Output data of each row
                  while ($row = $result->fetch_assoc()) {
              ?>
                    <option value="<?php echo $row['place']; ?>"><?php echo $row['place']; ?></option>
              <?php
                  }
              } else {
                  echo "<option value=''>No places found</option>";
              }
              ?>
              <option value="others">Others</option>
            </select>
          </div>

          <div class="form-group" id="customPlaceGroup" style="display: none;">
            <label>Please type the place of the tournament</label>
            <input type="text" class="form-control" name="customPlace" placeholder="Enter custom place">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" form="scheduleForm" name="createroundrobinschedule" class="btn btn-primary">Save</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Show/Hide Custom Place input field based on the selected option
  document.getElementById('placeSelect').addEventListener('change', function() {
    var customPlaceGroup = document.getElementById('customPlaceGroup');
    if (this.value === 'others') {
      customPlaceGroup.style.display = 'block';
    } else {
      customPlaceGroup.style.display = 'none';
    }
  });
</script>



<!-- Schedule Modal -->
<div id="scheduleModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Match</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="crud.php">
                <div class="modal-body">
                    <input type="hidden" id="scheduleDatetime" class="form-control" readonly name="schedule_id">

                    <div class="form-group">
                        <label for="dateandtime">Schedule Date & Time:</label>
                        <input type="datetime-local" class="form-control" id="dateandtime" name="schedule">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="roundrobinschedule">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Score Modal -->
<div id="scoreModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter Scores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="crud.php">
                <div class="modal-body">
                    <input type="hidden" id="matchupId" name="matchupId">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="team1Score" id="team1Label"></label>
                            <input type="number" id="team1Score" class="form-control" placeholder="Enter Score" name="team1_score">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="team2Score" id="team2Label"></label>
                            <input type="number" id="team2Score" class="form-control" placeholder="Enter Score" name="team2_score">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="saveScore">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        // Schedule Button
        document.querySelectorAll(".btn-schedule").forEach(button => {
            button.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                document.getElementById("scheduleDatetime").value = id; // Replace with your logic
                $("#scheduleModal").modal("show");
            });
        });

        // Score Button
        document.querySelectorAll(".btn-score").forEach(button => {
            button.addEventListener("click", function () {
                // Get data attributes
                const matchupId = this.getAttribute("data-id");
                const team1Name = this.getAttribute("data-team1-name");
                const team2Name = this.getAttribute("data-team2-name");

                // Set modal values
                document.getElementById("matchupId").value = matchupId; // Hidden field to store matchup ID
                document.getElementById("team1Label").textContent = team1Name; // Label for Team 1
                document.getElementById("team2Label").textContent = team2Name; // Label for Team 2

                // Show the Score Modal
                $("#scoreModal").modal("show");
            });
        });

        // Save Button Actions
        document.getElementById("saveSchedule").addEventListener("click", function () {
            alert("Schedule saved!");
            $("#scheduleModal").modal("hide");
        });

        document.getElementById("saveScore").addEventListener("click", function () {
            const matchupId = document.getElementById("matchupId").value;
            const team1Score = document.getElementById("team1Score").value;
            const team2Score = document.getElementById("team2Score").value;

            // Replace this with an AJAX request or form submission to save the scores
            alert(`Scores saved for Matchup ID ${matchupId}: Team 1 - ${team1Score}, Team 2 - ${team2Score}`);
            $("#scoreModal").modal("hide");
        });
    });
</script>




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

    <div class="container" style="margin-top: 20px;">
        <?php
        // Fetch all sports with matchups
        $sports_query = "SELECT DISTINCT sport.id AS sport_id, sport.name AS sport_name 
                         FROM roundrobin_matchups 
                         JOIN sport ON roundrobin_matchups.sport_id = sport.id";
        $sports_result = $conn->query($sports_query);

        if ($sports_result->num_rows > 0) {
            while ($sport = $sports_result->fetch_assoc()) {
                $sport_id = $sport['sport_id'];
                $sport_name = $sport['sport_name'];

                // Fetch standings for this sport
                $standings_query = "SELECT team.id AS team_id, team.name AS team_name, 
                                           COUNT(CASE WHEN winner = team.id THEN 1 END) AS wins 
                                    FROM team 
                                    LEFT JOIN roundrobin_matchups ON (team.id = roundrobin_matchups.team1 OR team.id = roundrobin_matchups.team2) 
                                    WHERE team.sport = '$sport_id' 
                                    GROUP BY team.id, team.name 
                                    ORDER BY wins DESC";
                $standings_result = $conn->query($standings_query);
                ?>
                <div class="sport-section" style="margin-bottom: 50px;">
                    <!-- Sport Header -->
                    <h3 style="text-align: center;"><?php echo $sport_name; ?></h3>

                    <div style="gap: 20px;" class="row">
                        <!-- Standings -->
                        <div class="standings col-md-2">
                            <table border="1" class="display table" style="width: 100%; text-align: left; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th>Team</th>
                                        <th style='text-align: center;'>Wins</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($standings_result->num_rows > 0) {
                                        while ($team = $standings_result->fetch_assoc()) {
                                            echo "<tr><td>{$team['team_name']}</td><td style='text-align: center;'>{$team['wins']}</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No teams found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Matchups -->
                        <div class="matchups col-md-10">
                            <table border="1" id="myTable" class="display table" style="width: 100%; text-align: center; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <?php
                                        // Fetch distinct rounds for the current sport
                                        $rounds_query = "
                                            SELECT DISTINCT round 
                                            FROM roundrobin_matchups 
                                            WHERE sport_id = '$sport_id'
                                            ORDER BY id ASC";
                                        $rounds_result = $conn->query($rounds_query);
                                        ?>
                                        <?php
                                        // Display column headers for each round
                                        $rounds = [];
                                        if ($rounds_result->num_rows > 0) {
                                            while ($round = $rounds_result->fetch_assoc()) {
                                                $rounds[] = $round['round'];
                                                echo "<th>Round {$round['round']}</th>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch all matchups for the sport
                                    $all_matchups_query = "
                                        SELECT roundrobin_matchups.*, 
                                               t1.name AS team1_name, 
                                               t2.name AS team2_name, 
                                               roundrobin_matchups.scheduledatetime, 
                                               roundrobin_matchups.team1_score, 
                                               roundrobin_matchups.team2_score, 
                                               roundrobin_matchups.place 
                                        FROM roundrobin_matchups 
                                        JOIN team AS t1 ON roundrobin_matchups.team1 = t1.id 
                                        JOIN team AS t2 ON roundrobin_matchups.team2 = t2.id 
                                        WHERE roundrobin_matchups.sport_id = '$sport_id'
                                        ORDER BY roundrobin_matchups.round ASC, roundrobin_matchups.scheduledatetime ASC";
                                    $all_matchups_result = $conn->query($all_matchups_query);
                        
                                    // Prepare an array to group matchups by round
                                    $matchups_by_round = [];
                                    if ($all_matchups_result->num_rows > 0) {
                                        while ($matchup = $all_matchups_result->fetch_assoc()) {
                                            $matchups_by_round[$matchup['round']][] = $matchup;
                                        }
                                    }
                        
                                    // Determine the maximum number of matchups in any round
                                    $max_matchups = max(array_map('count', $matchups_by_round));
                        
                                    // Loop through each row for matchups
                                    for ($i = 0; $i < $max_matchups; $i++) {
                                        echo "<tr>";
                                        foreach ($rounds as $round) {
                                            if (isset($matchups_by_round[$round][$i])) {
                                                $matchup = $matchups_by_round[$round][$i];
                                                $team1_score = $matchup['team1_score'];
                                                $team2_score = $matchup['team2_score'];
                                                $place = $matchup['place'];
                                                $id = $matchup['id'];
                                                // Determine score classes
                                                if (!is_null($team1_score) && !is_null($team2_score)) {
                                                    if ($team1_score > $team2_score) {
                                                        $team1_class = "text-success";
                                                        $team2_class = "text-danger";
                                                    } elseif ($team1_score < $team2_score) {
                                                        $team1_class = "text-danger";
                                                        $team2_class = "text-success";
                                                    } else {
                                                        $team1_class = "text-secondary";
                                                        $team2_class = "text-secondary";
                                                    }
                        
                                                    // Display scores next to team names
                                                    $team1_display = "{$matchup['team1_name']} <span class='$team1_class'>({$team1_score})</span>";
                                                    $team2_display = "{$matchup['team2_name']} <span class='$team2_class'>({$team2_score})</span>";
                                                } else {
                                                    // No scores, just display team names
                                                    $team1_display = $matchup['team1_name'];
                                                    $team2_display = $matchup['team2_name'];
                                                }
                        
                                                $formatted_datetime = is_null($matchup['scheduledatetime'])
                                                    ? "Not Scheduled"
                                                    : date("F d, Y h:ia", strtotime($matchup['scheduledatetime']));
                        
                                                echo "<td>
                                                        $team1_display vs $team2_display<br>
                                                        <small>{$formatted_datetime}</small><br>
                                                        <small>$place</small><br>";
                                                        if (is_null($team1_score) || is_null($team2_score)) {
                                                            echo "<button class='btn btn-sm btn-success btn-score' 
                                                                         data-id='{$matchup['id']}' 
                                                                         data-team1-id='{$matchup['team1']}' 
                                                                         data-team1-name='{$matchup['team1_name']}' 
                                                                         data-team2-id='{$matchup['team2']}' 
                                                                         data-team2-name='{$matchup['team2_name']}'>
                                                                    Score
                                                                  </button>
                                                                  <button class='btn btn-warning btn-sm btn-schedule' 
                                                                          data-id='$id'>Reschedule</button>";
                                                        }
                                            } else {
                                                echo "<td></td>";
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <?php
            }
        } else {
            echo "<p>No sports or matchups found.</p>";
        }

        $conn->close();
        ?>
    </div>
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
