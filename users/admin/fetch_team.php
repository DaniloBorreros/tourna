<?php
// Include your database connection file
include '../../config.php';

// Check if sportId is provided
if(isset($_POST['sportId'])) {
    $sportId = $_POST['sportId'];

    // Fetch teams data from the 'teams' table based on the selected sport
    $sql = "SELECT * FROM team WHERE sport = $sportId";
    $result = $conn->query($sql);

    $teams = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add each team to the $teams array
            $teams[] = array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
    }

    // Convert the array to JSON format
    echo json_encode($teams);
}

// Close database connection
$conn->close();
?>
