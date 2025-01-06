<?php
// Include database connection
include '../../../config.php';
session_start();
// Fetch news data from the database
$sql = "SELECT * FROM news ORDER BY dateuploaded DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Carousel 06</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>





<section class="ftco-section">


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="featured-carousel owl-carousel">
                    <?php
                    // Check if there are any news items
                    if ($result->num_rows > 0) {
                        // Output data of each news item
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="item">
                                <div class="work-wrap d-md-flex">
                                    <div class="img order-md-last" style="background-image: url(../../news/<?php echo $row['image']; ?>);"></div>
                                    <div class="text text-left text-lg-right p-4 px-xl-5 d-flex align-items-center">
                                        <div class="desc w-100">
                                            <h2 class="mb-4"><?php echo $row['title']; ?></h2>
                                            <p class="h5"><?php echo $row['dateuploaded']; ?></p>
                                            <div class="row justify-content-end">
                                                <div class="col-xl-8">
                                                    <p><?php echo $row['content']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // If no news items found
                        echo "<p>No news available.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
