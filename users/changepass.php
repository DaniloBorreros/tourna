<?php
session_start();

// Include database connection
include '../config.php';
$id = $_SESSION['id'];

if(isset($_POST['changepassword'])) {
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['password'];

    // Retrieve the current password from the database
    $sql = "SELECT password FROM users WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentPassword = $row['password'];

        // Verify if the entered old password matches the current password
        if($oldpassword === $currentPassword) {
            // Old password matches, proceed with updating the password
            $updateSql = "UPDATE users SET password='$newpassword' WHERE id='$id'";
            if ($conn->query($updateSql) === TRUE) {
                $_SESSION['type'] = 'success';
                $_SESSION['msg'] = 'Password updated successfully.';
                header("Location: changepassword.php"); // Redirect back to the index page
                exit();
            } else {
                echo "Error updating password: " . $conn->error;
            }
        } else {
            // Old password doesn't match
            $_SESSION['type'] = 'danger';
            $_SESSION['msg'] = 'Old password is incorrect. Changing of password failed!';
            header("Location: changepassword.php"); // Redirect back to the change password page
            exit();
        }
    } else {
        echo "No user found with ID: $id";
    }
}

// Close the database connection
$conn->close();
?>
