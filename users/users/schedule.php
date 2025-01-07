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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

    <div class="container mx-auto text-center py-8">
        <h3 class="text-3xl font-bold mb-6">Incoming Tournament Schedule</h3>
        <div class="grid gap-6 md:grid-cols-1">

            <?php
            // Check if there are any rows in the result
            if (mysqli_num_rows($result) > 0) {
                // Loop through each row in the result
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="bg-gray-100 shadow-md rounded-lg p-6">
                        <!-- Sport Name -->
                        <div class="mb-4">
                            <h5 class="text-2xl font-semibold text-gray-800"><?php echo $row['sport_name']; ?></h5>
                        </div>
                        <!-- Teams -->
                        <div class="flex items-center justify-between mb-4">
                            <!-- Team 1 -->
                            <div class="flex flex-col items-center">
                                <a href="roster.php?sport=<?php echo $row['sport']; ?>&team=<?php echo urlencode($row['team1_name']); ?>"
                                    class="text-center">
                                    <img src="../teamLogo/<?php echo $row['team1_logo'] . '.jpg'; ?>"
                                        alt="<?php echo $row['team1_name']; ?>" class="h-24 w-24 rounded-full w-48 h-48">
                                    <span class="block mt-2 text-2xl font-semibold text-gray-700"><?php echo $row['team1_name']; ?></span>
                                </a>
                            </div>
                            <!-- Versus Icon -->
                            <div class="text-gray-500">
                                <h4 class="text-xl font-bold">VS</h4>
                            </div>
                            <!-- Team 2 -->
                            <div class="flex flex-col items-center">
                                <a href="roster.php?sport=<?php echo $row['sport']; ?>&team=<?php echo urlencode($row['team2_name']); ?>"
                                    class="text-center">
                                    <img src="../teamLogo/<?php echo $row['team2_logo'] . '.jpg'; ?>"
                                        alt="<?php echo $row['team2_name']; ?>" class="h-24 w-24 rounded-full w-48 h-48">
                                    <span class="block mt-2 text-2xl font-semibold text-gray-700"><?php echo $row['team2_name']; ?></span>
                                </a>
                            </div>
                        </div>
                        <!-- Schedule & Location -->
                        <div class="text-gray-600">
                            <p class="flex items-center justify-center gap-2 mb-2 text-2xl font-semibold">
                                <i class="fa fa-calendar text-blue-500"></i>
                                <?php echo date("j F, Y H:i", strtotime($row['schedule'])); ?>
                            </p>
                            <p class="text-xl text-gray-500"><?php echo $row['place']; ?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // If no rows found in the result, display a message
                echo "<p class='text-gray-500'>No schedule available.</p>";
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