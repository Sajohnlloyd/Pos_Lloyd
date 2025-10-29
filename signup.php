<?php 
include_once "ui/connectdb.php";
session_start();

if (isset($_POST['btn_register'])) {
    $username = $_POST['txt_username'];
    $usermail = $_POST['txt_mail'];
    $password = $_POST['txt_password'];
    $role     = $_POST['txt_role']; // Admin or User

    // ✅ Check if email already exists
    $check = $pdo->prepare("SELECT * FROM tbl_user WHERE usermail = :usermail");
    $check->execute([':usermail' => $usermail]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $_SESSION['status'] = "Email already registered!";
        $_SESSION['status_code'] = "error";
    } else {
        // ✅ Insert new user
        $insert = $pdo->prepare("INSERT INTO tbl_user (username, usermail, userpassword, role)
                                 VALUES (:username, :usermail, :password, :role)");
        $insert->execute([
            ':username' => $username,
            ':usermail' => $usermail,
            ':password' => $password,
            ':role' => $role
        ]);

        $_SESSION['status'] = "Registration successful!";
        $_SESSION['status_code'] = "success";
        header("Location: index.php"); // redirect to login page
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS BARCODE_SYSTEM | Sign Up</title>

  <!-- Google Font -->
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

  <style>
    body {
      background: linear-gradient(135deg, #007bff 30%, #6610f2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-box {
      width: 420px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .card-header {
      border-radius: 15px 15px 0 0;
    }
  </style>
</head>

<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center bg-primary">
      <a href="#" class="h1 text-white"><b>POS</b>_Lloyd</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Create your account</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full Name" name="txt_username" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>

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

        <div class="input-group mb-4">
          <select class="form-control" name="txt_role" required>
            <option value="">Select Role</option>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-users"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-8 d-flex align-items-center">
            <a href="index.php" class="text-center">Already have an account?</a>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="btn_register">Sign Up</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<?php
// SweetAlert notification
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
    <script>
      $(function() {
        Swal.fire({
          toast: true,
          position: 'top',
          icon: '<?php echo $_SESSION['status_code']; ?>',
          title: '<?php echo $_SESSION['status']; ?>',
          showConfirmButton: false,
          timer: 4000
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
