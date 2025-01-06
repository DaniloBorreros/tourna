<?php
include '../config.php';
if (isset($_GET['id'])) {
    // Sanitize the input
    $sportId = intval($_GET['id']); // Convert to integer to prevent SQL injection

    // Prepare and execute a query to fetch teams associated with the selected sport ID
    $sql = "SELECT id, name FROM team WHERE sport = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sportId); // Bind the sport ID parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if teams are found
    if ($result->num_rows > 0) {
        // Fetch teams into an array
        $teams = array();
        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }

        // Return teams as JSON
        echo json_encode($teams);
    } else {
        // No teams found for the selected sport
        echo json_encode(array('message' => 'No teams found for the selected sport'));
    }
} else {
    // Sport ID is not provided
    echo json_encode(array('message' => 'Sport ID is required'));
}
?>
