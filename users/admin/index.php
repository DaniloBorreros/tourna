<?php  

  include '../../config.php';
  session_start();
  $id = $_SESSION['id'];


   $sql = "SELECT * FROM admin WHERE id = '$id'";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
       // Output data of each row
       while($row = $result->fetch_assoc()) {
           $name = $row['name'];
       }
   }

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Page</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <!-- Material Design Icons CSS -->
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css">
    <style>
  .collapsed {
    width: 0 !important;
    overflow: hidden;
  }
  
  .collapsed + .body-wrapper {
    margin-left: 0 !important;
  }

  .navbar-toggler {
    border: none;
    background: none;
  }
  .hide-menu, i{
      color: white;
      text-decoration: none;
  }
  @media (max-width: 768px) {
      .hide-menu, i{
          color: black;
      }
      .toggle{
          display: block;
      }
      .sidebar-item{
          width: 100%;
          text-align: center;
          padding: 2%;
      }
      
  }
</style>

</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar" style="background: #1D2951;">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="#" class="text-nowrap logo-img">
            <img src="assets/images/logos/stingray.png" width="180" alt=""/>
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><?php echo $name; ?></span>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">DATA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="dashboard.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-view-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="schedule.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-clock"></i>
                </span>
                <span class="hide-menu">Schedule</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="bracketing.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-fencing"></i>
                </span>
                <span class="hide-menu">Bracketing</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="roundrobin.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-fencing"></i>
                </span>
                <span class="hide-menu">Roundrobin</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="news/index.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-newspaper"></i>
                </span>
                <span class="hide-menu">News</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">SYSTEM</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="sport.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-basketball"></i>
                </span>
                <span class="hide-menu">Sport</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="place.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Place</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="course.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-book-open-variant"></i>
                </span>
                <span class="hide-menu">Course</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="team.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="mdi mdi-clipboard-account"></i>
                </span>
                <span class="hide-menu">Team</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="users.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">Students</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="athletes.php" target="mainFrame" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Athletes</span>
              </a>
            </li>
            
          </ul>

        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
          <nav class="navbar navbar-expand-lg navbar-dark">
            <!-- Hamburger Icon -->
            <li class="nav-item">
              <button type="button" class="toggle" data-bs-toggle="modal" data-bs-target="#sidebarOptionsModal" style="background: transparent; border: none;">
                <i class="mdi mdi-menu"></i>
              </button>
            </li>
            
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
              <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown">
                  <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                  </a>
                  <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div class="message-body">
                      <a href="../changepassword.php" target="mainFrame" class="d-flex align-items-center gap-2 dropdown-item">
                        <i class="ti ti-key fs-6"></i>
                        <p class="mb-0 fs-3">Change Password</p>
                      </a>
                      <a href="../logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </header>
      <iframe src="dashboard.php" name="mainFrame" style="height: 92vh; width: 100%; margin-top: 50px;"></iframe>
    </div>
  </div>
  
       <!-- Modal -->
<div class="modal fade" id="sidebarOptionsModal" tabindex="-1" aria-labelledby="sidebarOptionsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sidebarOptionsModalLabel">Sidebar Options</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Options will be dynamically inserted here -->
        <div id="sidebarOptionsContainer"></div>
      </div>
    </div>
  </div>
</div>


    <script>
      document.addEventListener("DOMContentLoaded", () => {
          const sidebarOptionsContainer = document.getElementById("sidebarOptionsContainer");
          const sidebarNav = document.querySelector(".sidebar-nav.scroll-sidebar");
        
          // Copy sidebar options to the modal
          const toggleButton = document.querySelector('[data-bs-target="#sidebarOptionsModal"]');
          toggleButton.addEventListener("click", () => {
            sidebarOptionsContainer.innerHTML = sidebarNav.innerHTML;
          });
        });
    </script>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/dashboard.js"></script>
</body>

</html>