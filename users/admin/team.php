<?php  
    include '../../config.php';
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team List</title>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Bootstrap Modal for New Team -->
<div id="newTeamModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Team</h4>
            </div>
            <div class="modal-body">
                <!-- Your form for adding a new team goes here -->
                <form id="newTeamForm" method="post" action="crud.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="course">Sport:</label>
                        <select name="course" id="defaultForm-pass" class="form-control">
                            <?php
                            $sql = "SELECT id, name FROM sport"; // Modify the SQL query to select id and name
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while($row = $result->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php
                                }
                            } else {
                                echo "<option value=''>No sport found</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teamName">Team Name:</label>
                        <input type="text" class="form-control" id="teamName" name="name">
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo:</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept=".jpg, .jpeg" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="newteam">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Team Modal -->
<div id="updateTeamModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Team</h4>
            </div>
            <div class="modal-body">
                <form id="updateTeamForm" method="post" action="crud.php" enctype="multipart/form-data">
                    <input type="hidden" name="team_id" id="updateTeamId">
                    <div class="form-group">
                        <label for="updateSport">Sport:</label>
                        <select name="sport" id="updateSport" class="form-control" required>
                            <?php
                            $sql = "SELECT id, name FROM sport ORDER BY name ASC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="updateTeamName">Team Name:</label>
                        <input type="text" class="form-control" id="updateTeamName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="updateLogo">Logo:</label>
                        <input type="file" class="form-control" id="updateLogo" name="logo" accept=".jpg, .jpeg">
                    </div>
                    <button type="submit" class="btn btn-primary" name="updateTeam">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">
    <div class="row" style="margin-bottom: 0.5%;">
        <div class="col-md-6">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newTeamModal">New Team</button>
        </div>
        <div class="col-md-6">
            <?php
            if (isset($_SESSION['type'])) {
                echo "<div class='alert alert-{$_SESSION['type']}' role='alert'>{$_SESSION['msg']}</div>";
                unset($_SESSION['type']);
                unset($_SESSION['msg']);
            }
            ?>
        </div>
    </div>
    <table id="myTable" class="display table" width="100%">
        <thead>
        <tr>
            <th hidden>ID</th>
            <th>LOGO</th>
            <th>SPORT</th>
            <th>TEAM</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT team.id, sport.name AS sport_name, team.name, team.logo FROM team JOIN sport ON team.sport = sport.id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td hidden><?php echo $row['id']; ?></td>   
            <td><img src="../teamLogo/<?php echo $row['logo']; ?>.jpg" alt="Logo" width="50"></td>
            <td><?php echo $row['sport_name']; ?></td> 
            <td><?php echo $row['name']; ?></td>
            <td>
                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#updateTeamModal" 
                        onclick="fillUpdateModal(<?php echo $row['id']; ?>, '<?php echo $row['sport_name']; ?>', '<?php echo $row['name']; ?>')">Update</button>
                <form method="post" action="crud.php" style="display:inline;">
                    <input type="hidden" name="team_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger" name="deleteTeam">Delete</button>
                </form>
            </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='5'>No team/s found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#myTable').dataTable();
    });

    function fillUpdateModal(id, sportName, teamName) {
        $('#updateTeamId').val(id);
        $('#updateTeamName').val(teamName);
        $('#updateSport').val(sportName); // Update this logic as needed for sport dropdown
    }
</script>

</body>
</html>
