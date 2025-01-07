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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</>

<body>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6 m-10">
        <?php
        $tiles = [
            ['color' => 'bg-blue-500', 'icon' => 'fa-users', 'description' => 'Athletes', 'count' => $userCount],
            ['color' => 'bg-yellow-500', 'icon' => 'fa-calendar', 'description' => 'Incoming Games', 'count' => $incomingGamesCount],
            ['color' => 'bg-green-500', 'icon' => 'mdi mdi-newspaper', 'description' => 'News', 'count' => $newsCount],
            ['color' => 'bg-orange-500', 'icon' => 'fa-check', 'description' => 'Finished Games', 'count' => $finishGamesCount]
        ];

        foreach ($tiles as $tile) {
            ?>
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-center <?php echo $tile['color']; ?> text-white rounded-t-lg h-20">
                    <i class="fa <?php echo $tile['icon']; ?> text-3xl"></i>
                </div>
                <div class="p-4 text-center">
                    <p class="text-gray-500 font-semibold"><?php echo $tile['description']; ?></p>
                    <p class="text-2xl font-bold text-gray-700"><?php echo $tile['count']; ?></p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <iframe src="../users/schedule.php" style="height: 100vh; width: 100%;"></iframe>
        </div>
    </div>
</body>

</html>