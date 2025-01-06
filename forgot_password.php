<?php
// Include database connection
include 'config.php'; // Replace with your database connection script
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email from the form
    $email = $_POST['email'];


    // Generate a random 6-character alphanumeric password
    $newPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);

    // Check if the email exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        // Update the password in the database
        $updateQuery = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
        if (mysqli_query($conn, $updateQuery)) {
            
            $_SESSION['email'] = $email;
            require "phpmailer/PHPMailerAutoload.php";
            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->Port=587;
            $mail->SMTPAuth=true;
            $mail->SMTPSecure='tls';

            $mail->Username='tounamentscheduling@gmail.com';
            $mail->Password='fzgfefluvbdkdsnq';

            $mail->setFrom('email account');
            $mail->addAddress($_POST["email"]);

            $mail->isHTML(true);
            $mail->Subject="CvSU Tournament System Password";
            $mail->Body="<p>Dear $email, </p> <h3>It seems that you forgot your old password but don't worry, here is your new account password: <b style='color: red;'>$newPassword</b></h3>
            <br><br>
            <p>With regards,</p>
            <b>CvSU - Bacoor City Campus Admin</b>";
            $mail->send();    
                $_SESSION['type'] = 'success';
                $_SESSION['msg'] = 'Password reset successful! Please check your email for your account password.';
                header('Location: index.php');
            
        } else {
            echo "Error updating the password. Please try again.";
        }
    } else {
        echo "The email address you entered does not exist in our records.";
    }
} else {
    echo "Invalid request.";
}
?>
