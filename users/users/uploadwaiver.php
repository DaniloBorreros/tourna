<!DOCTYPE html>
<html lang="en">

<?php  

  include '../../config.php';
  session_start();
  $id = $_SESSION['id'];


   $sql = "SELECT * FROM users WHERE id = '$id'";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
       // Output data of each row
       while($row = $result->fetch_assoc()) {
           $waiver = $row['waiver'];
           $name = $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
       }
   }

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF File Upload with Preview</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="waiver.css">
</head>

<body>


    <?php if ($waiver == 0){

    ?>
    <div class="container py-5">

        <!-- For demo purpose -->
        <header class="text-white text-center">
            <h1 class="display-4">Upload athlete's waiver</h1>
            <p class="lead mb-0"><!-- Some more texts here --></p>
            <img src="https://bootstrapious.com/i/snippets/sn-img-upload/file.svg" alt="" width="150" class="mb-4">
        </header>

        <div class="row py-4">
            <div class="col-lg-6 mx-auto">

                <!-- Form to upload PDF -->
                <form action="crud.php" method="POST" enctype="multipart/form-data">
                    <!-- Upload PDF input-->
                    <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
                        <input id="upload" type="file" accept="application/pdf" onchange="previewPDF(this);" class="form-control border-0" name="waiver_file" required>
                        <label id="upload-label" for="upload" class="font-weight-light text-muted">Choose PDF file</label>
                        <div class="input-group-append">
                            <label for="upload" class="btn btn-light m-0 rounded-pill px-4">
                                <i class="fa fa-cloud-upload mr-2 text-muted"></i>
                                <small class="text-uppercase font-weight-bold text-muted">Choose file</small>
                            </label>
                        </div>
                    </div>

                    <!-- Uploaded file info area-->
                    <p class="font-italic text-white text-center">The selected PDF file will be displayed below.</p>
                    <div class="file-area mt-4">
                        <p id="fileName" class="text-white text-center"></p>
                        <!-- PDF preview area -->
                        <iframe id="pdfPreview"></iframe>
                    </div>

                    <!-- Submit button -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary" name="submit_waiver">Submit Waiver</button>
                        <a class="btn btn-primary" href="downloadwaiver.php" target="mainFrame" aria-expanded="false">Download Waiver</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <?php }
    elseif($waiver == 1){
    ?>
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-12 col-sm-offset-3">
                <br><br> <h2 style="color:#0fad00">Thank you!</h2>
                <img src="http://osmhotels.com//assets/check-true.jpg">
                <h3>Dear, <?php echo $name; ?></h3>
                <p style="font-size:20px;color:black;">Thank you for submitting the athlete's waiver. Your submission has been successfully received, and you are now fully registered for the event.</p>
            <br><br>
                </div>
                
            </div>
        </div>
    <?php
    }
    ?>

    <script type="text/javascript" src="waiver.js"></script>

</body>

</html>
