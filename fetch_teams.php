<?php
// fetch_teams.php

// Include your database connection
include 'config.php';

// Get the selected sport ID from POST data
$sportId = $_POST['sport_id'];

// Query to fetch teams based on sport ID
$sql = "SELECT * FROM team WHERE sport = $sportId";
$result = $conn->query($sql);
echo '<i class="fa fa-users prefix grey-text"></i>
                                <label data-error="wrong" data-success="right" for="teamDropdown">Teams</label>';
echo '<select class="form-control" name="team" id="teamDropdown">';
// Check if there are teams available
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
    }
} else {
    echo "<option value=''>No teams found</option>";
}

echo "</select>";
// Close the database connection
$conn->close();
?>
