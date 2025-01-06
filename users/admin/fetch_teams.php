<?php
include '../../config.php';

$team1id = $_GET['team1'];

$sql = "SELECT * FROM course WHERE id != $team1id";
$result = $conn->query($sql);

$teams = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
}

// Return teams as JSON
echo json_encode($teams);

$conn->close();
?>
