<?php
// Include your database connection and any necessary configurations
include '../../config.php';

// Check if the table name is provided in the POST request
if(isset($_POST['table'])) {
    // Sanitize the table name to prevent SQL injection
    $table = mysqli_real_escape_string($conn, $_POST['table']);
    
    // Construct the SQL query based on the provided table name
    $sql = "SELECT * FROM $table";
    
    // Execute the query
    $result = $conn->query($sql);

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Fetch data and store it in an array
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Return data as JSON
        echo json_encode($data);
    } else {
        // If no data found, return an empty array
        echo json_encode(array());
    }
} else {
    // If table name is not provided, return an error message
    echo json_encode(array("error" => "Table name not provided"));
}

// Close the database connection
$conn->close();
?>
