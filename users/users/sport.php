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
            <th hidden>ID</th>
            <th>SPORT</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM sport";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
                <td hidden><?php echo $row['id']; ?> </td>    
                <td><?php echo $row['name']; ?> </td>
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
