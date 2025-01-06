<?php
session_start();
$email = $_SESSION['email'];
$at_pos = strpos($email, "@");
$masked_email = str_repeat('*', 8) . substr($email, 8, $at_pos - 8) . substr($email, $at_pos);
// Function to send OTP to the user's email
function sendOTP($email) {
    // Generate a new OTP
    $otp = rand(100000, 999999); // Generate a random 6-digit OTP
    $_SESSION['otp'] = $otp; // Store OTP in session
    $_SESSION['otp_expiry'] = time() + 300; // Set expiry time (5 minutes)

    // Send the OTP via email (using PHPMailer or similar)
    require "phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'tounamentscheduling@gmail.com'; // Your email
    $mail->Password = 'fzgfefluvbdkdsnq'; // Your email password
    $mail->setFrom('tounamentscheduling@gmail.com', 'CvSU-Bacoor Admin');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Your OTP Code";
    $mail->Body = "<p>Your OTP code is: <strong>$otp</strong></p>";

    // Send the email
    return $mail->send();
}

// Check if OTP is generated
if (!isset($_SESSION['otp'])) {
    header("Location: login.php");
    exit();
}

// Verify OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $input_otp = $_POST['otp'];
    $current_time = time();

    // Check if OTP matches and is not expired
    if ($input_otp == $_SESSION['otp'] && $current_time < $_SESSION['otp_expiry']) {
        // OTP verified, clear OTP session data and proceed
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        
        // Redirect to admin dashboard
        header("Location: users/admin/index.php");
        exit();
    } else {
        $error_message = "Invalid or expired OTP.";
    }
}

// Resend OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resend_otp'])) {
    // Check if email is set in the session
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; // Get the user's email from the session
        if (sendOTP($email)) {
            $success_message = "OTP has been resent to your email.";
        } else {
            $error_message = "Failed to resend OTP. Please try again.";
        }
    } else {
        $error_message = "Email is not set. Please try logging in again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
    <title>OTP Verification</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-4" style="width: 400px;">
            <h2 class="text-center mb-4">OTP Verification</h2><br>
            <h6 style="text-align: center;">Otp has been sent to: <i><?php echo htmlspecialchars($masked_email); ?></i></h6>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP:</label>
                    <input type="text" name="otp" id="otp" class="form-control" required>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-primary">Verify OTP</button>
            </form>

            <!-- Separate form for resending OTP -->
            <form method="POST" action="" class="mt-3">
                <button type="submit" name="resend_otp" class="btn btn-secondary">Resend OTP</button>
            </form>

            <div class="mt-3 text-center">
                <small>If you did not receive the OTP, please check your email or try resending it.</small>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
