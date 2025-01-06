<?php
include '../../config.php';

$id = $_POST['id'];

// Fetch user data
$sql = "SELECT users.*, CONCAT(users.firstname, ' ', users.lastname) AS fullName FROM users WHERE id = $id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch course data
$course_sql = "SELECT * FROM course";
$course_result = $conn->query($course_sql);
$courses = [];
while ($row = $course_result->fetch_assoc()) {
    $courses[] = $row;
}

$data = [
    'id' => $user['id'],
    'lastName' => $user['lastname'],
    'firstName' => $user['firstname'],
    'middleName' => $user['middlename'],
    'year' => $user['year'],
    'course' => $user['course'],
    'fullName' => $user['fullName'],
    'courses' => $courses
];

echo json_encode($data);
?>
