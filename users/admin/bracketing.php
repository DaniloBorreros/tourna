<?php
include '../../config.php';

// Query to fetch match data grouped by sport and round
$matches_query = "
    SELECT 
        sp.id AS sport_id, 
        sp.name AS sport_name, 
        s.round, 
        t1.name AS team1_name, 
        t2.name AS team2_name, 
        t_winner.name AS winner_name, 
        t_loser.name AS loser_name, 
        s.schedule, 
        s.place 
    FROM schedule s
    INNER JOIN sport sp ON sp.id = s.sport
    INNER JOIN team t1 ON t1.id = s.team1
    INNER JOIN team t2 ON t2.id = s.team2
    LEFT JOIN team t_winner ON t_winner.id = s.winner
    LEFT JOIN team t_loser ON t_loser.id = s.loser
    ORDER BY sp.name, s.round, s.schedule
";

$matches_result = $conn->query($matches_query);

// Organize matches by sport and round
$sports = [];
while ($row = $matches_result->fetch_assoc()) {
    $sports[$row['sport_name']][$row['round']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Brackets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Roboto', sans-serif;
            padding: 20px;
        }

        .bracket-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 10px;
        }

        .round {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .round h3 {
            font-size: 18px;
            text-transform: uppercase;
            color: #0d6efd;
            margin-bottom: 15px;
        }

        .match {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            min-width: 250px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .match:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .player {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            padding: 8px;
            border-radius: 5px;
        }

        .winner {
            color: #198754; /* Bootstrap success green */
            font-weight: bold;
        }

        .winner .badge {
            background-color: #198754;
        }

        .loser {
            color: #dc3545; /* Bootstrap danger red */
            text-decoration: line-through;
        }

        .loser .badge {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center my-4 text-uppercase text-primary">Match Brackets</h1>

        <?php foreach ($sports as $sport_name => $rounds): ?>
            <div class="my-5">
                <h2 class="text-center text-success text-uppercase"><?= htmlspecialchars($sport_name) ?></h2>
                <div class="bracket-container">
                    <?php foreach ($rounds as $round_number => $matches): ?>
                        <div class="round">
                            <h3>Round <?= $round_number ?></h3>
                            <?php foreach ($matches as $match): ?>
                                <div class="match">
                                    <div class="player <?= isset($match['winner_name']) && $match['winner_name'] === $match['team1_name'] ? 'winner' : (isset($match['winner_name']) ? 'loser' : '') ?>">
                                        <span><?= htmlspecialchars($match['team1_name']) ?></span>
                                        <?= isset($match['winner_name']) && $match['winner_name'] === $match['team1_name'] ? '<span class="badge bg-success text-white">Winner</span>' : '' ?>
                                    </div>
                                    <div class="player <?= isset($match['winner_name']) && $match['winner_name'] === $match['team2_name'] ? 'winner' : (isset($match['winner_name']) ? 'loser' : '') ?>">
                                        <span><?= htmlspecialchars($match['team2_name']) ?></span>
                                        <?= isset($match['winner_name']) && $match['winner_name'] === $match['team2_name'] ? '<span class="badge bg-success text-white">Winner</span>' : '' ?>
                                    </div>
                                    <p class="mt-2">
                                        <strong>Date:</strong> <?= date('F d, Y h:ia', strtotime($match['schedule'])) ?><br>
                                        <strong>Place:</strong> <?= htmlspecialchars($match['place']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
