<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lloyd_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed". $conn->connect_error);
}

$sql = "SELECT id,username,password FROM tbl_user1";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container bg-white p-4 rounded shadow">
    <h2 class="text-center text-primary mb-4">User Records</h2>
<?php if($result->num_rows > 0): ?>
    <table class="table table-bordered">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>

       <tbody>
         <?php while($row = $result->fetch_assoc()){?>
                <tr>
                    <td><?= ($row['id'])?></td>
                    <td><?= ($row['username'])?></td>

                    <td>
                        <a href="edit.php ?id=<? $row['id'];?>"class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php ?id=<? $row['id'];?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this User?');> Delete </a>
                    </td>    
                </tr>
                <?php }?>
    </table>
    <?php else: ?>
        <div class="alert-info text-center>No records found</a>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="form.html"class="btn btn-primary">Add New User</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></scrip>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

</html>

<?php $conn->close(); ?>
