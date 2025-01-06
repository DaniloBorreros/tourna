<?php  
    include '../../config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor List</title>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>



<div class="table-responsive" style="margin-top: 20px; width: 94%; margin-left: 3%;">
    <table id="myTable" class="display table" width="100%">
        <thead>
        <tr>
            <th hidden>USER ID</th>
            <th>LASTNAME</th>
            <th>FIRSTNAME</th>
            <th>MIDDLENAME</th>
            <th>Yr & COURSE</th>
            <th>ATHLETE?</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                if ($row['year'] == 1) {
                    $yr = '1st';
                }
                if ($row['year'] == 2) {
                    $yr = '2nd';
                }
                if ($row['year'] == 3) {
                    $yr = '3rd';
                }
                if ($row['year'] == 4) {
                    $yr = '4th';
                }
        ?>
        <tr>
                <td hidden><?php echo $row['id']; ?> </td>    
                <td><?php echo $row['lastname']; ?> </td>
                <td><?php echo $row['firstname']; ?> </td>
                <td><?php echo $row['middlename']; ?> </td>
                <td><?php echo $yr.' year '.$row['course']; ?></td>
                <td><?php

                    if ($row['athlete'] == 1) {
                        echo 'Yes';
                    }
                    else{
                        echo 'No';
                    }

                ?>
                </td>
                <td>
                    <button class="btn btn-success edit-btn">Edit</button>

                    <button class="btn btn-danger delete-btn">Delete</button>
                </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='4'>No doctors found.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('#myTable').dataTable();
    });
</script>

</body>
</html>
