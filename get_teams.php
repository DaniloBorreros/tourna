<?php
// Establish database connection and retrieve $conn variable
include 'config.php';
// Check if the sport ID is provided
if (isset($_GET['sport_id'])) {
    $sportId = $_GET['sport_id'];

    // Fetch teams from the database for the selected sport ID
    $sql = "SELECT * FROM team WHERE sport = $sportId";
    $result = $conn->query($sql);

    // Prepare an array to hold team data
    $teams = array();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            // Add team data to the array
            $teams[] = array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
    }

    // Send the teams data as JSON response
    echo json_encode($teams);
} else {
    // Send an error response if the sport ID is not provided
    echo json_encode(array('error' => 'Sport ID is not provided'));
}
?>
