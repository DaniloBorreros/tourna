<?php
    // Include configuration file and establish database connection
    include '../../config.php';
    
    // Fetch data from the 'schedule' table and order by 'schedule' column
    $query = "SELECT s.team1, s.team2, s.schedule, s.place, s.sport, sp.name AS sport_name, t1.name AS team1_name, t1.logo AS team1_logo, t2.name AS team2_name, t2.logo AS team2_logo
          FROM schedule s 
          LEFT JOIN sport sp ON s.sport = sp.id
          LEFT JOIN team t1 ON s.team1 = t1.id 
          LEFT JOIN team t2 ON s.team2 = t2.id
          WHERE s.schedule > NOW() AND s.winner IS NULL OR s.loser IS NULL
          ORDER BY s.schedule ASC";

    $result = mysqli_query($conn, $query);
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

<div class="container text-center">
    <h3>Incoming Tournament Schedule</h3>
    <div class="row text-center">
        
        <?php
            // Check if there are any rows in the result
            if(mysqli_num_rows($result) > 0) {
                // Loop through each row in the result
                while($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="col-md-12 plays-schedule">
                        <div class="well">
                            <div class="plays-schedule-title">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 style="font-weight: bold;"><?php echo $row['sport_name']; ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="plays-schedule-items">
                                <div class="row plays-team">
                                    <div class="col-md-5">
                                        <a href="roster.php?sport=<?php echo $row['sport']; ?>&team=<?php echo urlencode($row['team1_name']); ?>">
                                            <img src="../teamLogo/<?php echo $row['team1_logo'] . '.jpg'; ?>" alt="<?php echo $row['team1_name']; ?>" style="height: 100px; width: 100px;"><br>
                                            <span style="font-weight: bold;"><?php echo $row['team1_name']; ?></span>
                                        </a>
                                    </div>
                                    <div class="col-md-2">
                                        <h4>
                                            <span class="fa fa-arrow-circle-left "></span>
                                            <span class="fa fa-futbol-o fa-3"></span>
                                        </h4>
                                    </div>
                                    <div class="col-md-5">
                                        <a href="roster.php?sport=<?php echo $row['sport']; ?>&team=<?php echo urlencode($row['team2_name']); ?>">
                                            <img src="../teamLogo/<?php echo $row['team2_logo'] . '.jpg'; ?>" alt="<?php echo $row['team2_name']; ?>" style="height: 100px; width: 100px;"><br>
                                            <span style="font-weight: bold;"><?php echo $row['team2_name']; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row plays-info">
                                    <div class="col-md-12">
                                        <p>
                                            <span class="fo"></span>
                                            <span class="fa fa-calendar fa-lg colored-tex"></span>
                                            <?php echo date("j F, Y H:i", strtotime($row['schedule'])); ?>
                                            <span class="fa fa-clock-o fa-lg colored-tex"></span>
                                        </p>
                                        <p class="plays-dash"></p>
                                        <p><small><?php echo $row['place']; ?></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
                }
            } else {
                // If no rows found in the result, display a message
                echo "No schedule available.";
            }
        ?>
        
    </div>
</div>

</body>
</html>

<?php
    // Close database connection
    mysqli_close($conn);
?>
