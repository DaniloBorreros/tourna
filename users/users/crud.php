<?php
include '../../config.php';
session_start();

if(isset($_POST['createschedule'])) {
    // Retrieve form data
    $sport = $_POST['sport'];
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $schedule = $_POST['schedule'];
    $place = $_POST['place'];

    // Check if team1 or team2 is already assigned to another schedule
    $checkSql = "SELECT * FROM schedule WHERE (team1 = '$team1' OR team2 = '$team1' OR team1 = '$team2' OR team2 = '$team2' OR place = '$place') AND schedule = '$schedule' AND sport = '$sport'";
    $checkResult = $conn->query($checkSql);
    if ($checkResult->num_rows > 0) {
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = "Team 1, Team 2, or Place is already assigned to another schedule at the same time.";
        header('Location: schedule.php');
        exit; // Stop execution
    }

    // Prepare and execute SQL query to insert data into schedule table
    $sql = "INSERT INTO schedule (sport, team1, team2, schedule, place) VALUES ('$sport', '$team1', '$team2', '$schedule', '$place')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = "New schedule created successfully";
        header('Location: schedule.php');
    } else {
        // Error occurred while creating schedule
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


if (isset($_POST['changeImage'])) {
    // Validate if the session ID exists
    if (!isset($_SESSION['id'])) {
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'User not logged in!';
        header('Location: login.php');
        exit;
    }

    $userId = $_SESSION['id'];
    $targetDir = "../image/"; // Target directory for saving images
    $file = $_FILES['profileImage'];

    // Validate file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Error uploading file. Please try again.';
        header('Location: accountsettings.php');
        exit;
    }

    // Validate file type (jpg only)
    $fileType = mime_content_type($file['tmp_name']);
    if ($fileType !== 'image/jpeg') {
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Only JPG images are allowed.';
        header('Location: accountsettings.php');
        exit;
    }

    // Generate a random 6-character alphanumeric filename
    $newFileName = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5) . '.jpg';
    $targetFilePath = $targetDir . $newFileName;

    // Move uploaded file to the target directory
    if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Failed to save the uploaded file.';
        header('Location: accountsettings.php');
        exit;
    }

    // Update the database
    $imageName = pathinfo($newFileName, PATHINFO_FILENAME); // Get filename without extension
    $query = "UPDATE users SET image = '$imageName' WHERE id = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Image successfully updated!';
    } else {
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Database update failed.';
        // Remove the uploaded file if database update fails
        unlink($targetFilePath);
    }

    // Redirect to account settings
    header('Location: accountsettings.php');
    exit;
}



if (isset($_POST['changeProfileInfo'])) {
    // Retrieve values from the form
    $id = $_SESSION['id'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);

    // Update query
    $updateQuery = "UPDATE users 
                    SET firstname = '$firstname', 
                        middlename = '$middlename', 
                        lastname = '$lastname', 
                        course = '$course', 
                        year = '$year' 
                    WHERE id = '$id'";

    if (mysqli_query($conn, $updateQuery)) {
        // Set success message in session
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Profile updated successfully!';
    } else {
        // Set error message in session
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Failed to update profile. Please try again.';
    }

    // Redirect to the account settings page
    header('Location: accountsettings.php');
    exit();
}


if (isset($_POST['submit_waiver'])) {
    // Directory to save the uploaded file
    $target_dir = "../waiver/";

    // Set file name to be saved as 'session_id.pdf'
    $target_file = $target_dir . $_SESSION['id'] . ".pdf";

    // Check if the file is a valid PDF
    if ($_FILES['waiver_file']['type'] == "application/pdf") {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['waiver_file']['tmp_name'], $target_file)) {

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Update the 'waiver' column in the 'users' table to '1'
            $user_id = $_SESSION['id'];
            $sql = "UPDATE users SET waiver = '1' WHERE id = '$user_id'";

            if ($conn->query($sql) === TRUE) {
                // Redirect to 'uploadwaiver.php'
                header("Location: uploadwaiver.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }

            // Close connection
            $conn->close();
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "Please upload a valid PDF file.";
    }
}

$conn->close();
?>
