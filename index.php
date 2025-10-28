<?php
include_once "ui/connectdb.php";
session_start();

if (isset($_POST['btn_login'])) {
    $usermail = $_POST['txt_mail'];
    $password = $_POST['txt_password'];

    $select = $pdo->prepare("SELECT * FROM tbl_user WHERE usermail = :usermail");
    $select->execute([':usermail' => $usermail]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['usermail'] == $usermail && $row['userpassword'] == $password && $row['role'] == "Admin") {
            $_SESSION['userid']   = $row['userid'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['usermail'] = $row['usermail'];
            $_SESSION['role']     = $row['role'];

            $_SESSION['status']      = "Login Success By Admin";
            $_SESSION['status_code'] = "success";

            header('Location: ui/dashboard.php');
            exit;
        } elseif ($row['usermail'] == $usermail && $row['userpassword'] == $password && $row['role'] == "User") {
            $_SESSION['userid']   = $row['userid'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['usermail'] = $row['usermail'];
            $_SESSION['role']     = $row['role'];

            $_SESSION['status']      = "Login Success By User";
            $_SESSION['status_code'] = "success";

            header('Location: ui/user.php');
            exit;
        } else {
            $_SESSION['status']      = "Wrong Email or Password";
            $_SESSION['status_code'] = "error";
        }
    } else {
        $_SESSION['status']      = "Wrong Email or Password";
        $_SESSION['status_code'] = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS BARCODE_SYSTEM_Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>POS</b>_Lloyd</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="txt_mail" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="txt_password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        <div class="row">
          <div class="col-8"><div class="icheck-primary"><a href="forgot-password.html">I forgot my password</a></div></div>
          <div class="col-4"><button type="submit" class="btn btn-primary btn-block" name="btn_login">Login</button></div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<?php
// show toast once if session status set
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    // echo small toast using SweetAlert2
    ?>
    <script>
      $(function() {
        var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 5000
        });

        Toast.fire({
          icon: '<?php echo $_SESSION['status_code']; ?>',
          title: '<?php echo $_SESSION['status']; ?>'
        });
      });
    </script>
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status_code']);
}
?>
</body>
</html>
