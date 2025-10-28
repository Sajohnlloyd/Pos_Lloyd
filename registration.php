<?php
include_once 'connectdb.php';
session_start();

if($_SESSION['usermail']==""){
  header('location:../index.php');
  exit();
}

// ✅ Restrict Registration page only for Admins
if($_SESSION['role'] != "Admin"){
  $_SESSION['status'] = "Access denied! Only Admin can open registration.";
  $_SESSION['status_code'] = "error";
  header("location:index.php");
  exit();
}

include_once "header.php";

if (isset($_POST['btnupdate'])) {
  $oldpassword_txt = $_POST['txt_oldpassword'];
  $newpassword_txt = $_POST['txt_newpassword'];
  $cpassword_txt   = $_POST['txt_cpassword'];

  if (!isset($_SESSION['usermail'])) {
      $_SESSION['status'] = "User not logged in";
      $_SESSION['status_code'] = "error";
      exit;
  }
  $usermail = $_SESSION['usermail'];

  $select = $pdo->prepare("SELECT * FROM tbl_user WHERE usermail = :usermail");
  $select->execute([':usermail' => $usermail]);
  $row = $select->fetch(PDO::FETCH_ASSOC);

  if ($row) {
      $password_db = $row['userpassword'];

      if ($oldpassword_txt == $password_db) {
          if ($newpassword_txt == $cpassword_txt) {
              $update = $pdo->prepare("UPDATE tbl_user SET userpassword = :newpassword WHERE usermail = :usermail");
              $update->execute([
                  ':newpassword' => $newpassword_txt,
                  ':usermail'    => $usermail
              ]);
              $_SESSION['status'] = "Password Changed Successfully";
              $_SESSION['status_code'] = "success";
          } else {
              $_SESSION['status'] = "New password and Confirm password do not match";
              $_SESSION['status_code'] = "error";
          }
      } else {
          $_SESSION['status'] = "Old password is incorrect";
          $_SESSION['status_code'] = "error";
      }
  } else {
      $_SESSION['status'] = "User not found";
      $_SESSION['status_code'] = "error";
  }
}

error_reporting(0);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $delete = $pdo->prepare("DELETE FROM tbl_user WHERE userid = :id");
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        $_SESSION['status'] = "Account deleted successfully";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Account is not deleted";
        $_SESSION['status_code'] = "warning";
    }

    echo "<script>window.location.href='registration.php';</script>";
    exit();
}

if(isset($_POST['btnsave'])){
    $username     = $_POST['txtname'];
    $usermail     = $_POST['txtemail'];
    $userpassword = $_POST['txtpassword'];
    $userrole     = $_POST['txtselect_option'];

    $select = $pdo->prepare("SELECT usermail FROM tbl_user WHERE usermail = :mail");
    $select->bindParam(':mail', $usermail);
    $select->execute();

    if($select->rowCount() > 0){
        $_SESSION['status'] = "Email already exists!";
        $_SESSION['status_code'] = "error";
    } else {
        $insert = $pdo->prepare("INSERT INTO tbl_user (username, usermail, userpassword, role) 
                                 VALUES (:name, :mail, :password, :role)");

        $insert->bindParam(':name', $username);
        $insert->bindParam(':mail', $usermail);
        $insert->bindParam(':password', $userpassword);
        $insert->bindParam(':role', $userrole);

        try {
            if($insert->execute()){
                $_SESSION['status'] = "User inserted successfully into database!";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Error inserting user!";
                $_SESSION['status_code'] = "error";
            }
        } catch (PDOException $e) {
            $_SESSION['status'] = "Database Error: " . $e->getMessage();
            $_SESSION['status_code'] = "error";
        }
    }
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Registration</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
     
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="m-0">Registration</h5>
          </div>
          <div class="card-body">

            <div class="row">
              <div class="col-md-4">
                <form action="" method="post">
                  <div class="form-group">
                    <label for="txtname">Name</label>
                    <input type="text" class="form-control" placeholder="Enter Name" name="txtname" required>
                  </div>

                  <div class="form-group">
                    <label for="txtemail">Email address</label>
                    <input type="email" class="form-control" placeholder="Enter email" name="txtemail" required>
                  </div>

                  <div class="form-group">
                    <label for="txtpassword">Password</label>
                    <input type="password" class="form-control" placeholder="Password" name="txtpassword" required>
                  </div>

                  <div class="form-group">
                    <label>Role</label>
                    <select class="form-control" name="txtselect_option" required>
                      <option value="" disabled selected>Select Role</option>
                      <option value="Admin">Admin</option>
                      <option value="User">User</option>
                    </select>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                  </div>
                </form>
              </div>

              <div class="col-md-8">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <td>#</td>
                      <td>Name</td>
                      <td>Email</td>
                      <td>Password</td>
                      <td>Role</td>
                      <td>Delete</td>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                    $select = $pdo->prepare("SELECT * FROM tbl_user ORDER BY userid ASC");
                    $select->execute();

                    while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        echo '
                        <tr>
                          <td>'.$row['userid'].'</td>
                          <td>'.$row['username'].'</td>
                          <td>'.$row['usermail'].'</td>
                          <td>'.$row['userpassword'].'</td>
                          <td>'.$row['role'].'</td>
                          <td>
                            <a href="registration.php?id='.$row['userid'].'" class="btn btn-danger">
                              <i class="fa fa-trash-alt"></i>
                            </a>
                          </td>
                        </tr>';
                    }
                    ?>
                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
            
      </div>
    </div>
  </div>

<?php
include_once "footer.php";
?>

<?php
if(isset($_SESSION['status']) && $_SESSION['status'] !='' )
{
?>
<script>
    Swal.fire({
      icon: '<?php echo $_SESSION['status_code']; ?>',
      title: '<?php echo $_SESSION['status']; ?>'
    });
</script>
<?php
  unset($_SESSION['status']);
  unset($_SESSION['status_code']);
}
?>
