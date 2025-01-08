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
        /* Your provided CSS styles */
        .playoff-table * {
            box-sizing: border-box;
        }

        .playoff-table {
            font-family: sans-serif;
            font-size: 15px;
            line-height: 1.42857143;
            font-weight: 400;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            background-color: #f5f5f5;
        }

        .playoff-table .playoff-table-content {
            display: -webkit-flex;
            display: -ms-flexbox;
            display: -ms-flex;
            display: flex;
            padding: 20px;
        }

        .playoff-table .playoff-table-tour {
            display: -webkit-flex;
            display: -ms-flexbox;
            display: -ms-flex;
            display: flex;
            -webkit-align-items: center;
            -ms-align-items: center;
            align-items: center;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-justify-content: space-around;
            -ms-justify-content: space-around;
            justify-content: space-around;
            position: relative;
        }

        .playoff-table .playoff-table-pair {
            position: relative;
        }

        .playoff-table .playoff-table-pair:before {
            content: '';
            position: absolute;
            top: 27px;
            right: -12px;
            width: 12px;
            height: 1px;
            background-color: red;
        }

        .playoff-table .playoff-table-pair:after {
            content: '';
            position: absolute;
            width: 3px;
            height: 1000px;
            background-color: #f5f5f5;
            right: -12px;
            z-index: 1;
        }

        .playoff-table .playoff-table-pair:nth-child(even):after {
            top: 28px;
        }

        .playoff-table .playoff-table-pair:nth-child(odd):after {
            bottom: 28px;
        }

        .playoff-table .playoff-table-pair-style {
            border: 1px solid #cccccc;
            background-color: white;
            width: 160px;
            margin-bottom: 20px;
        }

        .playoff-table .playoff-table-group {
            padding-right: 11px;
            padding-left: 10px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: -ms-flex;
            display: flex;
            -webkit-align-items: center;
            -ms-align-items: center;
            align-items: center;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-justify-content: space-around;
            -ms-justify-content: space-around;
            justify-content: space-around;
        }

        .playoff-table .playoff-table-left-player,
        .playoff-table .playoff-table-right-player {
            min-height: 26px;
            padding: 3px 5px;
        }

        .playoff-table .playoff-table-left-player {
            border-bottom: 1px solid #cccccc;
        }

        .playoff-table .playoff-table-right-player {
            margin-top: -1px;
            border-top: 1px solid #cccccc;
        }

        .playoff-table .playoff-table-third-place {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            margin-top: 100px;
        }

        .winner {
            color: green;
            font-weight: bold;
        }

        .place {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center my-4 text-uppercase text-primary">Match Brackets</h1>

        <?php foreach ($sports as $sport_name => $rounds): ?>
            <div class="my-5">
                <h2 class="text-center text-success text-uppercase"><?= htmlspecialchars($sport_name) ?></h2>
                <div class="playoff-table">
                    <div class="playoff-table-content">
                        <?php foreach ($rounds as $round_number => $matches): ?>
                            <div class="playoff-table-tour">
                                <h3>Round <?= $round_number ?></h3>
                                <div class="playoff-table-group">
                                    <?php foreach ($matches as $match): ?>
                                        <div class="playoff-table-pair playoff-table-pair-style">
                                            <div class="playoff-table-left-player">
                                                <?= htmlspecialchars($match['team1_name']) ?>
                                                <?php if ($match['winner_name'] === $match['team1_name']): ?>
                                                    <span class="winner">(Winner)</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="playoff-table-right-player">
                                                <?= htmlspecialchars($match['team2_name']) ?>
                                                <?php if ($match['winner_name'] === $match['team2_name']): ?>
                                                    <span class="winner">(Winner)</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="place">
                                                <strong>Place:</strong> <?= htmlspecialchars($match['place']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>