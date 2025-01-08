<?php
// Include your database connection and any necessary configurations
include '../../config.php';

// Fetch counts from database tables
$sqlUsers = "SELECT COUNT(*) AS userCount FROM users";
$resultUsers = $conn->query($sqlUsers);
$userCount = ($resultUsers->num_rows > 0) ? $resultUsers->fetch_assoc()['userCount'] : 0;

$sqlIncomingGames = "SELECT COUNT(*) AS incomingGamesCount FROM schedule WHERE schedule > NOW()";
$resultIncomingGames = $conn->query($sqlIncomingGames);
$incomingGamesCount = ($resultIncomingGames->num_rows > 0) ? $resultIncomingGames->fetch_assoc()['incomingGamesCount'] : 0;

$sqlFinishGames = "SELECT COUNT(*) AS finishGamesCount FROM schedule WHERE schedule < NOW()";
$resultFinishGames = $conn->query($sqlFinishGames);
$finishGamesCount = ($resultFinishGames->num_rows > 0) ? $resultFinishGames->fetch_assoc()['finishGamesCount'] : 0;

$sqlNews = "SELECT COUNT(*) AS newsCount FROM news";
$resultNews = $conn->query($sqlNews);
$newsCount = ($resultNews->num_rows > 0) ? $resultNews->fetch_assoc()['newsCount'] : 0;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css">
</head>

<body>
    <div class="row" style="margin-top: 2%; width: 90%; margin-left: 5%;">
        <div class="col-md-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading dark-blue">
                        <i class="fa fa-users fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded">Users</div>
                    <div class="circle-tile-number text-faded"><?php echo $userCount; ?></div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading red bg-warning">
                        <i class="fa fa-calendar fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content red">
                    <div class="circle-tile-description text-faded">Incoming Games</div>
                    <div class="circle-tile-number text-faded"><?php echo $incomingGamesCount; ?></div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading green">
                        <i class="mdi mdi-newspaper fa-fw fa-4x"></i>
                    </div>
                </a>
                <div class="circle-tile-content green">
                    <div class="circle-tile-description text-faded">News</div>
                    <div class="circle-tile-number text-faded"><?php echo $newsCount; ?></div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading orange">
                        <i class="fa fa-check fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content orange">
                    <div class="circle-tile-description text-faded">Finish Games</div>
                    <div class="circle-tile-number text-faded"><?php echo $finishGamesCount; ?></div>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <iframe src="../users/schedule.php" style="height: 100vh; width: 100%;"></iframe>
        </div>
    </div>







</body>

</html>