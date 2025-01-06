<?php
// Include your database connection file
include '../../config.php';

// Fetch sports data from the 'sports' table
$sql = "SELECT * FROM sport";
$result = $conn->query($sql);

$sports = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each sport to the $sports array
        $sports[] = array(
            'id' => $row['id'],
            'name' => $row['name']
        );
    }
}

// Convert the array to JSON format
echo json_encode($sports);

// Close database connection
$conn->close();
?>
