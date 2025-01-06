<?php
include '../../config.php';
$query = "SELECT 
            schedule.id,
            schedule.team1, 
            schedule.team2,
            schedule.winner,
            schedule.loser,
            schedule.schedule,
            schedule.round,
            schedule.sport,
            t1.name AS team1_name,
            t2.name AS team2_name
          FROM 
            schedule
          JOIN 
            team AS t1 ON schedule.team1 = t1.id
          JOIN 
            team AS t2 ON schedule.team2 = t2.id
          ORDER BY 
            schedule.sport, schedule.round, schedule.schedule";
$result = mysqli_query($conn, $query);

// Group matches by sport
$sport_matches = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sport_matches[$row['sport']][] = $row;
}
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Kaushan+Script|Herr+Von+Muellerhoff' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Istok+Web|Roboto+Condensed:700' rel='stylesheet' type='text/css'>
  <link href='bracket.css' rel='stylesheet' type='text/css'>
  <title>March Madness Stock Matchup</title>
</head>

<body>

<section id="bracket">
    <?php foreach ($sport_matches as $sport => $matches): ?>
        <div class="container">
            <div class="bracket-group" id="bracket-<?php echo $sport; ?>">
                <h2><?php echo ucfirst($sport); ?> Bracket</h2>
                <div class="split split-one">
                    <?php 
                    $current_round = 1;
                    foreach ($matches as $match):
                        if ($match['round'] == $current_round): 
                    ?>
                            <div class="round round-<?php echo $current_round; ?>">
                                <div class="round-details"><?php echo $match['round']; ?> - <?php echo $match['date']; ?></div>
                                <ul class="matchup">
                                    <li class="team team-top"><?php echo $match['team1_name']; ?>
                                        <span class="score">
                                            <?php echo ($match['team1'] == $match['winner']) ? 'Winner' : ''; ?>
                                        </span>
                                    </li>
                                    <li class="team team-bottom"><?php echo $match['team2_name']; ?>
                                        <span class="score">
                                            <?php echo ($match['team2'] == $match['winner']) ? 'Winner' : ''; ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        <?php 
                        endif;
                        $current_round++;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</section>

</body>
