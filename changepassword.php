<?php
include_once 'connectdb.php';
session_start();
if($_SESSION['usermail']==""){
  header('location:../index.php');
}

include_once "header.php";

if (isset($_POST['btnupdate'])) {
  $oldpassword_txt = $_POST['txt_oldpassword'];
  $newpassword_txt = $_POST['txt_newpassword'];
  $cpassword_txt   = $_POST['txt_cpassword'];

  // ✅ Get usermail from session
  if (!isset($_SESSION['usermail'])) {
      $_SESSION['status'] = "User not logged in";
      $_SESSION['status_code'] = "error";
      exit;
  }
  $usermail = $_SESSION['usermail'];

  // ✅ Select user
  $select = $pdo->prepare("SELECT * FROM tbl_user WHERE usermail = :usermail");
  $select->execute([':usermail' => $usermail]);
  $row = $select->fetch(PDO::FETCH_ASSOC);

  if ($row) {
      $usermail_db = $row['usermail'];
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

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Change password</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- ✅ Just added this to show email + username -->
    <div class="container-fluid mb-3">
      <?php if (!empty($usermail) && !empty($username)) { ?>
        <p><b><?php echo $usermail; ?></b> - <?php echo $username; ?></p>
      <?php } ?>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <!-- Horizontal Form -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Change Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="" method="post">
                <div class="card-body">

                  <div class="form-group row">
                    <label for="oldPassword" class="col-sm-2 col-form-label">Old Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="oldPassword" placeholder="Old Password" name="txt_oldpassword" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="newPassword" class="col-sm-2 col-form-label">New Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="newPassword" placeholder="New Password" name="txt_newpassword" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="txt_cpassword" required>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="btnupdate">Update Password</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- /.card -->

          </div>
        </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
include_once "footer.php";
?>


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
  unset($_SESSION['status']); // clears after showing once
}
?>


