<?php
session_start(); // Start session to use $_SESSION['id']

// Include database connection
include '../../config.php'; // Replace with your DB connection file

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

$userId = $_SESSION['id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = '$userId'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$row = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <title></title>
    <style>
        body {
            background: rgba(0, 0, 0, 0.05)
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: #BA68C8
        }
        
        .profile-button {
            background: rgb(99, 39, 120);
            box-shadow: none;
            border: none
        }
        
        .profile-button:hover {
            background: #682773
        }
        
        .profile-button:focus {
            background: #682773;
            box-shadow: none
        }
        
        .profile-button:active {
            background: #682773;
            box-shadow: none
        }
        
        .back:hover {
            color: #682773;
            cursor: pointer
        }
        
        .labels {
            font-size: 11px
        }
        
        .add-experience:hover {
            background: #BA68C8;
            color: #fff;
            cursor: pointer;
            border: solid 1px #BA68C8
        }
    </style>
</head>
<body>
    
    
    <div class="container rounded bg-white mt-5 mb-5" style="width: 50%;">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" width="150px" src="../image/<?php echo $row['image']; ?>.jpg">
                    <span class="font-weight-bold"><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname']; ?></span>
                    <span class="text-black-50"><?php echo $row['email']; ?></span>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadPictureModal">
                        Upload New Picture
                    </button>

                </div>
                
            </div>
            <form action="crud.php" method="POST" class="col-md-9 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="labels">Firstname</label>
                            <input type="text" class="form-control" name="firstname" value="<?php echo $row['firstname']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="labels">Middlename</label>
                            <input type="text" class="form-control" name="middlename" value="<?php echo $row['middlename']; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="labels">Lastname</label>
                            <input type="text" class="form-control" name="lastname" value="<?php echo $row['lastname']; ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label class="labels">Course</label>
                                <select class="form-control" name="course" required>
                                    <?php
                                    $currentCourseId = $row['course'];
                                    $currentCourseQuery = "SELECT name FROM course WHERE id = '$currentCourseId'";
                                    $currentCourseResult = mysqli_query($conn, $currentCourseQuery);
        
                                    if ($currentCourseResult && mysqli_num_rows($currentCourseResult) > 0) {
                                        $currentCourseRow = mysqli_fetch_assoc($currentCourseResult);
                                        $currentCourseName = $currentCourseRow['name'];
                                    } else {
                                        $currentCourseName = "Unknown Course";
                                    }
        
                                    echo "<option value='$currentCourseId' selected>$currentCourseName</option>";
        
                                    $allCoursesQuery = "SELECT id, name FROM course ORDER BY name ASC";
                                    $allCoursesResult = mysqli_query($conn, $allCoursesQuery);
        
                                    if ($allCoursesResult && mysqli_num_rows($allCoursesResult) > 0) {
                                        while ($course = mysqli_fetch_assoc($allCoursesResult)) {
                                            if ($course['id'] != $currentCourseId) {
                                                echo "<option value='" . $course['id'] . "'>" . $course['name'] . "</option>";
                                            }
                                        }
                                    } else {
                                        echo "<option value=''>No courses available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="labels">Year</label>
                                <input type="number" class="form-control" name="year" value="<?php echo $row['year']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <button class="btn btn-primary profile-button" type="submit" name="changeProfileInfo">Save Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
        
        <?php
        
            if(isset($_SESSION['type'])){
        ?>
        
            <div class="alert alert-<?php echo $_SESSION['type']; ?>" role="alert">
              <?php echo $_SESSION['msg']; ?>
            </div>
        
        <?php
            }
            unset($_SESSION['type']);
            unset($_SESSION['msg']);
        ?>
    </div>
    
    
    
    <!-- Upload Picture Modal -->
    <div class="modal fade" id="uploadPictureModal" tabindex="-1" aria-labelledby="uploadPictureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPictureModalLabel">Upload New Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="crud.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profileImage" class="form-label">Choose Image (.jpg only):</label>
                            <input type="file" class="form-control" id="profileImage" name="profileImage" accept=".jpg" required>
                            <small class="text-muted">Max file size: 2MB</small>
                        </div>
                        <div class="text-center">
                            <img id="previewImage" src="#" alt="Image Preview" class="img-fluid mt-3" style="max-width: 200px; display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="changeImage">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    
    <script>
        document.getElementById('profileImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
    
            if (file && file.type === 'image/jpeg') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                alert('Only JPG images are allowed.');
                event.target.value = ''; // Clear the input
                document.getElementById('previewImage').style.display = 'none';
            }
        });
    </script>




    <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>