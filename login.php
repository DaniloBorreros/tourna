<?php
session_start();
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $_SESSION['email'] = $_POST['email'];
    $password = $_POST['password'];

    // Check if the account exists in the 'admin' table
    $sql = "SELECT id FROM admin WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Admin account found, generate OTP
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

        // Send the OTP to the admin email using PHPMailer
        require "phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        $mail->Username = 'tounamentscheduling@gmail.com'; // Your email
        $mail->Password = 'fzgfefluvbdkdsnq'; // Your app password (ensure security)

        $mail->setFrom('tounamentscheduling@gmail.com', 'CvSU - Bacoor City Campus Admin'); // Sender details
        $mail->addAddress($email); // Recipient email

        $mail->isHTML(true);
        $mail->Subject = "CvSU Tournament System OTP Verification";
        $mail->Body = "<p>Dear Admin,</p>
                       <h3>Your OTP Code is: <b style='color: blue;'>$otp</b></h3>
                       <p>Please enter this OTP within 5 minutes to access your account.</p>
                       <br>
                       <p>Best regards,</p>
                       <b>CvSU - Bacoor City Campus Admin</b>";

        if ($mail->send()) {
            // Redirect to OTP verification page if mail is sent
            header("Location: otp_verification.php");
            exit();
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }

    // Check in 'users' table if not found in admin
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        header("Location: users/users/index.php");
        exit();
    }

    $_SESSION['type'] = "danger";
    $_SESSION['msg'] = "Invalid email or password";
    header("Location: index.php");
}

// Close database connection
$conn->close();
?>
