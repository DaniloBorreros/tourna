<?php
// Database connection
include '../../config.php';

// Retrieve the values from the URL parameters or set default values for testing
$sport = isset($_GET['sport']) ? $_GET['sport'] : 1;
$team = isset($_GET['team']) ? $_GET['team'] : 2;

// Query to fetch the name of the team from the 'course' table
$teamQuery = "SELECT name FROM course WHERE id = '$team'";
$teamResult = $conn->query($teamQuery);
$teamName = ($teamResult->num_rows > 0) ? $teamResult->fetch_assoc()['name'] : '';

// Query to fetch the name of the sport from the 'sport' table
$sportQuery = "SELECT name FROM sport WHERE id = '$sport'";
$sportResult = $conn->query($sportQuery);
$sportName = ($sportResult->num_rows > 0) ? $sportResult->fetch_assoc()['name'] : '';

// Query to fetch users based on the selected team and sport
$sql = "SELECT * FROM users WHERE course = '$team' AND sport = '$sport'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roster</title>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="roster.css">
</head>
<body>

<div class="py-5 team4">
  <div class="container">
    <div class="row justify-content-center mb-6" style="margin-bottom: 50px;">
      <div class="col-md-12 text-center">
        <h3 class="mb-3"><?php echo $teamName; ?></h3>
        <h4 class="subtitle"><?php echo $sportName; ?> Team</h4>
      </div>
    </div>
    <div class="row">

    <?php
    // Check if there are any users found
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
    ?>
            <!-- column -->
            <div class="col-lg-4 mb-4">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12">
                        <img src="../image/<?php echo $row['image']; ?>.jpg" alt="wrapkit" class="img-fluid rounded-circle" style="border-radius: 1000px; height: 100%; width: 100%;" />
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="pt-2">
                            <h5 class="mt-4 font-weight-medium mb-0"><?php echo $row['lastname'].', '.$row['firstname'].' '.$row['middlename']; ?></h5>
                            <h6 class="subtitle mb-3"><?php //echo $row['position']; ?></h6>
                            <p><?php //echo $row['description']; ?></p>
                            <ul class="list-inline">
                                <!-- Add social media links if available -->
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Row -->
            </div>
            <!-- column -->
    <?php
        }
    } else {
        // If no users found
        echo "<div class='col-md-12 text-center'><p>No users found for the selected team and sport.</p></div>";
    }
    ?>

    </div>
  </div>
</div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
