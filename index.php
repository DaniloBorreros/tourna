<!DOCTYPE html>
<html lang="en">
<?php  
   include 'config.php';
?>
   <!-- Basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- Mobile Metas -->
   <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <!-- Site Metas -->
   <title>CvSU - Bacoor City Campus Tournament Management System</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- Site Icons -->
   <link rel="shortcut icon" href="" type="image/x-icon" />
   <link rel="apple-touch-icon" href="">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!-- Site CSS -->
   <link rel="stylesheet" href="style.css">
   <!-- Colors CSS -->
   <link rel="stylesheet" href="css/colors.css">
   <!-- ALL VERSION CSS -->	
   <link rel="stylesheet" href="css/versions.css">
   <!-- Responsive CSS -->
   <link rel="stylesheet" href="css/responsive.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/custom.css">
   <!-- font family -->
   <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
   <!-- end font family -->
   <link rel="stylesheet" href="css/3dslider.css" />
   <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
   <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
   <script src="js/3dslider.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-DGh+3F62vuLbJ9gOLrGt4vgK9Kmdk/0Ki6JoW7QoE6jcGz4B7XJql4RmQIp3LbBb" crossorigin="anonymous">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

   </head>
   <body class="game_info" data-spy="scroll" data-target=".header">
      <!-- LOADER -->
      <div id="preloader">
         <img class="preloader" src="images/loading-img.gif" alt="">
      </div>
      <!-- END LOADER -->
      <section id="top">
         <header>
            <div class="container">
               <div class="header-top">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="full">
                           <div class="logo">
                              <a href="index.php"><img src="images/stingray.png" alt="#" style="height: 75%; width: 100%;" /></a>
                           </div>
                        </div>
                     </div>

                     <div class="col-md-3">
                        <?php
                        session_start();
                        if (isset($_SESSION['type']) && $_SESSION['type'] == 'success') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_SESSION['msg']; ?>
                            </div>
                            <?php
                        }
                        elseif (isset($_SESSION['type']) && $_SESSION['type'] == 'danger') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['msg']; ?>
                            </div>
                            <?php
                        }
                        unset($_SESSION['type']);
                        unset($_SESSION['msg']);
                        ?>
                    </div>


                     <div class="col-md-6 col-12">
                       <div class="right_top_section text-center text-md-end">
                          <!-- button section -->
                          <ul class="login list-inline">
                             <li class="list-inline-item login-modal">
                                <a href="#" class="login"><i class="fa fa-user"></i> Login</a>
                             </li>
                             <li class="list-inline-item">
                                <div class="cart-option">
                                   <a href="#"><i class="fas fa-solid fa-right-to-bracket"></i> Register</a>
                                </div>
                             </li>
                          </ul>
                          <!-- end button section -->
                       </div>
                    </div>
                  </div>
               </div>
            </div>
         </header>


        <!-- Forgot Password Modal -->
        <div id="forgotPasswordModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forgot Password</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="forgot_password.php">
                            <div class="md-form mb-4" style="margin-bottom: 20px;">
                                <i class="fa fa-envelope prefix grey-text"></i>
                                <label data-error="wrong" data-success="right" for="forgotEmail">Enter your email</label>
                                <input type="email" name="email" id="forgotEmail" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default">Reset Password</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


         <!-- Login Modal -->
         <div id="loginModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Log In</h4>
                  </div>
                  <div class="modal-body">
                     <!-- Your login form goes here -->
                     <form method="POST" action="login.php">
                        <!-- Form fields -->
                              <div class="md-form mb-4" style="margin-bottom: 20px;">
                                 <i class="fa fa-envelope prefix grey-text"></i>
                                 <label data-error="wrong" data-success="right" for="defaultForm-email">Email</label>
                                 <input type="email" name="email" id="defaultForm-email" class="form-control">
                              </div>

                              <div class="md-form mb-4" style="margin-bottom: 20px;">
                                 <i class="fa fa-lock prefix grey-text"></i>
                                 <label data-error="wrong" data-success="right" for="defaultForm-email">Password</label>
                                 <input type="password" name="password" id="defaultForm-email" class="form-control">
                              </div>
                        <!-- Submit button -->
                        <div class="modal-footer">
                           <button type="submit" name="login" class="btn btn-default">Login</button>
                           <a href="#" class="forgot-password-link btn btn-sm link-opacity-100" style="background: transparent; border: none; color: black; float: right;">Forgot password</a>
                        </div>
                        
                     </form>
                  </div>
               </div>
            </div>
         </div>
         
         
         <script>
            $(document).ready(function () {
                // Dismiss login modal and show forgot password modal
                $('.forgot-password-link').click(function (e) {
                    e.preventDefault();
                    $('#loginModal').modal('hide'); // Hide the login modal
                    setTimeout(function () {
                        $('#forgotPasswordModal').modal('show'); // Show the forgot password modal
                    }, 500); // Add a slight delay for a smooth transition
                });
            });
        </script>


         <!-- Register Modal -->
         <div id="registerModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header text-center">
                     <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
                  </div>
                     <form method="post" action="register.php" enctype="multipart/form-data">
                        <div class="modal-body mx-3">
                           <div class="row">
                              <div class="md-form mb-4 col-md-4" style="margin-bottom: 20px;">
                                 <i class="fa fa-user prefix grey-text"></i>
                                 <label data-error="wrong" data-success="right" for="defaultForm-email">Lastname</label>
                                 <input type="text" name="lastname" id="defaultForm-email" class="form-control">
                              </div>

                              <div class="md-form mb-4 col-md-4" style="margin-bottom: 20px;">
                                 <i class="fa fa-user prefix grey-text"></i>
                                 <label data-error="wrong" data-success="right" for="defaultForm-email">Firstname</label>
                                 <input type="text" name="firstname" id="defaultForm-email" class="form-control">
                              </div>

                              <div class="md-form mb-4 col-md-4" style="margin-bottom: 20px;">
                                 <i class="fa fa-user prefix grey-text"></i>
                                 <label data-error="wrong" data-success="right" for="defaultForm-email">Middlename</label>
                                 <input type="text" name="middlename" id="defaultForm-email" class="form-control">
                              </div>
                           </div>

                           <div class="md-form mb-5" style="margin-bottom: 20px;">
                               <i class="fa fa-envelope prefix grey-text"></i>
                               <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
                               <input type="text" name="email" id="testInput" class="form-control" value="@cvsu.edu.ph">
                           </div>

                          <div class="row" style="margin-bottom: 20px;">
                             <div class="md-form mb-4 col-md-3">
                               <i class="fa fa-users prefix grey-text"></i>
                               <label data-error="wrong" data-success="right" for="defaultForm-pass">Year level</label>
                               <input type="number" name="year" id="defaultForm-pass" min="1" max="4" class="form-control">
                             </div>

                             <div class="md-form mb-4 col-md-9">
    <i class="fa fa-users prefix grey-text"></i>
    <label data-error="wrong" data-success="right" for="defaultForm-pass">Program</label>
    <select name="course" id="defaultForm-pass" class="form-control">
        <?php
        $sql = "SELECT id, name FROM course"; // Modify the SQL query to select id and name
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
            }
        } else {
            echo "<option value=''>No courses found</option>";
        }
        ?>
    </select>
</div>

                        </div>

                         <div class="row">
                            <div class="md-form mb-4 col-md-12" style="margin-bottom: 20px;">
                                <i class="fa fa-users prefix grey-text"></i>
                                <label data-error="wrong" data-success="right" for="defaultForm-pass">Athlete?</label>
                                <select name="athlete" id="athleteDropdown" class="form-control">
                                    <option>Please select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                           <div class="md-form mb-4 col-md-6" id="sportTeamDropdowns" style="display: none;">
                                <i class="fa fa-users prefix grey-text"></i>
                                <label data-error="wrong" data-success="right" for="sportDropdown">Sports</label>
                                <select class="form-control" name="sport" id="sportDropdown">
                                    <option>Please select your sport.</option>
                                    <?php
                                    $sql = "SELECT * FROM sport";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while($row = $result->fetch_assoc()) {
                                            ?>
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo "<option value=''>No sports found</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="md-form mb-4 col-md-6" id="teamDropdown">
                                <!--<i class="fa fa-users prefix grey-text"></i>
                                <label data-error="wrong" data-success="right" for="teamDropdown">Teams</label>
                                <select class="form-control" name="team" id="teamDropdown">
                                     <option>Please select your team.</option>
                                 </select>-->
                            </div>
                        </div>


                        <script>
                        $(document).ready(function(){
                            // Function to fetch teams based on selected sport
                            $('#sportDropdown').on('change', function(){
                                var sportId = $(this).val(); // Get the selected sport ID
                                // AJAX request to fetch teams based on sport ID
                                $.ajax({
                                    url: 'fetch_teams.php', // URL to your PHP script to fetch teams
                                    type: 'POST',
                                    data: {sport_id: sportId}, // Send the selected sport ID to the server
                                    success: function(response){
                                        $('#teamDropdown').html(response); // Update teams dropdown with fetched data
                                    }
                                });
                            });
                        });
                        </script>




                                 <div class="md-form mb-5">
                                    <i class="fa fa-image prefix grey-text"></i>
                                    <label>Picture</label>
                                    <input type="file" id="defaultForm-email" class="form-control" name="image" accept=".jpg, .jpeg">
                                 </div>


                           </div>

                           

                        <div class="modal-footer d-flex justify-content-center">
                          <button class="btn btn-default" type="submit" name="Register">Register</button>
                          <button type="button" class="close btn btn-secondary closemodal" data-dismiss="modal">Close</button>
                        </div>
                     </form>
               </div>
            </div>
         </div>



         <div class="full-slider">
            <div id="carousel-example-generic" class="carousel slide">
               <!-- Wrapper for slides -->
               <div class="carousel-inner" role="listbox">
                  <!-- First slide -->
                  <div class="item active deepskyblue" data-ride="carousel" data-interval="5000">
                     <div class="carousel-caption">
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                           <div class="slider-contant" data-animation="animated fadeInRight">
                              <h3>In our intramurals,<br>we're like <span class="color-green">stingrays </span><br>— graceful, resilient, inspiring.</h3>
                              <p>Dive into Victory: Stingrays Unite at Cavite State University - Bacoor Campus for an Epic Tournament of <br>
                              Athletic Excellence and Unity.
                              </p>
                              <!--<button class="btn btn-primary btn-lg">Read More</button>-->
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.item -->
               </div>
               <!-- /.carousel-inner -->
            </div>
            <!-- /.carousel -->
            <div class="news">
               <div class="container">
                  <div class="heading-slider">
                     <p class="headline"><i class="fa fa" aria-hidden="true"></i> Top Headlines :</p>
                     <h1>
                     <a href="" class="typewrite" data-period="2000" data-type='[ "Tournament Scheduling is soon so to be released!", "LOCAL INTRAMURALS 2022: Strengthening Sportsmanship and Camaraderie in the New Normal. "]'>
                     <span class="wrap"></span>
                     </a>
                     </h1>
                     <span class="wrap"></span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </section>
      
      <footer id="footer" class="footer">
         <div class="container">
            <div class="row">
            </div>
         </div>
         <div class="footer-bottom">
            <div class="container">
               <p>Copyright © 2024 Distributed by Cavite State University - Bacoor City Campus</p>
            </div>
         </div>
      </footer>
      <a href="#home" data-scroll class="dmtop global-radius"><i class="fa fa-angle-up"></i></a>
      <!-- ALL JS FILES -->
      <script src="js/all.js"></script>
      <!-- ALL PLUGINS -->
      <script src="js/custom.js"></script>
      <script src="js/modal.js"></script>
      <script src="js/register.js"></script>
      <script type="text/javascript">
         $(document).ready(function(){
           let defaultDomain = '@cvsu.edu.ph'; // use this to remove later
           let input = $("#testInput");
           let typed = ""; // keep track of what the user has typed
           
           // put cursor at start when clicked
           input.click(function(){
            $(this).get(0).setSelectionRange(0,0);
           })
           
           input.keydown(function(e){
             // @ is keycode 192
             if(e.keyCode === 192){
               // the user has typed @ and you can assume that they want a different domain
               input.val(typed.substring(0, typed.length - 1));
             }else{
                 // store the email the user has typed so far, minnus default domain
                 typed = input.val().replace(defaultDomain, '');
             }
           })
         })
      </script>

   </body>
</html>