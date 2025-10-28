<?php
session_start();
if (!isset($_SESSION['usermail'])) {
    header("Location: ../index.php");
    exit;
}

include_once "header.php"; 
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Page</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
        
            <div class="card card-success card-outline">
              <div class="card-header">
                <h5 class="m-0">Welcome</h5>
              </div>
              <div class="card-body">
                <h6 class="card-title">Hello, <?php echo $_SESSION['username']; ?></h6>
                <p class="card-text">This is the User dashboard area.</p>
                <a href="#" class="btn btn-success">Go somewhere</a>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
// ✅ Show SweetAlert toast once after login
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
?>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
  $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top',
      showConfirmButton: false,
      timer: 3000
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

<?php
include_once "footer.php";
?>
