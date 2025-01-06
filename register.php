<?php
include 'config.php';
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $email = $_POST['email'];
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    $year = $_POST['year'];
    $course = $_POST['course'];
    $athlete = $_POST['athlete'];
    $sport = $_POST['sport'];
    $team = $_POST['team'];

    // File upload handling
    $targetDir = "users/image/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Generate a random name for the image file
    $randomName = substr(md5(uniqid(mt_rand(), true)), 0, 5);
    $newFileName = $randomName . "." . $fileType;
    $targetFilePath = $targetDir . $newFileName;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        // Check file size (maximum 5MB)
        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            echo "Sorry, your file is too large.";
        } else {
            // Allow only .jpg format
            if(strtolower($fileType) == "jpg" || strtolower($fileType) == "jpeg") {
                // If everything is ok, try to upload file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    // Insert data into users table
                    $sql = "INSERT INTO users (lastname, firstname, middlename, email, password, year, course, athlete, sport, team, image)
                            VALUES ('$lastname', '$firstname', '$middlename', '$email', '$password', '$year', '$course', '$athlete', '$sport', '$team', '$randomName')";

                    if ($conn->query($sql) === TRUE) {

                    $_SESSION['mail'] = $email;
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
                    $mail->Body="<p>Dear $email, </p> <h3>Thank you for your kind registration in our website. Here is your account password: <b style='color: red;'>$password</b></h3>
                    <br><br>
                    <p>With regards,</p>
                    <b>CvSU - Bacoor City Campus Admin</b>";
                    $mail->send();    
                        $_SESSION['type'] = 'success';
                        $_SESSION['msg'] = 'Registration successful! Please check your email for your account password.';
                        header('Location: index.php');
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG files are allowed.";
            }
        }
    } else {
        echo "File is not an image.";
    }
}

// Close database connection
$conn->close();
?>
