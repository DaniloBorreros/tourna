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


<!-- Bootstrap modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1" role="dialog" aria-labelledby="addNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewsModalLabel">Add News</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding news -->
                <form action="../crud.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="newsTitle">Title</label>
                        <input type="text" class="form-control" id="newsTitle" name="newsTitle">
                    </div>
                    <div class="form-group">
                        <label for="newsDate">Date</label>
                        <input type="date" class="form-control" id="newsDate" name="newsDate">
                    </div>
                    <div class="form-group">
                        <label for="newsContent">Content</label>
                        <textarea class="form-control" id="newsContent" name="newsContent" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="newsImage">Image</label>
                        <input type="file" class="form-control-file" id="newsImage" name="newsImage">
                    </div>
                    <button type="submit" class="btn btn-primary" name="addnews">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap modal for updating news -->
<div class="modal fade" id="updateNewsModal" tabindex="-1" role="dialog" aria-labelledby="updateNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateNewsModalLabel">Update News</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for updating news -->
                <form action="../crud.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="updateNewsId" name="newsId">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="updateNewsTitle">Title</label>
                                <input type="text" class="form-control" id="updateNewsTitle" name="newsTitle">
                            </div>
                            <div class="form-group">
                                <label for="updateNewsDate">Date</label>
                                <input type="date" class="form-control" id="updateNewsDate" name="newsDate">
                            </div>
                            <div class="form-group">
                                <label for="updateNewsContent">Content</label>
                                <textarea class="form-control" id="updateNewsContent" name="newsContent" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="updateNewsImage">Image</label>
                                <input type="file" class="form-control-file" id="updateNewsImage" name="newsImage" onchange="previewImage(event)">
                                <img id="updateImagePreview" src="" alt="Image Preview" style="max-width: 100%; margin-top: 10px;">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="updatenews">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>



<section class="ftco-section">

    <?php
    if (isset($_SESSION['type']) && $_SESSION['type'] == 'success') {
        ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['msg']; ?>
        </div>
        <?php
    }
    unset($_SESSION['type']);
    unset($_SESSION['msg']);
    ?>


    <button class="btn btn-primary btn-sm" id="addNewsBtn" style="margin-left: 5.5%; margin-bottom: 1%;">Add news</button>


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
                                                    <button class="btn btn-success btn-sm updateBtn" 
                                                            data-id="<?php echo $row['id']; ?>" 
                                                            data-title="<?php echo $row['title']; ?>" 
                                                            data-date="<?php echo $row['dateuploaded']; ?>" 
                                                            data-content="<?php echo $row['content']; ?>" 
                                                            data-image="../../news/<?php echo $row['image']; ?>">Update</button>
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


<script>
    // JavaScript to trigger modal
    $(document).ready(function(){
        $("#addNewsBtn").click(function(){
            $("#addNewsModal").modal();
        });
    });
</script>
<script>
    // Trigger the add news modal
    $(document).ready(function () {
        $("#addNewsBtn").click(function () {
            $("#addNewsModal").modal();
        });

        // Trigger the update news modal and populate data
        $(".updateBtn").click(function () {
            const newsId = $(this).data("id");
            const newsTitle = $(this).data("title");
            const newsDate = $(this).data("date");
            const newsContent = $(this).data("content");
            const newsImage = $(this).data("image");

            // Populate the update form with existing news data
            $("#updateNewsId").val(newsId);
            $("#updateNewsTitle").val(newsTitle);
            $("#updateNewsDate").val(newsDate);
            $("#updateNewsContent").val(newsContent);
            $("#updateImagePreview").attr("src", newsImage);

            // Show the update modal
            $("#updateNewsModal").modal();
        });
    });

    // Preview image before uploading
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('updateImagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
