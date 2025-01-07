<?php
include '../../config.php';
session_start();
$id = $_SESSION['id'];

$name = "Admin";
$sql = "SELECT * FROM admin WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $name = $row['name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrator Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col">
      <div class="p-8 flex items-center justify-center">
        <img src="assets/images/logos/stingray.png" alt="Logo" class="w-64">
      </div>
      <div class="p-4">
        <p class="text-sm uppercase text-gray-300 mb-4"><?php echo $name; ?></p>
        <ul class="space-y-2">
          <li>
            <a href="dashboard.php" target="mainFrame" class="flex items-center p-2 hover:bg-blue-700 rounded">
              <i class="mdi mdi-view-dashboard mr-2"></i> Dashboard
            </a>
          </li>
          <li>
            <a href="schedule.php" target="mainFrame" class="flex items-center p-2 hover:bg-blue-700 rounded">
              <i class="mdi mdi-clock mr-2"></i> Schedule
            </a>
          </li>
          <li>
            <a href="bracketing.php" target="mainFrame" class="flex items-center p-2 hover:bg-blue-700 rounded">
              <i class="mdi mdi-fencing mr-2"></i> Bracketing
            </a>
          </li>
          <li>
            <a href="news/index.php" target="mainFrame" class="flex items-center p-2 hover:bg-blue-700 rounded">
              <i class="mdi mdi-newspaper mr-2"></i> News
            </a>
          </li>
          <li>
            <a href="../logout.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
              <i class="mdi mdi-logout mr-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <header class="bg-white shadow p-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold">Administrator Dashboard</h1>
        <div class="flex items-center space-x-4">
          <img src="assets/images/profile/user-1.jpg" alt="Profile" class="w-8 h-8 rounded-full">
          <a href="../changepassword.php" target="mainFrame" class="text-sm text-gray-600 hover:underline">Change
            Password</a>
        </div>
      </header>

      <!-- Main Frame -->
      <iframe src="dashboard.php" name="mainFrame" class="flex-1 w-full border-none"></iframe>
    </div>
  </div>
</body>

</html>